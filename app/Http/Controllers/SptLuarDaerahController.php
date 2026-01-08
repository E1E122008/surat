<?php

namespace App\Http\Controllers;

use App\Models\SptLuarDaerah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\SptLuarDaerahExport;
use Maatwebsite\Excel\Facades\Excel;

class SptLuarDaerahController extends Controller
{
    public function index(Request $request)
    {
        $query = SptLuarDaerah::latest();
        
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
        
        $sptLuarDaerah = $query->latest()->paginate(10);
        return view('spt-luar-daerah.index', compact('sptLuarDaerah'));
    }

    public function create()
    {
        $nomorSurat = SptLuarDaerah::generateNomorSurat();
        return view('spt-luar-daerah.create', compact('nomorSurat'));
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
                        'path' => $file->store('lampiran/spt-luar-daerah', 'public'),
                        'name' => $file->getClientOriginalName(),
                    ];
                }
                $validated['lampiran'] = json_encode($lampiranPaths);
        }

        SptLuarDaerah::create($validated);

        return redirect()->route('spt-luar-daerah.index')
            ->with('success', 'SPT Luar Daerah berhasil ditambahkan');
        } catch (\Exception $e) {
            if (isset($lampiranPaths)) {
                foreach ($lampiranPaths as $lampiran) {
                    if (isset($lampiran['path']) && Storage::disk('public')->exists($lampiran['path'])) {
                        Storage::disk('public')->delete($lampiran['path']);
                    }
                }
            }
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan SPT Luar Daerah: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function detail($id)
    {
        $spt = SptLuarDaerah::findOrFail($id);
        return view('spt-luar-daerah.detail', compact('spt'));
    }

    public function edit($id)
    {
        $sptLuarDaerah = SptLuarDaerah::findOrFail($id);
        $lampiranLama = $sptLuarDaerah->lampiran ? array_values(json_decode($sptLuarDaerah->lampiran, true) ?? []) : [];
        return view('spt-luar-daerah.edit', compact('sptLuarDaerah', 'lampiranLama'));
    }

    public function update(Request $request, $id)
    {   
        try {
            $sptLuarDaerah = SptLuarDaerah::findOrFail($id);
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
            $lampiranSebelumnya = json_decode($sptLuarDaerah->lampiran, true) ?? [];

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
                        'path' => $file->store('lampiran/spt-luar-daerah', 'public'),
                        'name' => $file->getClientOriginalName(),
                    ];
                }
            }

            $validated['lampiran'] = json_encode($lampiranData);

            $sptLuarDaerah->update($validated);

            return redirect()->route('spt-luar-daerah.index')
                ->with('success', 'SPT Luar Daerah berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui SPT Luar Daerah: ' . $e->getMessage());
        }
    }

    public function destroy(SptLuarDaerah $sptLuarDaerah)
    {
        try {
            // Delete file if exists
            if ($sptLuarDaerah->lampiran) {
                $lampiranData = json_decode($sptLuarDaerah->lampiran, true);
                if (is_array($lampiranData)) {
                    foreach ($lampiranData as $lampiran) {
                        if (isset($lampiran['path']) && Storage::disk('public')->exists($lampiran['path'])) {
                            Storage::disk('public')->delete($lampiran['path']);
                        }
                    }
                }
            }

            $sptLuarDaerah->delete();

            return redirect()->route('spt-luar-daerah.index')
                ->with('success', 'SPT Luar Daerah berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('spt-luar-daerah.index')
                ->with('error', 'Gagal menghapus SPT Luar Daerah: ' . $e->getMessage());
        }
    }

    public function export()
    {
        return Excel::download(new SptLuarDaerahExport, 'spt-luar-daerah.xlsx');
    }
} 