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

class SuratMasukController extends Controller
{
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

        $suratMasuk = $query->paginate(10);
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
                'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|max:10240',
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

            if ($request->hasFile('lampiran')) {
                $file = $request->file('lampiran');
                $path = $file->store('lampiran/surat-masuk', 'public');
                $validated['lampiran'] = $path;
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
        $surat = SuratMasuk::find($id); // Ambil data surat berdasarkan ID
        $surat = SuratMasuk::findOrFail($id);
        return view('surat-masuk.detail', compact('surat'));
    }
    

    public function edit(SuratMasuk $suratMasuk)
    {
        return view('surat-masuk.edit', compact('suratMasuk'));
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
                'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|max:10240',
                'disposisi' => 'nullable|string|max:255',
                'status' => 'nullable|string|max:255',
                'submitted_by' => 'nullable|exists:users,id',
                'catatan' => 'nullable|string',
                'admin_notes' => 'nullable|string',
            ]);

            if ($request->hasFile('lampiran')) {
                // Hapus file lama jika ada
                if ($suratMasuk->lampiran) {
                    Storage::disk('public')->delete($suratMasuk->lampiran);
                }
                
                $file = $request->file('lampiran');
                $path = $file->store('lampiran/surat-masuk', 'public');
                $validated['lampiran'] = $path;
            }

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
        if ($suratMasuk->lampiran) {
            Storage::disk('public')->delete($suratMasuk->lampiran);
        }


        $suratMasuk->delete();

        return redirect()->route('surat-masuk.index')
                ->with('success', 'Data surat masuk berhasil dihapus!');
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
        $surat = SuratMasuk::find($id); // Ambil data surat berdasarkan ID
        if (!$surat) {
            return redirect()->route('surat.index')->with('error', 'Surat tidak ditemukan.');
        }
        return view('surat-masuk.detail', compact('surat')); // Kirim variabel ke view
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
                'catatan' => 'nullable'
            ]);

            // Ambil data surat masuk
            $suratMasuk = SuratMasuk::findOrFail($id);

            // Gabungkan data disposisi dalam satu string
            $disposisiText = $request->disposisi;
            if ($request->sub_disposisi) {
                $disposisiText .= ' | Diteruskan ke: ' . $request->sub_disposisi;
            }
            $disposisiText .= ' | Tanggal: ' . $request->tanggal_disposisi;
            if ($request->catatan) {
                $disposisiText .= ' | Catatan: ' . $request->catatan;
            }

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