<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\SK;
use App\Models\Perda;
use App\Models\Pergub;
use App\Models\SppdDalamDaerah;
use App\Models\SppdLuarDaerah;
use App\Models\SptDalamDaerah;
use App\Models\SptLuarDaerah;
use App\Models\ApprovalRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\ApprovalRequestNotification;
use Illuminate\Support\Facades\Notification;

class TransaksiSuratController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $tab = $request->tab ?? 'surat-masuk';

        // Query dasar untuk setiap model
        $suratMasuk = SuratMasuk::query();
        $suratKeluar = SuratKeluar::query();
        $sk = SK::query();
        $perda = Perda::query();
        $pergub = Pergub::query();
        $sppdDalam = SppdDalamDaerah::query();
        $sppdLuar = SppdLuarDaerah::query();
        $sptDalam = SptDalamDaerah::query();
        $sptLuar = SptLuarDaerah::query();

        // Jika ada pencarian
        if ($search) {
            $suratMasuk->where(function($query) use ($search) {
                $query->where('no_surat', 'like', "%{$search}%")
                      ->orWhere('pengirim', 'like', "%{$search}%")
                      ->orWhere('perihal', 'like', "%{$search}%");
            });

            $suratKeluar->where(function($query) use ($search) {
                $query->where('no_surat', 'like', "%{$search}%")
                      ->orWhere('perihal', 'like', "%{$search}%");
            });

            $sk->where(function($query) use ($search) {
                $query->where('no_surat', 'like', "%{$search}%")
                      ->orWhere('perihal', 'like', "%{$search}%");
            });

            $perda->where(function($query) use ($search) {
                $query->where('no_surat', 'like', "%{$search}%")
                      ->orWhere('perihal', 'like', "%{$search}%");
            });

            $pergub->where(function($query) use ($search) {
                $query->where('no_surat', 'like', "%{$search}%")
                      ->orWhere('perihal', 'like', "%{$search}%");
            });

            $sppdDalam->where(function($query) use ($search) {
                $query->where('no_surat', 'like', "%{$search}%")
                      ->orWhere('tujuan', 'like', "%{$search}%")
                      ->orWhere('perihal', 'like', "%{$search}%");
            });

            $sppdLuar->where(function($query) use ($search) {
                $query->where('no_surat', 'like', "%{$search}%")
                      ->orWhere('tujuan', 'like', "%{$search}%")
                      ->orWhere('perihal', 'like', "%{$search}%");
            });

            $sptDalam->where(function($query) use ($search) {
                $query->where('no_surat', 'like', "%{$search}%")
                      ->orWhere('tujuan', 'like', "%{$search}%")
                      ->orWhere('perihal', 'like', "%{$search}%");
            });

            $sptLuar->where(function($query) use ($search) {
                $query->where('no_surat', 'like', "%{$search}%")
                      ->orWhere('tujuan', 'like', "%{$search}%")
                      ->orWhere('perihal', 'like', "%{$search}%");
            });
        }

        // Ambil hasil query dengan Session Sorting (Default 'desc' = Terbaru)
        if ($request->has('sort')) {
            session(['transaksi_surat_sort' => $request->sort]);
        }
        $sortOrder = session('transaksi_surat_sort', 'desc');

        $suratMasuk = $suratMasuk->orderBy('created_at', $sortOrder)->paginate(10)->appends($request->query());
        $suratKeluar = $suratKeluar->orderBy('created_at', $sortOrder)->get();
        $sk = $sk->orderBy('created_at', $sortOrder)->paginate(10)->appends($request->query());
        $perda = $perda->orderBy('created_at', $sortOrder)->paginate(10)->appends($request->query());
        $pergub = $pergub->orderBy('created_at', $sortOrder)->paginate(10)->appends($request->query());
        $sppdDalam = $sppdDalam->orderBy('created_at', $sortOrder)->get();
        $sppdLuar = $sppdLuar->orderBy('created_at', $sortOrder)->get();
        $sptDalam = $sptDalam->orderBy('created_at', $sortOrder)->get();
        $sptLuar = $sptLuar->orderBy('created_at', $sortOrder)->get();

        // Hitung total untuk setiap jenis surat
        $totalSuratMasuk = $suratMasuk->total();
        $totalSuratKeluar = $suratKeluar->count();
        $totalSK = $sk->total();
        $totalPerda = $perda->total();
        $totalPergub = $pergub->total();
        $totalSppdDalam = $sppdDalam->count();
        $totalSppdLuar = $sppdLuar->count();
        $totalSptDalam = $sptDalam->count();
        $totalSptLuar = $sptLuar->count();

        // Cek status permintaan persetujuan untuk user
        $approvalRequest = null;
        if (Auth::user()->role === 'user') {
            $approvalRequest = ApprovalRequest::where('user_id', Auth::id())
                ->whereIn('status', ['pending', 'approved', 'rejected'])
                ->orderBy('created_at', 'desc')
                ->first();
        }

        return view('transaksi-surat.index', compact(
            'suratMasuk', 'suratKeluar', 'sk', 'perda', 'pergub',
            'sppdDalam', 'sppdLuar', 'sptDalam', 'sptLuar',
            'totalSuratMasuk', 'totalSuratKeluar', 'totalSK', 
            'totalPerda', 'totalPergub', 'totalSppdDalam', 
            'totalSppdLuar', 'totalSptDalam', 'totalSptLuar',
            'approvalRequest', 'search', 'tab', 'sortOrder'
        ));
    }

    public function requestApproval(Request $request)
    {
        $request->validate([
            'letter_type' => 'required|string',
            'sender' => 'required|string|max:255',
            'notes' => 'required|string|max:500',
        ]);

        $approvalRequest = ApprovalRequest::create([
            'user_id' => Auth::id(),
            'letter_type' => $request->letter_type,
            'sender' => $request->sender,
            'notes' => $request->notes,
            'status' => 'pending'
        ]);

        // Kirim notifikasi ke admin
        $admins = User::whereIn('role', ['admin', 'superadmin'])->get();
        Notification::send($admins, new ApprovalRequestNotification($approvalRequest));

        return redirect()->route('data-requests.index')
            ->with('success', 'Permintaan persetujuan telah dikirim. Anda akan mendapatkan notifikasi setelah diproses oleh admin.');
    }

    public function approveRequest(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Hanya admin yang dapat menyetujui permintaan.');
        }

        $approvalRequest = ApprovalRequest::findOrFail($id);
        $approvalRequest->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'notes' => $request->notes
        ]);

        return redirect()->back()->with('success', 'Permintaan telah disetujui.');
    }

    public function rejectRequest(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Hanya admin yang dapat menolak permintaan.');
        }

        $approvalRequest = ApprovalRequest::findOrFail($id);
        $approvalRequest->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'notes' => $request->notes
        ]);

        return redirect()->back()->with('success', 'Permintaan telah ditolak.');
    }

    public function create()
    {
        if (Auth::user()->role === 'user') {
            $approvalRequest = ApprovalRequest::where('user_id', Auth::id())
                ->where('status', 'approved')
                ->first();

            if (!$approvalRequest) {
                return redirect()->route('transaksi-surat.index')
                    ->with('error', 'Anda perlu mendapatkan persetujuan admin terlebih dahulu.');
            }
        }

        return view('transaksi-surat.create');
    }

    public function showRequestForm()
    {
        return view('transaksi-surat.request-form');
    }
} 