<?php

namespace App\Http\Controllers;

use App\Models\SK; // Pastikan model SK sudah ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\SKExport; // Pastikan Anda memiliki export untuk SK
use Maatwebsite\Excel\Facades\Excel;

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
            $validated = $request->validate([
                'no_agenda' => 'required|string|max:255',
                'no_surat' => 'required|string|max:255|unique:sks,no_surat',
                'pengirim' => 'required|string|max:255',
                'tanggal_surat' => 'required|date',
                'tanggal_terima' => 'required|date',
                'perihal' => 'required|string|max:255',
                'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            ]);

            if ($request->hasFile('lampiran')) {
                $file = $request->file('lampiran');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('lampiran/sk', $fileName, 'public');
                $validated['lampiran'] = $path;
            }

            // Set status default to 'tercatat'
            $validated['status'] = 'tercatat';

            $sk = SK::create($validated);

            if (!$sk) {
                throw new \Exception('Gagal menyimpan SK');
            }


            return redirect()->route('draft-phd.sk.index')
                ->with('success', 'SK berhasil ditambahkan');
        } catch (\Exception $e) {
            // Jika terjadi error saat upload file, hapus file yang sudah terupload
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            return redirect()->route('draft-phd.sk.index')
                ->with('error', 'Gagal menyimpan SK');
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

            return redirect()->back()->with('success', 'Status berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengupdate status: ' . $e->getMessage());
        }
    }

    public function edit(SK $sk)
    {
        return view('draft-phd.sk.edit', compact('sk'));
    }

    public function update(Request $request, SK $sk)
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
                if ($sk->lampiran) {
                    Storage::disk('public')->delete($sk->lampiran);
                }
                
                $file = $request->file('lampiran');
                $path = $file->store('lampiran/sk', 'public');
                $validated['lampiran'] = $path;
            }

            $sk->update($validated);

            return redirect()->route('draft-phd.sk.index')
                ->with('success', 'SK berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->route('draft-phd.sk.edit', $sk->id)
                ->with('error', 'Gagal memperbarui SK: ' . $e->getMessage());
        }
    }

    public function destroy(SK $sk)
    {

        // Delete file if exists
        if ($sk->lampiran) {
            Storage::disk('public')->delete($sk->lampiran);
        }
            
            // Delete the SK record
        $sk->delete();

            return redirect()->route('draft-phd.sk.index')
                ->with('success', 'SK berhasil dihapus');
        
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
            $request->validate([
                'disposisi' => 'required',
                'sub_disposisi' => 'required_unless:disposisi,Kasubag Tata Usaha',
                'tanggal_disposisi' => 'required|date',
                'catatan' => 'nullable'
            ]);

            $sk = SK::findOrFail($id);

            $disposisiText = $request->disposisi;
            if ($request->sub_disposisi) {
                $disposisiText .= ' | Diteruskan ke: ' . $request->sub_disposisi;
            }
            $disposisiText .= ' | Tanggal: ' . $request->tanggal_disposisi;
            if ($request->catatan) {
                $disposisiText .= ' | Catatan: ' . $request->catatan;
            }

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