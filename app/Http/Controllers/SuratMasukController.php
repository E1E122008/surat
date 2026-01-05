<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\SuratMasukExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ApprovalRequestNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

class SuratMasukController extends Controller
{
    // API akses disposisi surat masuk (disederhanakan - hanya untuk radio button listener)
    public function apiShow($id)
    {
        $surat = SuratMasuk::findOrFail($id);
        
        return response()->json([
            'id' => $surat->id,
            'disposisi' => $surat->disposisi,
            'status' => $surat->status
        ]);
    }

    public function index(Request $request)
    {
        $query = SuratMasuk::latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_agenda', 'LIKE', "%{$search}%")
                  ->orWhere('no_surat', 'LIKE', "%{$search}%")
                  ->orWhere('pengirim', 'LIKE', "%{$search}%");
            });
        }

        $suratMasuk = $query->latest()->paginate(10);
        return view('surat-masuk.index', compact('suratMasuk'));
    }

    public function create()
    {
        return view('surat-masuk.create');
    }

    public function store(Request $request)
    { 
        try {
            $validated = $request->validate([
                'no_surat' => 'required|string|max:255',
                'pengirim' => 'required|string|max:255',
                'tanggal_surat' => 'required|date',
                'tanggal_terima' => 'required|date',
                'perihal' => 'required|string|max:255',
                'lampiran' => 'nullable|array',
                'lampiran.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2147483648',
                'catatan' => 'nullable|string',
                'no_agenda' => 'nullable|string|max:255',
                'disposisi' => 'nullable|string|max:255',
                'status' => 'nullable|string|max:255',
                'admin_notes' => 'nullable|string',
            ]);

            $validated['submitted_by'] = Auth::id();

            $validated['status'] = $request->input('status', 'tercatat');
            $adminFields = ['no_agenda', 'disposisi', 'admin_notes'];
            foreach ($adminFields as $field) {
                if ($request->has($field)) {
                    $validated[$field] = $request->input($field);
                }
            }

            $lampiranPaths = [];
            if ($request->hasFile('lampiran')) {
                $files = $request->file('lampiran');
                if (!is_array($files)) {
                    $files = [$files];
                }
                foreach ($files as $file) {
                    $lampiranPaths[] = [
                        'path' => $file->store('lampiran/surat-masuk', 'public'),
                        'name' => $file->getClientOriginalName(),
                    ];
                }
                $validated['lampiran'] = json_encode($lampiranPaths);
            }

            $suratMasuk = SuratMasuk::create($validated);

            $successMessage = 'Surat masuk berhasil ditambahkan.';

            return redirect()->route('surat-masuk.index')
                ->with('success', $successMessage);

        } catch (\Exception $e) {
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            Log::error('Terjadi kesalahan saat mengajukan surat masuk: ' . $e->getMessage());

            return redirect()->back()
                            ->with('error', 'Terjadi kesalahan saat mengajukan surat masuk: ' . $e->getMessage())
                            ->withInput();
        }
    }

    public function detail($id)
    {
        $surat = SuratMasuk::findOrFail($id);
        return view('surat-masuk.detail', compact('surat'));
    }
    

    public function edit(SuratMasuk $suratMasuk)
    {
        $lampiranLama = $suratMasuk->lampiran ? array_values(json_decode($suratMasuk->lampiran, true)) : [];
        return view('surat-masuk.edit', compact('suratMasuk', 'lampiranLama'));
    }

    public function update(Request $request, $id)
    {
        try {
            $suratMasuk = SuratMasuk::findOrFail($id);

            $validated = $request->validate([
                'no_agenda' => 'nullable|string|max:255',
                'no_surat' => 'required|string|max:255',
                'pengirim' => 'required|string|max:255',
                'tanggal_surat' => 'required|date',
                'tanggal_terima' => 'nullable|date',
                'perihal' => 'required|string|max:255',
                'lampiran' => 'nullable|array',
                'lampiran.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2147483648',
                'disposisi' => 'nullable|string|max:255',
                'status' => 'nullable|string|max:255',
                'submitted_by' => 'nullable|exists:users,id',
                'catatan' => 'nullable|string',
                'admin_notes' => 'nullable|string',
            ]);

            // Ambil lampiran lama yang dipertahankan
            $lampiranLama = $request->input('lampiran_lama', []);
            $lampiranDihapus = $request->input('lampiran_dihapus', []);
            $lampiranData = [];
            $lampiranSebelumnya = json_decode($suratMasuk->lampiran, true) ?? [];

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
                        'path' => $file->store('lampiran/surat-masuk', 'public'),
                        'name' => $file->getClientOriginalName(),
                    ];
                }
            }

            $validated['lampiran'] = json_encode($lampiranData);

            $suratMasuk->update($validated);

            return redirect()->route('surat-masuk.index')
                ->with('success', 'Data surat masuk berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy(SuratMasuk $suratMasuk)
    {
        try {
            // Delete file if exists
            if ($suratMasuk->lampiran) {
                $lampiranData = json_decode($suratMasuk->lampiran, true);
                if (is_array($lampiranData)) {
                    foreach ($lampiranData as $lampiran) {
                        if (isset($lampiran['path']) && Storage::disk('public')->exists($lampiran['path'])) {
                            Storage::disk('public')->delete($lampiran['path']);
                        }
                    }
                }
            }
                
            // Delete the SuratMasuk record
            $suratMasuk->delete();

            return redirect()->route('surat-masuk.index')
                ->with('success', 'Data surat masuk berhasil dihapus!');
                
        } catch (\Exception $e) {
            return redirect()->route('surat-masuk.index')
                ->with('error', 'Gagal menghapus surat masuk: ' . $e->getMessage());
        }
    }

    public function export() 
    {
        return Excel::download(new SuratMasukExport, 'surat-masuk.xlsx');
    }

    public function updateDisposisi(Request $request, $id)
    {
        $request->validate([
            'disposisi' => 'required|string|max:255',
        ]);

        $suratMasuk = SuratMasuk::findOrFail($id);
        $suratMasuk->disposisi = $request->disposisi;
        $suratMasuk->save();

        return response()->json(['success' => true]);
    }

    public function updateCatatan(Request $request, $id)
    {
        try {
            // Log the incoming request data
            Log::info('Updating catatan:', $request->all());

            $suratMasuk = SuratMasuk::findOrFail($id);
            $suratMasuk->update([
                'catatan' => $request->catatan
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Catatan berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating catatan:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui catatan'
            ], 500);
        }
    }

    public function show($id)
    {
        $surat = SuratMasuk::findOrFail($id);
        return view('surat-masuk.detail', compact('surat'));
    }

    public function status($id)
    {
        $surat = SuratMasuk::findOrFail($id);
        return view('surat-masuk.status', compact('surat'));
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:tercatat,terdisposisi,diproses,koreksi,diambil,selesai'
            ]);

            $surat = SuratMasuk::findOrFail($id);
            $surat->status = $request->status;
            $surat->save();

            return redirect()->back()->with('success', 'Status berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengupdate status: ' . $e->getMessage());
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

            // Ambil data surat masuk
            $suratMasuk = SuratMasuk::findOrFail($id);

            // Tentukan status persetujuan (prioritas dari form, jika tidak ada ambil dari existing)
            $statusPersetujuan = 'Belum';
            if ($request->has('persetujuan_ketua')) {
                // Normalisasi input (uppercase first letter)
                $inputValue = ucfirst(strtolower($request->persetujuan_ketua));
                if (in_array($inputValue, ['Sudah', 'Belum'])) {
                    $statusPersetujuan = $inputValue;
                }
            } elseif ($suratMasuk->disposisi) {
                // Ambil dari disposisi yang sudah ada - format baru: "Sudah di Setujui Ketua Biro Hukum" atau "Belum di Setujui Ketua Biro Hukum"
                if (preg_match('/(Sudah|Belum)\s+di\s+Setujui\s+Ketua\s+Biro\s+Hukum/i', $suratMasuk->disposisi, $matches)) {
                    $statusPersetujuan = ucfirst(strtolower($matches[1]));
                }
                // Fallback untuk format lama jika masih ada
                elseif (preg_match('/Persetujuan Ketua Biro Hukum:\s*(Sudah|Belum|sudah|belum)/i', $suratMasuk->disposisi, $matches)) {
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
            $suratMasuk->update([
                'disposisi' => $disposisiText
            ]);

            return redirect()->back()
                            ->with('success', 'Disposisi berhasil ditambahkan');

        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Gagal menambahkan disposisi: ' . $e->getMessage());
        }
    }

    public function review(Request $request, $id)
    {
        if (!Auth::user()->role === 'admin') {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        $suratMasuk = SuratMasuk::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_notes' => 'nullable|string',
            'no_agenda' => 'required_if:status,approved|string|max:255'
        ]);

        // Preserve existing data
        $updateData = [
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes']
        ];

        // Only update no_agenda if status is approved
        if ($validated['status'] === 'approved' && !empty($validated['no_agenda'])) {
            $updateData['no_agenda'] = $validated['no_agenda'];
        }

        $suratMasuk->update($updateData);

        $status = $validated['status'] === 'approved' ? 'disetujui' : 'ditolak';
        return redirect()->route('surat-masuk.index')
            ->with('success', "Surat masuk telah {$status}");
    }

}