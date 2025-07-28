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

            Log::debug('APPROVE: Mulai proses persetujuan', [
                'approvalRequest_id' => $approvalRequest->id,
                'request_data' => $request->all()
            ]);

            // Check for duplicate no_surat based on letter type
            $duplicateCheck = $this->checkDuplicateNoSurat($approvalRequest->no_surat, $approvalRequest->letter_type);
            Log::debug('APPROVE: Cek duplikat nomor surat', [
                'no_surat' => $approvalRequest->no_surat,
                'letter_type' => $approvalRequest->letter_type,
                'duplicate' => $duplicateCheck
            ]);
            
            if ($duplicateCheck) {
                DB::rollBack();
                Log::warning('APPROVE: Gagal karena duplikat nomor surat', [
                    'no_surat' => $approvalRequest->no_surat,
                    'letter_type' => $approvalRequest->letter_type
                ]);
                return redirect()->back()->with('error', 'Nomor surat sudah ada dalam sistem, Silakan periksa kembali.');
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
            Log::debug('APPROVE: ApprovalRequest diupdate', [
                'approvalRequest' => $approvalRequest->toArray()
            ]);

            // Create entry in the corresponding main table
            $lampiranPaths = [];
            if ($request->hasFile('lampiran')) {
                foreach ($request->file('lampiran') as $file) {
                    $lampiranPaths[] = [
                        'path' => $file->store('lampiran', 'public'),
                        'name' => $file->getClientOriginalName(),
                    ];
                }
            } else {
                // Ambil lampiran dari approvalRequest jika tidak ada upload baru
                $lampiranPaths = json_decode($approvalRequest->lampiran, true) ?? [];
            }
            Log::debug('APPROVE: Data lampiran yang akan disimpan', [
                'lampiranPaths' => $lampiranPaths
            ]);
            $data = [
                'no_agenda' => $request->no_agenda,
                'no_surat' => $approvalRequest->no_surat,
                'pengirim' => $approvalRequest->sender,
                'tanggal_surat' => $approvalRequest->tanggal_surat,
                'tanggal_terima' => $request->tanggal_diterima,
                'perihal' => $approvalRequest->perihal,
                'lampiran' => json_encode($lampiranPaths),
                'status' => 'tercatat',
                'submitted_by' => $approvalRequest->user_id,
                'catatan' => $approvalRequest->notes,
                'disposisi' => $request->disposisi,
            ];
            Log::debug('APPROVE: Data surat yang akan disimpan', [
                'data' => $data,
                'letter_type' => $approvalRequest->letter_type
            ]);

            switch ($approvalRequest->letter_type) {
                case 'surat_masuk':
                    $result = SuratMasuk::create($data);
                    Log::debug('APPROVE: SuratMasuk::create result', ['result' => $result]);
                    break;
                case 'sk':
                    $result = SK::create($data);
                    Log::debug('APPROVE: SK::create result', ['result' => $result]);
                    break;
                case 'perda':
                    $result = Perda::create($data);
                    Log::debug('APPROVE: Perda::create result', ['result' => $result]);
                    break;
                case 'pergub':
                    $result = Pergub::create($data);
                    Log::debug('APPROVE: Pergub::create result', ['result' => $result]);
                    break;
                default:
                    Log::error('APPROVE: Jenis surat tidak valid', ['letter_type' => $approvalRequest->letter_type]);
                    throw new \Exception('Jenis surat tidak valid');
            }

            DB::commit();
            Log::info('APPROVE: Proses persetujuan selesai dan data surat berhasil disimpan', [
                'approvalRequest_id' => $approvalRequest->id,
                'letter_type' => $approvalRequest->letter_type
            ]);

            // Send notification to user
            $approvalRequest->user->notify(new ApprovalRequestNotification($approvalRequest));

            return redirect()->back()->with('success', 'Data surat berhasil di simpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error approving request: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
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

    public function toggleFisik($id)
    {
        $request = ApprovalRequest::findOrFail($id);
        // Hanya boleh toggle jika status sudah approved
        if ($request->status !== 'approved') {
            return response()->json(['status' => 'forbidden'], 403);
        }
        $request->fisik_diterima = !$request->fisik_diterima;
        $request->fisik_diterima_at = $request->fisik_diterima ? now() : null;
        $request->save();

        return response()->json([
            'status' => 'success',
            'fisik_diterima' => $request->fisik_diterima,
            'fisik_diterima_at' => $request->fisik_diterima_at ? $request->fisik_diterima_at->format('d M Y H:i') : null
        ]);
    }
} 