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
    // API akses disposisi perda (khusus modal)
    public function apiShow($id)
    {
        $perda = Perda::findOrFail($id);
        return response()->json([
            'id' => $perda->id,
            'disposisi' => $perda->disposisi,
            'status' => $perda->status
        ]);
    }

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
                'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2097152',
                'catatan' => 'nullable|string',
                'disposisi' => 'nullable|string',
                'admin_notes' => 'nullable|string',
            ]);
            
            $validated['submitted_by'] = Auth::id();

            $lampiranPaths = [];
            if ($request->hasFile('lampiran')) {
                $files = $request->file('lampiran');
                if (!is_array($files)) {
                    $files = [$files];
                }
                foreach ($files as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('lampiran/perda', $fileName, 'public');
                    $lampiranPaths[] = [
                        'path' => $path,
                        'name' => $file->getClientOriginalName(),
                    ];
                }
                $validated['lampiran'] = json_encode($lampiranPaths);
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
                'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2097152',
                'catatan' => 'nullable|string',
                'disposisi' => 'nullable|string',
                'admin_notes' => 'nullable|string',
                'status' => 'nullable|string|max:255',
            ]);

            // Proses lampiran - form edit menggunakan single file input (name="lampiran")
            if ($request->hasFile('lampiran')) {
                // Validasi file hanya jika ada file yang diupload
                $request->validate([
                    'lampiran' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2097152',
                ]);
                
                // Hapus file lama jika ada
                if ($perda->lampiran) {
                    $lampiranLama = json_decode($perda->lampiran, true);
                    if (is_array($lampiranLama)) {
                        foreach ($lampiranLama as $lampiran) {
                            if (isset($lampiran['path']) && Storage::disk('public')->exists($lampiran['path'])) {
                                Storage::disk('public')->delete($lampiran['path']);
                            }
                        }
                    }
                }
                
                $file = $request->file('lampiran');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('lampiran/perda', $fileName, 'public');
                $validated['lampiran'] = json_encode([[
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
                ]]);
            } else {
                // Jika tidak ada file baru, pertahankan lampiran yang lama
                $validated['lampiran'] = $perda->lampiran;
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
        try {
            // Delete file if exists
            if ($perda->lampiran) {
                $lampiranData = json_decode($perda->lampiran, true);
                if (is_array($lampiranData)) {
                    foreach ($lampiranData as $lampiran) {
                        if (isset($lampiran['path']) && Storage::disk('public')->exists($lampiran['path'])) {
                            Storage::disk('public')->delete($lampiran['path']);
                        }
                    }
                }
            }
                
            // Delete the Perda record
            $perda->delete();

            return redirect()->route('draft-phd.perda.index')
                ->with('success', 'Surat Peraturan Daerah berhasil dihapus');
                
        } catch (\Exception $e) {
            return redirect()->route('draft-phd.perda.index')
                ->with('error', 'Gagal menghapus Perda: ' . $e->getMessage());
        }
    }

    public function export()
    {
        return Excel::download(new PerdaExport(), 'perda.xlsx');
    }

    public function disposisi(Request $request, $id)
    {
        try {
            // Validasi input
            $request->validate([
                'disposisi' => 'required',
                'tanggal_disposisi' => 'required|date',
                'catatan' => 'nullable',
                'persetujuan_ketua' => 'nullable|in:Sudah,Belum,sudah,belum'
            ]);

            // Ambil data perda
            $perda = Perda::findOrFail($id);

            // Tentukan status persetujuan (prioritas dari form, jika tidak ada ambil dari existing)
            $statusPersetujuan = 'Belum';
            if ($request->has('persetujuan_ketua')) {
                // Normalisasi input (uppercase first letter)
                $inputValue = ucfirst(strtolower($request->persetujuan_ketua));
                if (in_array($inputValue, ['Sudah', 'Belum'])) {
                    $statusPersetujuan = $inputValue;
                }
            } elseif ($perda->disposisi) {
                // Ambil dari disposisi yang sudah ada - format baru: "Sudah di Setujui Ketua Biro Hukum" atau "Belum di Setujui Ketua Biro Hukum"
                if (preg_match('/(Sudah|Belum)\s+di\s+Setujui\s+Ketua\s+Biro\s+Hukum/i', $perda->disposisi, $matches)) {
                    $statusPersetujuan = ucfirst(strtolower($matches[1]));
                }
                // Fallback untuk format lama jika masih ada
                elseif (preg_match('/Persetujuan Ketua Biro Hukum:\s*(Sudah|Belum|sudah|belum)/i', $perda->disposisi, $matches)) {
                    $statusPersetujuan = ucfirst(strtolower($matches[1]));
                }
            }

            // Format disposisi: Status Persetujuan terlebih dahulu, kemudian informasi lainnya
            $disposisiParts = [];
            
            // 1. Status Persetujuan Ketua Biro Hukum (pertama)
            $disposisiParts[] = $statusPersetujuan . ' di Setujui Ketua Biro Hukum';
            
            // 2. Tujuan Disposisi
            $disposisiParts[] = $request->disposisi;
            
            // 3. Diteruskan ke (jika ada)
            if ($request->sub_disposisi) {
                $disposisiParts[] = 'Diteruskan ke: ' . $request->sub_disposisi;
            }
            
            // 4. Tanggal Disposisi
            $disposisiParts[] = 'Tanggal: ' . $request->tanggal_disposisi;
            
            // 5. Catatan (jika ada)
            if ($request->catatan) {
                $disposisiParts[] = 'Catatan: ' . $request->catatan;
            }

            // Gabungkan semua bagian dengan separator
            $disposisiText = implode(' | ', $disposisiParts);

            // Update kolom disposisi
            $perda->update([
                'disposisi' => $disposisiText
            ]);

            return redirect()->back()
                            ->with('success', 'Disposisi berhasil ditambahkan');

        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Gagal menambahkan disposisi: ' . $e->getMessage());
        }
    }

    public function updateDisposisi(Request $request, $id)
    {
        try {
            $request->validate([
                'disposisi' => 'required|string',
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
