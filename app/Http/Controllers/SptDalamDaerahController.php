<?php

namespace App\Http\Controllers;

use App\Models\SptDalamDaerah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\SptDalamDaerahExport;
use Maatwebsite\Excel\Facades\Excel;

class SptDalamDaerahController extends Controller
{
    public function index(Request $request)
    {
        $query = SptDalamDaerah::latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_surat', 'LIKE', "%{$search}%")
                  ->orWhere('tanggal', 'LIKE', "%{$search}%")
                  ->orWhere('tujuan', 'LIKE', "%{$search}%")
                  ->orWhere('perihal', 'LIKE', "%{$search}%")
                  ->orWhere('nama_petugas', 'LIKE', "%{$search}%");
            });
        }

        $sptDalamDaerah = $query->latest()->paginate(10);
        return view('spt-dalam-daerah.index', compact('sptDalamDaerah'));
    }

    public function create()
    {
        $nomorSurat = SptDalamDaerah::generateNomorSurat();
        return view('spt-dalam-daerah.create', compact('nomorSurat'));
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
                        'path' => $file->store('lampiran/spt-dalam-daerah', 'public'),
                        'name' => $file->getClientOriginalName(),
                    ];
                }
                $validated['lampiran'] = json_encode($lampiranPaths);
            }

            SptDalamDaerah::create($validated);
            return redirect()->route('spt-dalam-daerah.index')
                ->with('success', 'SPT Dalam Daerah berhasil ditambahkan');
        } catch (\Exception $e) {
            if (isset($lampiranPaths)) {
                foreach ($lampiranPaths as $lampiran) {
                    if (isset($lampiran['path']) && Storage::disk('public')->exists($lampiran['path'])) {
                        Storage::disk('public')->delete($lampiran['path']);
                    }
                }
            }
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan SPT Dalam Daerah: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function detail($id)
    {
        $spt = SptDalamDaerah::findOrFail($id);
        return view('spt-dalam-daerah.detail', compact('spt'));
    }

    public function edit($id)
    {
        $sptDalamDaerah = SptDalamDaerah::findOrFail($id);
        $lampiranLama = $sptDalamDaerah->lampiran ? array_values(json_decode($sptDalamDaerah->lampiran, true) ?? []) : [];
        return view('spt-dalam-daerah.edit', compact('sptDalamDaerah', 'lampiranLama'));
    }

    public function update(Request $request, $id)
    {
        try {
            $sptDalamDaerah = SptDalamDaerah::findOrFail($id);
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
            $lampiranSebelumnya = json_decode($sptDalamDaerah->lampiran, true) ?? [];

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
                        'path' => $file->store('lampiran/spt-dalam-daerah', 'public'),
                        'name' => $file->getClientOriginalName(),
                    ];
                }
            }

            $validated['lampiran'] = json_encode($lampiranData);

            $sptDalamDaerah->update($validated);

            return redirect()->route('spt-dalam-daerah.index')
                ->with('success', 'SPT Dalam Daerah berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui SPT Dalam Daerah: ' . $e->getMessage());
        }
    }

    public function destroy(SptDalamDaerah $sptDalamDaerah)
    {
        try {
            // Delete file if exists
            if ($sptDalamDaerah->lampiran) {
                $lampiranData = json_decode($sptDalamDaerah->lampiran, true);
                if (is_array($lampiranData)) {
                    foreach ($lampiranData as $lampiran) {
                        if (isset($lampiran['path']) && Storage::disk('public')->exists($lampiran['path'])) {
                            Storage::disk('public')->delete($lampiran['path']);
                        }
                    }
                }
            }

            $sptDalamDaerah->delete();

            return redirect()->route('spt-dalam-daerah.index')
                ->with('success', 'SPT Dalam Daerah berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('spt-dalam-daerah.index')
                ->with('error', 'Gagal menghapus SPT Dalam Daerah: ' . $e->getMessage());
        }
    }



    public function export()
    {
        return Excel::download(new SptDalamDaerahExport, 'spt-dalam-daerah.xlsx');
    }
} 