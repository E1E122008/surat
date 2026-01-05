<?php

namespace App\Http\Controllers;

use App\Models\Pergub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\PergubExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PergubController extends Controller
{
    // API akses disposisi pergub (khusus modal)
    public function apiShow($id)
    {
        $pergub = Pergub::findOrFail($id);
        return response()->json([
            'id' => $pergub->id,
            'disposisi' => $pergub->disposisi,
            'status' => $pergub->status
        ]);
    }

    public function index(Request $request)
    {
        $query = Pergub::query();
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_agenda', 'LIKE', "%{$search}%")
                  ->orWhere('no_surat', 'LIKE', "%{$search}%")
                  ->orWhere('pengirim', 'LIKE', "%{$search}%")
                  ->orWhere('perihal', 'LIKE', "%{$search}%");
            });
        }
        
        $pergubs = $query->latest()->paginate(10);
        return view('draft-phd.pergub.index', compact('pergubs'));
    }

    public function create()
    {
        return view('draft-phd.pergub.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'no_agenda' => 'nullable|string|max:255',
                'no_surat' => 'required|string|max:255|unique:pergub,no_surat',
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
                    // Sanitize nama file untuk menghindari karakter khusus yang bermasalah
                    $originalName = $file->getClientOriginalName();
                    $sanitizedName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
                    $fileName = time() . '_' . $sanitizedName;
                    $path = $file->storeAs('lampiran/pergub', $fileName, 'public');
                    $lampiranPaths[] = [
                        'path' => $path,
                        'name' => $originalName, // Simpan nama asli untuk display
                    ];
                }
                $validated['lampiran'] = json_encode($lampiranPaths);
            }

            // Set status default to 'tercatat' unless provided
            $validated['status'] = $request->input('status', 'tercatat');

            $pergub = Pergub::create($validated);

            if (!$pergub) {
                throw new \Exception('Gagal menyimpan data pergub');
            }

            return redirect()->route('draft-phd.pergub.index')
                ->with('success', 'Pergub berhasil ditambahkan');
        } catch (\Exception $e) {
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            Log::error('Terjadi kesalahan saat menambahkan data pergub: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan data pergub!')
                ->withInput();
        }
    }

    public function detail($id)
    {
        $pergub = Pergub::findOrFail($id);
        return view('draft-phd.pergub.detail', compact('pergub'));
    }

    public function edit($id)
    {       
        $pergub = Pergub::findOrFail($id);
        $lampiranLama = $pergub->lampiran ? array_values(json_decode($pergub->lampiran, true)) : [];
        return view('draft-phd.pergub.edit', compact('pergub', 'lampiranLama'));
    }   

    public function update(Request $request, $id)
    {
        try {
            $pergub = Pergub::findOrFail($id);
            $validated = $request->validate([
                'no_agenda' => 'nullable|string|max:255',
                'no_surat' => 'required|string|max:255',
                'pengirim' => 'required|string|max:255',
                'tanggal_surat' => 'required|date',
                'tanggal_terima' => 'required|date',
                'perihal' => 'required|string|max:255',
                'lampiran' => 'nullable|array',
                'lampiran.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2147483648',
                'catatan' => 'nullable|string',
                'disposisi' => 'nullable|string',
                'admin_notes' => 'nullable|string',
                'status' => 'nullable|string|max:255',
            ]);

            // Ambil lampiran lama yang dipertahankan
            $lampiranLama = $request->input('lampiran_lama', []);
            $lampiranDihapus = $request->input('lampiran_dihapus', []);
            $lampiranData = [];
            $lampiranSebelumnya = json_decode($pergub->lampiran, true) ?? [];

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
                    // Sanitize nama file untuk menghindari karakter khusus yang bermasalah
                    $originalName = $file->getClientOriginalName();
                    $sanitizedName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
                    $fileName = time() . '_' . $sanitizedName;
                    $path = $file->storeAs('lampiran/pergub', $fileName, 'public');
                    $lampiranData[] = [
                        'path' => $path,
                        'name' => $originalName, // Simpan nama asli untuk display
                    ];
                }
            }

            $validated['lampiran'] = json_encode($lampiranData);

            $pergub->update($validated);

            return redirect()->route('draft-phd.pergub.index')
                ->with('success', 'Pergub berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat memperbarui data pergub! ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui data pergub!');
        }
    }

    public function updateCatatan(Request $request, $id)
    {
        try {
            $pergub = Pergub::findOrFail($id);
            $pergub->update([
                'catatan' => $request->catatan
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Catatan berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating catatan pergub: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui catatan pergub'
            ], 500);
        }
    }

    public function status($id)
    {
        $pergub = Pergub::findOrFail($id);
        return view('draft-phd.pergub.status', compact('pergub'));
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:tercatat,terdisposisi,diproses,koreksi,diambil,selesai',
            ]);

            $pergub = Pergub::findOrFail($id);
            $pergub->status = $request->status;
            $pergub->save();

            return redirect()->back()->with('success', 'Status berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengupdate status: ' . $e->getMessage());
        }
    }

    public function destroy(Pergub $pergub)
    {
        try {
            // Delete file if exists
            if ($pergub->lampiran) {
                $lampiranData = json_decode($pergub->lampiran, true);
                if (is_array($lampiranData)) {
                    foreach ($lampiranData as $lampiran) {
                        if (isset($lampiran['path']) && Storage::disk('public')->exists($lampiran['path'])) {
                            Storage::disk('public')->delete($lampiran['path']);
                        }
                    }
                }
            }
                
            // Delete the Pergub record
            $pergub->delete();

            return redirect()->route('draft-phd.pergub.index')
                ->with('success', 'Pergub berhasil dihapus');
                
        } catch (\Exception $e) {
            return redirect()->route('draft-phd.pergub.index')
                ->with('error', 'Gagal menghapus Pergub: ' . $e->getMessage());
        }
    }

    public function export()
    {
        return Excel::download(new PergubExport(), 'pergub.xlsx');
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

            // Ambil data pergub
            $pergub = Pergub::findOrFail($id);

            // Tentukan status persetujuan (prioritas dari form, jika tidak ada ambil dari existing)
            $statusPersetujuan = 'Belum';
            if ($request->has('persetujuan_ketua')) {
                // Normalisasi input (uppercase first letter)
                $inputValue = ucfirst(strtolower($request->persetujuan_ketua));
                if (in_array($inputValue, ['Sudah', 'Belum'])) {
                    $statusPersetujuan = $inputValue;
                }
            } elseif ($pergub->disposisi) {
                // Ambil dari disposisi yang sudah ada - format baru: "Sudah di Setujui Ketua Biro Hukum" atau "Belum di Setujui Ketua Biro Hukum"
                if (preg_match('/(Sudah|Belum)\s+di\s+Setujui\s+Ketua\s+Biro\s+Hukum/i', $pergub->disposisi, $matches)) {
                    $statusPersetujuan = ucfirst(strtolower($matches[1]));
                }
                // Fallback untuk format lama jika masih ada
                elseif (preg_match('/Persetujuan Ketua Biro Hukum:\s*(Sudah|Belum|sudah|belum)/i', $pergub->disposisi, $matches)) {
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
            $pergub->update([
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

            $pergub = Pergub::findOrFail($id);
            $pergub->disposisi = $request->disposisi;
            $pergub->save();

            return response()->json([
                'success' => true,
                'message' => 'Disposisi berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating disposisi pergub: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui disposisi pergub'
            ], 500);
        }
    }
}