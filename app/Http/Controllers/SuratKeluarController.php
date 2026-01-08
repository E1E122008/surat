<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\SuratKeluarExport;
use Maatwebsite\Excel\Facades\Excel;

class SuratKeluarController extends Controller
{
    public function index(Request $request)
    {
        $query = SuratKeluar::latest();
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_surat', 'LIKE', "%{$search}%")
                  ->orWhere('perihal', 'LIKE', "%{$search}%");
            });
        }
        
        $suratKeluar = $query->latest()->paginate(10);
        return view('surat-keluar.index', compact('suratKeluar'));
    }

    public function create()
    {
        $nomorSurat = SuratKeluar::generateNomorSurat();
        return view('surat-keluar.create', compact('nomorSurat'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'no_surat' => 'required|string|max:255',
                'tanggal' => 'required|date',
                'perihal' => 'required|string|max:255',
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
                        'path' => $file->store('lampiran/surat-keluar', 'public'),
                        'name' => $file->getClientOriginalName(),
                    ];
                }
                $validated['lampiran'] = json_encode($lampiranPaths);
            }

            // Create record
            $suratKeluar = SuratKeluar::create($validated);
            
            return redirect()->route('surat-keluar.index')
                ->with('success', ' Surat keluar berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            // Jika terjadi error saat upload file, hapus file yang sudah terupload
            if (isset($lampiranPaths)) {
                foreach ($lampiranPaths as $lampiran) {
                    if (isset($lampiran['path']) && Storage::disk('public')->exists($lampiran['path'])) {
                        Storage::disk('public')->delete($lampiran['path']);
                    }
                }
            }

            return redirect()->back()
                ->with('error', ' Terjadi kesalahan saat menambahkan surat keluar: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        $suratKeluar = SuratKeluar::findOrFail($id);
        $lampiranLama = $suratKeluar->lampiran ? array_values(json_decode($suratKeluar->lampiran, true) ?? []) : [];
        return view('surat-keluar.edit', compact('suratKeluar', 'lampiranLama'));
    }

    public function update(Request $request, $id)
    {
        try {
            $suratKeluar = SuratKeluar::findOrFail($id);
            $validated = $request->validate([
                'no_surat' => 'required|string|max:255',
                'tanggal' => 'required|date',
                'perihal' => 'required|string|max:255',
                'lampiran' => 'nullable|array',
                'lampiran.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2147483648',
            ]);

            // Ambil lampiran lama yang dipertahankan
            $lampiranLama = $request->input('lampiran_lama', []);
            $lampiranDihapus = $request->input('lampiran_dihapus', []);
            $lampiranData = [];
            $lampiranSebelumnya = json_decode($suratKeluar->lampiran, true) ?? [];

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
                        'path' => $file->store('lampiran/surat-keluar', 'public'),
                        'name' => $file->getClientOriginalName(),
                    ];
                }
            }

            $validated['lampiran'] = json_encode($lampiranData);

            $suratKeluar->update($validated);

            return redirect()->route('surat-keluar.index')
                ->with('success', ' Surat keluar berhasil diperbarui!');
                
        } catch (\Exception $e) {
            return redirect()->route('surat-keluar.edit', $suratKeluar->id)
                ->with('error', ' Gagal memperbarui surat keluar: ' . $e->getMessage());
        }
    }

    public function destroy(SuratKeluar $suratKeluar)
    {
        try {
            // Delete file if exists
            if ($suratKeluar->lampiran) {
                $lampiranData = json_decode($suratKeluar->lampiran, true);
                if (is_array($lampiranData)) {
                    foreach ($lampiranData as $lampiran) {
                        if (isset($lampiran['path']) && Storage::disk('public')->exists($lampiran['path'])) {
                            Storage::disk('public')->delete($lampiran['path']);
                        }
                    }
                }
            }

            $suratKeluar->delete();

            return redirect()->route('surat-keluar.index')
                ->with('success', ' Surat keluar berhasil dihapus!');
                
        } catch (\Exception $e) {
            return redirect()->route('surat-keluar.index')
                ->with('error', ' Gagal menghapus surat keluar: ' . $e->getMessage());
        }
    }

    public function export()
    {
        return Excel::download(new SuratKeluarExport, 'surat-keluar.xlsx');
    }
} 