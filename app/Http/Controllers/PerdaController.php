<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;    
use App\Models\Perda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Excel;
use Carbon\Carbon;

class PerdaController extends Controller
{
    public function index()
    {
        $perdas = Perda::paginate(10);

        return view('draft-phd.perda.index', compact('perdas'));
    }

    public function create()
    {
        return view('draft-phd.perda.create'); // Pastikan Anda memiliki tampilan draft-phd/perda/create.blade.php
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_agenda' => 'required|string|max:255',
            'no_surat' => 'required|string|max:255',
            'pengirim' => 'required|string|max:255',    
            'tanggal_surat' => 'required|date',
            'tanggal_terima' => 'required|date',
            'perihal' => 'required|string|max:255',
            'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);
        
        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $path = $file->store('lampiran/perda', 'public');
            $validated['lampiran'] = $path;
        }
        
        Perda::create($validated);

        return redirect()->route('draft-phd.perda.index')
            ->with('success', 'Perda berhasil ditambahkan');
    }

    public function edit(Perda $perda)
    {
        return view('draft-phd.perda.edit', compact('perda'));
    }

    public function update(Request $request, Perda $perda)
    {
        try {
            $validated = $request->validate([
                'no_agenda' => 'required|string|max:255',
                'no_surat' => 'required|string|max:255',
                'pengirim' => 'required|string|max:255',
                'tanggal_surat' => 'required|date',
                'tanggal_terima' => 'required|date',
                'perihal' => 'required|string|max:255',
                'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            ]);

            if ($request->hasFile('lampiran')) {
                // Hapus file lama jika ada
                if ($perda->lampiran) {
                    Storage::disk('public')->delete($perda->lampiran);
                }

                $file = $request->file('lampiran');
                $path = $file->store('lampiran/perda', 'public');
                $validated['lampiran'] = $path;
            }
            
            $perda->update($validated);

            return redirect()->route('draft-phd.perda.index')
                ->with('success', 'Perda berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui data!');
        }
    }

    public function updateCatatan(Request $request, $id)
    {       
        try {
            $perda = Perda::findOrFail($id);
            $perda->update([
                'catatan' => $request->catatan
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Catatan berhasil diperbarui'
            ]);
                        
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui catatan'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $perda = Perda::findOrFail($id);
            
            if ($perda->lampiran) {
                Storage::disk('public')->delete($perda->lampiran);
            }

            $perda->delete();

            return redirect()->route('draft-phd.perda.index')   
                ->with('success', 'Data perda berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data!');
        }
    }

    public function export()
    {
        return Excel::download(new PerdaExport, 'perda.xlsx');
    }
    
    
    
    
    

}
