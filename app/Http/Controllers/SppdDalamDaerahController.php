<?php

namespace App\Http\Controllers;

use App\Models\SppdDalamDaerah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\SppdDalamDaerahExport;
use Maatwebsite\Excel\Facades\Excel;

class SppdDalamDaerahController extends Controller
{
    public function index(Request $request)
    {
        $query = SppdDalamDaerah::query();
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_surat', 'LIKE', "%{$search}%")
                  ->orWhere('tujuan', 'LIKE', "%{$search}%")
                  ->orWhere('perihal', 'LIKE', "%{$search}%")
                  ->orWhere('nama_petugas', 'LIKE', "%{$search}%");
            });
        }
        
        $sppd = $query->latest()->paginate(10);
        return view('sppd-dalam-daerah.index', compact('sppd'));
    }

    public function create()
    {
        $nomorSurat = SppdDalamDaerah::generateNomorSurat();
        return view('sppd-dalam-daerah.create', compact('nomorSurat'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'no_surat' => 'required|string|max:255',
                'tanggal' => 'required|date',
                'tujuan' => 'required|string|max:255',
                'perihal' => 'required|string|max:255',
                'nama_petugas' => 'required|string',
                'lampiran' => 'required|array',
                'lampiran.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2147483648',
            ]);

            $lampiranPaths = [];
            if ($request->hasFile('lampiran')) {
                $files = $request->file('lampiran');
                if (!is_array($files)) {
                    $files = [$files];
                }
                foreach ($files as $file) {
                    $lampiranPaths[] = [
                        'path' => $file->store('lampiran/sppd-dalam-daerah', 'public'),
                        'name' => $file->getClientOriginalName(),
                    ];
                }
                $validated['lampiran'] = json_encode($lampiranPaths);
            }

            SppdDalamDaerah::create($validated);

            return redirect()->route('sppd-dalam-daerah.index')
                ->with('success', 'SPPD Dalam Daerah berhasil ditambahkan');
        } catch (\Exception $e) {
            if (isset($lampiranPaths)) {
                foreach ($lampiranPaths as $lampiran) {
                    if (isset($lampiran['path']) && Storage::disk('public')->exists($lampiran['path'])) {
                        Storage::disk('public')->delete($lampiran['path']);
                    }
                }
            }
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan SPPD Dalam Daerah: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function detail($id)
    {
        $sppd = SppdDalamDaerah::findOrFail($id);
        return view('sppd-dalam-daerah.detail', compact('sppd'));
    }

    public function edit($id)
    {
        $sppdDalamDaerah = SppdDalamDaerah::findOrFail($id);
        $lampiranLama = $sppdDalamDaerah->lampiran ? array_values(json_decode($sppdDalamDaerah->lampiran, true) ?? []) : [];
        return view('sppd-dalam-daerah.edit', compact('sppdDalamDaerah', 'lampiranLama'));
    }

    public function update(Request $request, $id)
    {
        try {
            $sppdDalamDaerah = SppdDalamDaerah::findOrFail($id);
            $validated = $request->validate([
                'no_surat' => 'required|string|max:255',
                'tanggal' => 'required|date',
                'tujuan' => 'required|string|max:255',
                'perihal' => 'required|string|max:255',
                'nama_petugas' => 'required|string',
                'lampiran' => 'nullable|array',
                'lampiran.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2147483648',
            ]);

            // Ambil lampiran lama yang dipertahankan
            $lampiranLama = $request->input('lampiran_lama', []);
            $lampiranDihapus = $request->input('lampiran_dihapus', []);
            $lampiranData = [];
            $lampiranSebelumnya = json_decode($sppdDalamDaerah->lampiran, true) ?? [];

            // Proses lampiran yang ada
            foreach ($lampiranSebelumnya as $file) {
                // Jika file tidak dihapus dan masih ada di lampiran_lama
                if (!in_array($file['path'], $lampiranDihapus) && in_array($file['path'], $lampiranLama)) {
                    $lampiranData[] = $file;
                } else {
                    // Hapus file fisik jika user hapus lampiran
                    if (Storage::disk('public')->exists($file['path'])) {
                        Storage::disk('public')->delete($file['path']);
                    }
                }
            }

            // Proses upload file baru
            if ($request->hasFile('lampiran')) {
                foreach ($request->file('lampiran') as $file) {
                    $lampiranData[] = [
                        'path' => $file->store('lampiran/sppd-dalam-daerah', 'public'),
                        'name' => $file->getClientOriginalName(),
                    ];
                }
            }

            $validated['lampiran'] = json_encode($lampiranData);

            $sppdDalamDaerah->update($validated);

            return redirect()->route('sppd-dalam-daerah.index')
                ->with('success', 'SPPD Dalam Daerah berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy(SppdDalamDaerah $sppdDalamDaerah)
    {
        try {
            // Delete file if exists
            if ($sppdDalamDaerah->lampiran) {
                $lampiranData = json_decode($sppdDalamDaerah->lampiran, true);
                if (is_array($lampiranData)) {
                    foreach ($lampiranData as $lampiran) {
                        if (isset($lampiran['path']) && Storage::disk('public')->exists($lampiran['path'])) {
                            Storage::disk('public')->delete($lampiran['path']);
                        }
                    }
                }
            }

            $sppdDalamDaerah->delete();

            return redirect()->route('sppd-dalam-daerah.index')
                ->with('success', 'SPPD Dalam Daerah berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('sppd-dalam-daerah.index')
                ->with('error', 'Gagal menghapus SPPD Dalam Daerah: ' . $e->getMessage());
        }
    }

    public function export()
    {
        return Excel::download(new SppdDalamDaerahExport, 'sppd-dalam-daerah.xlsx');
    }
} 