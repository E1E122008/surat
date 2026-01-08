<?php

namespace App\Http\Controllers;

use App\Models\SppdLuarDaerah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\SppdLuarDaerahExport;
use Maatwebsite\Excel\Facades\Excel;

class SppdLuarDaerahController extends Controller
{
    public function index(Request $request)
    {
        $query = SppdLuarDaerah::query();
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_surat', 'LIKE', "%{$search}%")
                  ->orWhere('tujuan', 'LIKE', "%{$search}%")
                  ->orWhere('perihal', 'LIKE', "%{$search}%")
                  ->orWhere('nama_petugas', 'LIKE', "%{$search}%");
            });
        }
        
        $sppdLuarDaerah = $query->latest()->paginate(10);
        return view('sppd-luar-daerah.index', compact('sppdLuarDaerah'));
    }

    public function create()
    {
        $nomorSurat = SppdLuarDaerah::generateNomorSurat();
        return view('sppd-luar-daerah.create', compact('nomorSurat'));
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
                        'path' => $file->store('lampiran/sppd-luar-daerah', 'public'),
                        'name' => $file->getClientOriginalName(),
                    ];
                }
                $validated['lampiran'] = json_encode($lampiranPaths);
            }

            SppdLuarDaerah::create($validated);

            return redirect()->route('sppd-luar-daerah.index')
                ->with('success', 'SPPD Luar Daerah berhasil ditambahkan');
        } catch (\Exception $e) {
            if (isset($lampiranPaths)) {
                foreach ($lampiranPaths as $lampiran) {
                    if (isset($lampiran['path']) && Storage::disk('public')->exists($lampiran['path'])) {
                        Storage::disk('public')->delete($lampiran['path']);
                    }
                }
            }
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan SPPD Luar Daerah: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function detail($id)
    {
        $sppd = SppdLuarDaerah::findOrFail($id);
        return view('sppd-luar-daerah.detail', compact('sppd'));
    }

    public function edit($id)
    {
        $sppdLuarDaerah = SppdLuarDaerah::findOrFail($id);
        $lampiranLama = $sppdLuarDaerah->lampiran ? array_values(json_decode($sppdLuarDaerah->lampiran, true) ?? []) : [];
        return view('sppd-luar-daerah.edit', compact('sppdLuarDaerah', 'lampiranLama'));
    }

    public function update(Request $request, $id)
    {
        try {
            $sppdLuarDaerah = SppdLuarDaerah::findOrFail($id);
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
            $lampiranSebelumnya = json_decode($sppdLuarDaerah->lampiran, true) ?? [];

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
                        'path' => $file->store('lampiran/sppd-luar-daerah', 'public'),
                        'name' => $file->getClientOriginalName(),
                    ];
                }
            }

            $validated['lampiran'] = json_encode($lampiranData);

            $sppdLuarDaerah->update($validated);

            return redirect()->route('sppd-luar-daerah.index')
                ->with('success', 'SPPD Luar Daerah berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());    
        }
    }

    public function destroy(SppdLuarDaerah $sppdLuarDaerah)
    {   
        try {
            // Delete file if exists
            if ($sppdLuarDaerah->lampiran) {
                $lampiranData = json_decode($sppdLuarDaerah->lampiran, true);
                if (is_array($lampiranData)) {
                    foreach ($lampiranData as $lampiran) {
                        if (isset($lampiran['path']) && Storage::disk('public')->exists($lampiran['path'])) {
                            Storage::disk('public')->delete($lampiran['path']);
                        }
                    }
                }
            }

            $sppdLuarDaerah->delete();

            return redirect()->route('sppd-luar-daerah.index')
                ->with('success', 'SPPD Luar Daerah berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('sppd-luar-daerah.index')
                ->with('error', 'Gagal menghapus SPPD Luar Daerah: ' . $e->getMessage());
        }
    }

    public function print(SppdLuarDaerah $sppdLuarDaerah)
    {
        return view('sppd-luar-daerah.print', compact('sppdLuarDaerah'));
    }

    public function export()
    {
        return Excel::download(new SppdLuarDaerahExport, 'sppd-luar-daerah.xlsx');
    }
} 