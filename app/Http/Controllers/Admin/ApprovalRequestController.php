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

class ApprovalRequestController extends Controller
{
    public function index()
    {
        $approvalRequests = ApprovalRequest::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Mark notifications as read
        Auth::user()->unreadNotifications
            ->where('type', 'App\Notifications\ApprovalRequestNotification')
            ->markAsRead();

        return view('admin.approval-requests.index', compact('approvalRequests'));
    }

    public function approve(Request $request, $id)
    {
        $approvalRequest = ApprovalRequest::findOrFail($id);

        $request->validate([
            'no_agenda' => 'required|string|max:255',
            'tanggal_diterima' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            // Update approval request status
            $approvalRequest->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'no_agenda' => $request->no_agenda,
                'tanggal_diterima' => $request->tanggal_diterima,
                'notes' => null,
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
                'submitted_by' => $approvalRequest->user_id
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
} 