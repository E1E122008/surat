<?php

namespace App\Http\Controllers;

use App\Models\SK; // Pastikan model SK sudah ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\SKExport; // Pastikan Anda memiliki export untuk SK
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SKController extends Controller
{
    public function index(Request $request)
    {
        $query = SK::query();  // Mulai dengan query builder

        // Tambahkan kondisi pencarian jika ada
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_agenda', 'LIKE', "%{$search}%")
                  ->orWhere('no_surat', 'LIKE', "%{$search}%")
                  ->orWhere('pengirim', 'LIKE', "%{$search}%");
            });
        }

        // Ambil data dengan pagination
        $sks = $query->latest()->paginate(10);

        return view('draft-phd.sk.index', compact('sks'));
    }
    public function detail($id)
    {
        $sk = SK::find($id);
        return view('draft-phd.sk.detail', compact('sk'));
    }

    public function create()
    {
        return view('draft-phd.sk.create'); // Pastikan tampilan create ada
    }

    public function store(Request $request)
    { 
        try {
            // Validasi tanpa lampiran terlebih dahulu
            $validated = $request->validate([
                'no_agenda' => 'nullable|string|max:255',
                'no_surat' => 'required|string|max:255|unique:sks,no_surat',
                'pengirim' => 'required|string|max:255',
                'tanggal_surat' => 'required|date',
                'tanggal_terima' => 'required|date',
                'perihal' => 'required|string|max:255',
                'catatan' => 'nullable|string',
                'disposisi' => 'nullable|string',
                'admin_notes' => 'nullable|string',
            ]);

            // Validasi lampiran hanya jika ada file yang diupload
            // Form create menggunakan array (lampiran[]), jadi validasi sebagai array
            if ($request->hasFile('lampiran')) {
                $files = $request->file('lampiran');
                // Pastikan files adalah array
                if (!is_array($files)) {
                    $files = [$files];
                }
                // Validasi sebagai array hanya jika ada file
                if (count($files) > 0) {
                    $request->validate([
                        'lampiran' => 'array',
                        'lampiran.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2097152',
                    ]);
                }
            }

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
                    $path = $file->storeAs('lampiran/sk', $fileName, 'public');
                    $lampiranPaths[] = [
                        'path' => $path,
                        'name' => $originalName, // Simpan nama asli untuk display
                    ];
                }
                $validated['lampiran'] = json_encode($lampiranPaths);
            }

            // Set status default to 'tercatat' unless provided
            $validated['status'] = $request->input('status', 'tercatat');

            $sk = SK::create($validated);

            if (!$sk) {
                throw new \Exception('Gagal menyimpan SK');
            }

            return redirect()->route('draft-phd.sk.index')
                ->with('success', ' SK berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            // Jika terjadi error saat upload file, hapus file yang sudah terupload
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            Log::error('Terjadi kesalahan saat menyimpan SK: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', ' Gagal menyimpan SK: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function status($id)
    {
        $sk = SK::findOrFail($id);
        return view('draft-phd.sk.status', compact('sk'));
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:tercatat,terdisposisi,diproses,koreksi,diambil,selesai'
            ]);

            $sk = SK::findOrFail($id);
            $sk->status = $request->status;
            $sk->save();

            return redirect()->back()->with('success', ' Status berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengupdate status: ' . $e->getMessage());
        }
    }

    public function edit(SK $sk)
    {
        $lampiranLama = $sk->lampiran ? array_values(json_decode($sk->lampiran, true)) : [];
        return view('draft-phd.sk.edit', compact('sk', 'lampiranLama'));
    }

    public function update(Request $request, SK $sk)
    {
        try {
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
            $lampiranSebelumnya = json_decode($sk->lampiran, true) ?? [];

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
                    $path = $file->storeAs('lampiran/sk', $fileName, 'public');
                    $lampiranData[] = [
                        'path' => $path,
                        'name' => $originalName, // Simpan nama asli untuk display
                    ];
                }
            }

            $validated['lampiran'] = json_encode($lampiranData);

            $sk->update($validated);

            return redirect()->route('draft-phd.sk.index')
                ->with('success', ' SK berhasil diperbarui!');
                
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat memperbarui SK: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', ' Gagal memperbarui SK: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(SK $sk)
    {
        try {
            // Delete file if exists
            if ($sk->lampiran) {
                $lampiranData = json_decode($sk->lampiran, true);
                if (is_array($lampiranData)) {
                    foreach ($lampiranData as $lampiran) {
                        if (Storage::disk('public')->exists($lampiran['path'])) {
                            Storage::disk('public')->delete($lampiran['path']);
                        }
                    }
                }
            }
                
            // Delete the SK record
            $sk->delete();

            return redirect()->route('draft-phd.sk.index')
                ->with('success', ' SK berhasil dihapus!');
                
        } catch (\Exception $e) {
            return redirect()->route('draft-phd.sk.index')
                ->with('error', ' Gagal menghapus SK: ' . $e->getMessage());
        }
    }

    public function export() 
    {
        return Excel::download(new SKExport, 'sk.xlsx'); // Pastikan Anda memiliki export untuk SK
    }

    public function updateCatatan(Request $request, $id)
    {
        try {
            // Log the incoming request data
            \Log::info('Updating catatan:', $request->all());

            $sk = SK::findOrFail($id); // Ensure you're using the SK model
            $sk->update([
                'catatan' => $request->catatan
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Catatan berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating catatan:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui catatan'
            ], 500);
        }
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

            // Ambil data SK
            $sk = SK::findOrFail($id);

            // Tentukan status persetujuan (prioritas dari form, jika tidak ada ambil dari existing)
            $statusPersetujuan = 'Belum';
            if ($request->has('persetujuan_ketua')) {
                $inputValue = ucfirst(strtolower($request->persetujuan_ketua));
                if (in_array($inputValue, ['Sudah', 'Belum'])) {
                    $statusPersetujuan = $inputValue;
                }
            } elseif ($sk->disposisi) {
                // Cek format baru
                if (preg_match('/(Sudah|Belum)\s+di\s+Setujui\s+Ketua\s+Biro\s+Hukum/i', $sk->disposisi, $matches)) {
                    $statusPersetujuan = ucfirst(strtolower($matches[1]));
                }
                // Fallback format lama
                elseif (preg_match('/Persetujuan Ketua Biro Hukum:\s*(Sudah|Belum|sudah|belum)/i', $sk->disposisi, $matches)) {
                    $statusPersetujuan = ucfirst(strtolower($matches[1]));
                }
            }

            // Susun format disposisi: Status persetujuan dahulu, lalu detail lain
            $disposisiParts = [];

            // 1. Status Persetujuan Ketua Biro Hukum
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

            $disposisiText = implode(' | ', $disposisiParts);

            // Update kolom disposisi
            $sk->update([
                'disposisi' => $disposisiText
            ]);

            return redirect()->back()
                            ->with('success', 'Disposisi berhasil ditambahkan');

        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Gagal menambahkan disposisi: ' . $e->getMessage());
        }
    }

} 