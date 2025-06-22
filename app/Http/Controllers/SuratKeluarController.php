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
        
        $suratKeluar = $query->paginate(10);
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
                'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|max:2048',
            ]);

            // Handle file upload
            if ($request->hasFile('lampiran')) {
                $file = $request->file('lampiran');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('lampiran/surat-keluar', $fileName, 'public');
                $validated['lampiran'] = $path;
            }

            // Create record
            $suratKeluar = SuratKeluar::create($validated);
            
            return redirect()->route('surat-keluar.index')
                ->with('success', ' Surat keluar berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            // Jika terjadi error saat upload file, hapus file yang sudah terupload
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            return redirect()->back()
                ->with('error', ' Terjadi kesalahan saat menambahkan surat keluar: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        $suratKeluar = SuratKeluar::findOrFail($id);
        return view('surat-keluar.edit', compact('suratKeluar'));
    }

    public function update(Request $request, $id)
    {
        try {
            $suratKeluar = SuratKeluar::findOrFail($id);
            $validated = $request->validate([
                'no_surat' => 'required|string|max:255',
                'tanggal' => 'required|date',
                'perihal' => 'required|string|max:255',
                'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|max:2048',
            ]);

            if ($request->hasFile('lampiran')) {
                // Hapus file lama jika ada
                if ($suratKeluar->lampiran) {
                    Storage::disk('public')->delete($suratKeluar->lampiran);
                }
                
                // Upload file baru
                $file = $request->file('lampiran');
                $path = $file->store('lampiran/surat-keluar', 'public');
                $validated['lampiran'] = $path;
            }

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
        // Hapus file lampiran jika ada
        if ($suratKeluar->lampiran) {
            Storage::disk('public')->delete($suratKeluar->lampiran);
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