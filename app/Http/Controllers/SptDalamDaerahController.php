<?php

namespace App\Http\Controllers;

use App\Models\SptDalamDaerah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\SptDalamDaerahExport;
use Maatwebsite\Excel\Facades\Excel;

class SptDalamDaerahController extends Controller
{
    public function index(Request $request)
    {
        $query = SptDalamDaerah::latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_agenda', 'LIKE', "%{$search}%")
                  ->orWhere('no_surat', 'LIKE', "%{$search}%")
                  ->orWhere('tujuan', 'LIKE', "%{$search}%")
                  ->orWhere('perihal', 'LIKE', "%{$search}%")
                  ->orWhere('nama_petugas', 'LIKE', "%{$search}%");
            });
        }

        $spt = $query->paginate(10);
        return view('spt-dalam-daerah.index', compact('spt'));
    }

    public function create()
    {
        $nomorSurat = SptDalamDaerah::generateNomorSurat();
        return view('spt-dalam-daerah.create', compact('nomorSurat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lampiran' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048', // Sesuaikan dengan tipe file yang diizinkan
        ]);

        try {
            $validated = $request->validate([
                'no_agenda' => 'required|string|max:255',
                'no_surat' => 'required|string|max:255',
                'tanggal' => 'required|date',
                'tujuan' => 'required|string|max:255',
                'perihal' => 'required|string|max:255',
                'nama_petugas' => 'required|string',
                'lampiran' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|max:2048',
            ]);

            if ($request->hasFile('lampiran')) {
                $file = $request->file('lampiran');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('lampiran/spt-dalam-daerah', $fileName, 'public');
                $validated['lampiran'] = $path;
            }

            $sptDalamDaerah = SptDalamDaerah::create($validated);

            if (!$sptDalamDaerah) {
                throw new \Exception('Gagal menyimpan data spt dalam daerah');
            }

            return redirect()->route('spt-dalam-daerah.index')
                ->with('success', 'SPT Dalam Daerah berhasil ditambahkan');
        } catch (\Exception $e) {
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan SPT Dalam Daerah: ' . $e->getMessage())
                ->withInput();  
        }
    }

    public function edit($id)
    {
        $sptDalamDaerah = SptDalamDaerah::findOrFail($id);
        return view('spt-dalam-daerah.edit', compact('sptDalamDaerah'));
    }

    public function update(Request $request, $id)
    {
        try {
            $sptDalamDaerah = SptDalamDaerah::findOrFail($id);
            $validated = $request->validate([
                'no_agenda' => 'required|string|max:255',
                'no_surat' => 'required|string|max:255',
                'tanggal' => 'required|date',
                'tujuan' => 'required|string|max:255',
                'perihal' => 'required|string|max:255',
                'nama_petugas' => 'required|string',
                'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|max:2048',
            ]);

            if ($request->hasFile('lampiran')) {
                if ($sptDalamDaerah->lampiran) {
                    Storage::disk('public')->delete($sptDalamDaerah->lampiran);
                }
                $file = $request->file('lampiran');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('lampiran/spt-dalam-daerah', $fileName, 'public');
                $validated['lampiran'] = $path;
            }

            $sptDalamDaerah->update($validated);

            return redirect()->route('spt-dalam-daerah.index')
                ->with('success', 'SPT Dalam Daerah berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui SPT Dalam Daerah: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(SptDalamDaerah $sptDalamDaerah)
    {
        // Hapus file lampiran jika ada
        if ($sptDalamDaerah->lampiran) {
            Storage::disk('public')->delete($sptDalamDaerah->lampiran);
        }

        $sptDalamDaerah->delete();

        return redirect()->route('spt-dalam-daerah.index')
            ->with('success', 'SPT Dalam Daerah berhasil dihapus');
    }



    public function export()
    {
        return Excel::download(new SptDalamDaerahExport, 'spt-dalam-daerah.xlsx');
    }
} 