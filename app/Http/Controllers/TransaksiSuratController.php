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
}
