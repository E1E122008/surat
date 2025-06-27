<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApprovalRequest;
use App\Models\User;
use App\Models\SuratMasuk;
use App\Models\SK;
use App\Models\Perda;
use App\Models\Pergub;
use App\Notifications\ApprovalRequestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ApprovalRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = ApprovalRequest::with('user')->orderBy('created_at', 'desc');

        // Filter status
        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'pending_review' || $request->status === 'pending') {
                $query->where('status', 'pending');
            } else {
                $query->where('status', $request->status);
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('sender', 'like', "%$search%")
                  ->orWhere('letter_type', 'like', "%$search%")
                  ->orWhere('no_surat', 'like', "%$search%");
            });
        }

        $approvalRequests = $query->paginate(10)->withQueryString();
        $totalFiltered = $query->count();
        $totalAll = ApprovalRequest::count();

        // Mark notifications as read
        Auth::user()->unreadNotifications
            ->where('type', 'App\Notifications\ApprovalRequestNotification')
            ->markAsRead();

        return view('admin.approval-requests.index', compact('approvalRequests', 'totalFiltered', 'totalAll'));
    }

    public function approve(Request $request, $id)
    {
        $approvalRequest = ApprovalRequest::findOrFail($id);

        $request->validate([
            'no_agenda' => 'required|string|max:255',
            'tanggal_diterima' => 'required|date',
            'admin_notes' => 'nullable|string',
            'disposisi' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Check for duplicate no_surat based on letter type
            $duplicateCheck = $this->checkDuplicateNoSurat($approvalRequest->no_surat, $approvalRequest->letter_type);
            
            if ($duplicateCheck) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Nomor surat sudah ada dalam sistem. Silakan periksa kembali.');
            }

            // Update approval request status and save admin notes to 'notes' column
            $approvalRequest->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'no_agenda' => $request->no_agenda,
                'tanggal_diterima' => $request->tanggal_diterima,
                'notes' => $request->admin_notes,
            ]);

            // Create entry in the corresponding main table
            $data = [
                'no_agenda' => $request->no_agenda,
                'no_surat' => $approvalRequest->no_surat,
                'pengirim' => $approvalRequest->sender,
                'tanggal_surat' => $approvalRequest->tanggal_surat,
                'tanggal_terima' => $request->tanggal_diterima,
                'perihal' => $approvalRequest->perihal,
                'lampiran' => $approvalRequest->lampiran,
                'status' => 'tercatat',
                'submitted_by' => $approvalRequest->user_id,
                'catatan' => $approvalRequest->notes,
                'disposisi' => $request->disposisi,
            ];

            switch ($approvalRequest->letter_type) {
                case 'surat_masuk':
                    SuratMasuk::create($data);
                    break;
                case 'sk':
                    SK::create($data);
                    break;
                case 'perda':
                    Perda::create($data);
                    break;
                case 'pergub':
                    Pergub::create($data);
                    break;
                default:
                    throw new \Exception('Jenis surat tidak valid');
            }

            DB::commit();

            // Send notification to user
            $approvalRequest->user->notify(new ApprovalRequestNotification($approvalRequest));

            return redirect()->back()->with('success', 'Permintaan berhasil disetujui dan data telah ditambahkan ke sistem');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error approving request: ' . $e->getMessage());
            
            // Check if it's a duplicate key error
            if (str_contains($e->getMessage(), 'Duplicate entry') || str_contains($e->getMessage(), 'UNIQUE constraint failed')) {
                return redirect()->back()->with('error', 'Nomor surat sudah ada dalam sistem. Silakan periksa kembali.');
            }
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses permintaan: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $approvalRequest = ApprovalRequest::findOrFail($id);

        $request->validate([
            'admin_notes' => 'required|string',
        ]);

        $approvalRequest->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'notes' => $request->admin_notes,
        ]);

        
        // Send notification to user
        $approvalRequest->user->notify(new ApprovalRequestNotification($approvalRequest));

        return redirect()->back()->with('success', 'Permintaan berhasil ditolak');
    }

    /**
     * Check for duplicate no_surat in the corresponding table
     */
    private function checkDuplicateNoSurat($noSurat, $letterType)
    {
        switch ($letterType) {
            case 'surat_masuk':
                return SuratMasuk::where('no_surat', $noSurat)->exists();
            case 'sk':
                return SK::where('no_surat', $noSurat)->exists();
            case 'perda':
                return Perda::where('no_surat', $noSurat)->exists();
            case 'pergub':
                return Pergub::where('no_surat', $noSurat)->exists();
            default:
                return false;
        }
    }
} 