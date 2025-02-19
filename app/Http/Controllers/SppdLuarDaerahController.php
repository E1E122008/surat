<?php

namespace App\Http\Controllers;

use App\Models\SppdLuarDaerah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\SppdLuarDaerahExport;
use Maatwebsite\Excel\Facades\Excel;

class SppdLuarDaerahController extends Controller
{
    public function index(Request $request)
    {
        $query = SppdLuarDaerah::query();
        
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
        
        $sppd = $query->latest()->paginate(10);
        return view('sppd-luar-daerah.index', compact('sppd'));
    }

    public function create()
    {
        $nomorSurat = SppdLuarDaerah::generateNomorSurat();
        return view('sppd-luar-daerah.create', compact('nomorSurat'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_agenda' => 'required|string|max:255',
            'no_surat' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'tujuan' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'nama_petugas' => 'required|string',
            'lampiran' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:2048',
        ]);

        SppdLuarDaerah::create($validated);

        return redirect()->route('sppd-luar-daerah.index')
            ->with('success', 'SPPD Luar Daerah berhasil ditambahkan');
    }

    public function edit(SppdLuarDaerah $sppdLuarDaerah)
    {
        return view('sppd-luar-daerah.edit', compact('sppdLuarDaerah'));
    }

    public function update(Request $request, SppdLuarDaerah $sppdLuarDaerah)
    {
        try {
            $validated = $request->validate([
                'no_agenda' => 'required|string|max:255',
                'no_surat' => 'required|string|max:255',
                'tanggal' => 'required|date',
                'tujuan' => 'required|string|max:255',
                'perihal' => 'required|string|max:255',
                'nama_petugas' => 'required|string',
                'lampiran' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:2048',
            ]);

            if ($request->hasFile('lampiran')) {
                $file = $request->file('lampiran');
                $path = $file->store('lampiran/sppd-luar-daerah', 'public');
                $validated['lampiran'] = $path;
            }

            $sppdLuarDaerah->update($validated);

            return redirect()->route('sppd-luar-daerah.index')
                ->with('success', 'SPPD Luar Daerah berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui data!');    
        }
    }

    public function destroy(SppdLuarDaerah $sppdLuarDaerah)
    {
        $sppdLuarDaerah->delete();

        return redirect()->route('sppd-luar-daerah.index')
            ->with('success', 'SPPD Luar Daerah berhasil dihapus');
    }

    public function print(SppdLuarDaerah $sppdLuarDaerah)
    {
        return view('sppd-luar-daerah.print', compact('sppdLuarDaerah'));
    }

    public function export()
    {
        return Excel::download(new SppdLuarDaerahExport, 'sppd-luar-daerah.xlsx');
    }
} 