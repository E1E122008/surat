<?php

namespace App\Http\Controllers;

use App\Models\SptLuarDaerah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\SptLuarDaerahExport;
use Maatwebsite\Excel\Facades\Excel;

class SptLuarDaerahController extends Controller
{
    public function index(Request $request)
    {
        $query = SptLuarDaerah::latest();
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_agenda', 'LIKE', "%{$search}%")
                  ->orWhere('no_surat', 'LIKE', "%{$search}%")
                  ->orWhere('tanggal', 'LIKE', "%{$search}%")
                  ->orWhere('tujuan', 'LIKE', "%{$search}%")
                  ->orWhere('perihal', 'LIKE', "%{$search}%")
                  ->orWhere('nama_petugas', 'LIKE', "%{$search}%");
            });
        }
        
        $sptLuarDaerah = $query->paginate(10);
        return view('spt-luar-daerah.index', compact('sptLuarDaerah'));
    }

    public function create()
    {
        $nomorSurat = SptLuarDaerah::generateNomorSurat();
        return view('spt-luar-daerah.create', compact('nomorSurat'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_surat' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'tujuan' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'nama_petugas' => 'required|string',
            'lampiran' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|max:2048',
        ]);

        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $path = $file->store('lampiran/spt-luar-daerah', 'public');
            $validated['lampiran'] = $path;
        }

        SptLuarDaerah::create($validated);


        return redirect()->route('spt-luar-daerah.index')
            ->with('success', 'SPT Luar Daerah berhasil ditambahkan');
    }

    public function detail($id)
    {
        $spt = SptLuarDaerah::findOrFail($id);
        return view('spt-luar-daerah.detail', compact('spt'));
    }

    public function edit($id)
    {
        $sptLuarDaerah = SptLuarDaerah::findOrFail($id);
        return view('spt-luar-daerah.edit', compact('sptLuarDaerah'));
    }

    public function update(Request $request, $id)
    {   
        try {
            $sptLuarDaerah = SptLuarDaerah::findOrFail($id);
            $validated = $request->validate([
                'no_surat' => 'required|string|max:255',
                'tanggal' => 'required|date',
                'tujuan' => 'required|string|max:255',
                'perihal' => 'required|string|max:255',
                'nama_petugas' => 'required|string',
                'lampiran' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|max:2048',
            ]);

            if ($request->hasFile('lampiran')) {
                if ($sptLuarDaerah->lampiran) {
                    Storage::disk('public')->delete($sptLuarDaerah->lampiran);
                }
                $file = $request->file('lampiran');
                $path = $file->store('lampiran/spt-luar-daerah', 'public');
                $validated['lampiran'] = $path;
            }

            $sptLuarDaerah->update($validated);

            return redirect()->route('spt-luar-daerah.index')
                ->with('success', 'SPT Luar Daerah berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui SPT Luar Daerah');
        }
    }

    public function destroy(SptLuarDaerah $sptLuarDaerah)
    {
        // Hapus file lampiran jika ada
        if ($sptLuarDaerah->lampiran) {
            Storage::disk('public')->delete($sptLuarDaerah->lampiran);
        }

        $sptLuarDaerah->delete();

        return redirect()->route('spt-luar-daerah.index')
            ->with('success', 'SPT Luar Daerah berhasil dihapus');
    }

    public function export()
    {
        return Excel::download(new SptLuarDaerahExport, 'spt-luar-daerah.xlsx');
    }
} 