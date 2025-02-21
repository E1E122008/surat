<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\SuratMasukExport;
use Maatwebsite\Excel\Facades\Excel;

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
        $validated = $request->validate([
            'no_agenda' => 'required|string|max:255',
            'no_surat' => 'required|string|max:255',
            'pengirim' => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'tanggal_terima' => 'required|date',
            'perihal' => 'required|string|max:255',
            'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|max:2048',
        ]);

        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $path = $file->store('lampiran/surat-masuk', 'public');
            $validated['lampiran'] = $path;
        }

        SuratMasuk::create($validated);

        return redirect()->route('surat-masuk.index')
            ->with('success', 'Surat masuk berhasil ditambahkan');
    }

    public function detail($id)
    {
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
                'no_agenda' => 'required|string|max:255',
                'no_surat' => 'required|string|max:255',
                'pengirim' => 'required|string|max:255',
                'tanggal_surat' => 'required|date',
                'tanggal_terima' => 'required|date',
                'perihal' => 'required|string|max:255',
                'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|max:2048',
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
                ->with('error', 'Terjadi kesalahan saat memperbarui data!');
        }
    }

    public function destroy($id)
    {
        try {
            $suratMasuk = SuratMasuk::findOrFail($id);
            if ($suratMasuk->lampiran) {
                Storage::disk('public')->delete($suratMasuk->lampiran);
            }
            
            $suratMasuk->delete();

            return redirect()->route('surat-masuk.index')
                ->with('success', 'Data surat masuk berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data!');
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
            \Log::info('Updating catatan:', $request->all());

            $suratMasuk = SuratMasuk::findOrFail($id);
            $suratMasuk->update([
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

    public function show($id)
    {
        $surat = SuratMasuk::find($id); // Ambil data surat berdasarkan ID
        if (!$surat) {
            return redirect()->route('surat.index')->with('error', 'Surat tidak ditemukan.');
        }
        return view('surat-masuk.detail', compact('surat')); // Kirim variabel ke view
    }
}

