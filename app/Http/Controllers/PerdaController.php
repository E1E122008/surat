<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;    
use App\Models\Perda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\Exports\PerdaExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PerdaController extends Controller
{
    public function index(Request $request)
    {
        $query = Perda::query();
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_agenda', 'LIKE', "%{$search}%")
                  ->orWhere('no_surat', 'LIKE', "%{$search}%")
                  ->orWhere('perihal', 'LIKE', "%{$search}%")
                  ->orWhere('pengirim', 'LIKE', "%{$search}%");
            });
        }
        
        $perdas = $query->latest()->paginate(10);

        return view('draft-phd.perda.index', compact('perdas'));
    }

    public function create()
    {
        return view('draft-phd.perda.create'); // Pastikan Anda memiliki tampilan draft-phd/perda/create.blade.php
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'no_agenda' => 'nullable|string|max:255',
                'no_surat' => 'required|string|max:255',
                'pengirim' => 'required|string|max:255',    
                'tanggal_surat' => 'required|date',
                'tanggal_terima' => 'required|date',
                'perihal' => 'required|string|max:255',
                'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
                'catatan' => 'nullable|string',
                'disposisi' => 'nullable|string',
                'admin_notes' => 'nullable|string',
            ]);
            
            $validated['submitted_by'] = Auth::id();

            if ($request->hasFile('lampiran')) {
                $file = $request->file('lampiran');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('lampiran/perda', $fileName, 'public');
                $validated['lampiran'] = $path;
            }
            
            // Set status default to 'tercatat' unless provided
            $validated['status'] = $request->input('status', 'tercatat');
            
            $perda = Perda::create($validated);

            return redirect()->route('draft-phd.perda.index')
                ->with('success', 'Perda berhasil ditambahkan');
        } catch (\Exception $e) {
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            Log::error('Terjadi kesalahan saat menambahkan data!: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan data!' . $e->getMessage())
                ->withInput();
        }
    }

    public function detail($id)
    {
        $perda = Perda::findOrFail($id);
        return view('draft-phd.perda.detail', compact('perda'));
    }

    public function edit($id)
    {   
        $perda = Perda::findOrFail($id);
        return view('draft-phd.perda.edit', compact('perda'));
    }

    public function update(Request $request, $id)
    {
        try {
            $perda = Perda::findOrFail($id);
            $validated = $request->validate([
                'no_agenda' => 'nullable|string|max:255',
                'no_surat' => 'required|string|max:255',
                'pengirim' => 'required|string|max:255',
                'tanggal_surat' => 'required|date',
                'tanggal_terima' => 'required|date',
                'perihal' => 'required|string|max:255',
                'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
                'catatan' => 'nullable|string',
                'disposisi' => 'nullable|string',
                'admin_notes' => 'nullable|string',
                'status' => 'nullable|string|max:255',
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
            Log::error('Terjadi kesalahan saat memperbarui data!' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui data!' . $e->getMessage());
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

    public function status($id)
    {
        $perda = Perda::findOrFail($id);
        return view('draft-phd.perda.status', compact('perda'));
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:tercatat,terdisposisi,diproses,koreksi,diambil,selesai',
            ]);

            $perda = Perda::findOrFail($id);
            $perda->status = $request->status;
            $perda->save();

            return redirect()->back()->with('success', 'Status berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengupdate status: ' . $e->getMessage());
        }
    }

    public function destroy(Perda $perda)
    {
        if ($perda->lampiran) {
            Storage::disk('public')->delete($perda->lampiran);
        }

        $perda->delete();

        return redirect()->route('draft-phd.perda.index')
            ->with('success', 'Surat Peraturan Daerah berhasil dihapus');
    }

    public function export()
    {
        return Excel::download(new PerdaExport(), 'perda.xlsx');
    }

    public function disposisi(Request $request, $id)
    {
        try {
            $request->validate([
                'disposisi' => 'required',
                'catatan' => 'nullable'
            ]);

            $perda = Perda::findOrFail($id);

            // Combine disposisi and catatan into the disposisi field for display/single storage
            $disposisiText = $request->disposisi;
            if ($request->catatan) {
                $disposisiText .= ' | Catatan: ' . $request->catatan;
            }

            $perda->update([
                'disposisi' => $disposisiText,
                'catatan' => $request->catatan // Still store catatan separately if needed
            ]);

            return redirect()->back()
                            ->with('success', 'Disposisi berhasil ditambahkan');

        } catch (\Exception $e) {
            Log::error('Error adding disposisi:', ['error' => $e->getMessage()]);
            return redirect()->back()
                            ->with('error', 'Gagal menambahkan disposisi: ' . $e->getMessage());
        }
    }

    public function updateDisposisi(Request $request, $id)
    {
        try {
            $request->validate([
                'disposisi' => 'required|string|max:255',
            ]);

            $perda = Perda::findOrFail($id);
            $perda->disposisi = $request->disposisi;
            $perda->save();

            return response()->json([
                'success' => true,
                'message' => 'Disposisi berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating disposisi:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui disposisi'
            ], 500);
        }
    }
}
