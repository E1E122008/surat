<?php

namespace App\Http\Controllers;

use App\Models\SptLuarDaerah;
use Illuminate\Http\Request;
use App\Exports\SptLuarDaerahExport;
use Maatwebsite\Excel\Facades\Excel;

class SptLuarDaerahController extends Controller
{
    public function index()
    {
        $spt = SptLuarDaerah::latest()->paginate(10);
        return view('spt-luar-daerah.index', compact('spt'));
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
            'perihal' => 'required|string|max:255',
            'nama_petugas' => 'required|string',
        ]);

        SptLuarDaerah::create($validated);

        return redirect()->route('spt-luar-daerah.index')
            ->with('success', 'SPT Luar Daerah berhasil ditambahkan');
    }

    public function edit(SptLuarDaerah $sptLuarDaerah)
    {
        return view('spt-luar-daerah.edit', compact('sptLuarDaerah'));
    }

    public function update(Request $request, SptLuarDaerah $sptLuarDaerah)
    {
        $validated = $request->validate([
            'no_surat' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'perihal' => 'required|string|max:255',
            'nama_petugas' => 'required|string',
        ]);

        $sptLuarDaerah->update($validated);

        return redirect()->route('spt-luar-daerah.index')
            ->with('success', 'SPT Luar Daerah berhasil diperbarui');
    }

    public function destroy(SptLuarDaerah $sptLuarDaerah)
    {
        $sptLuarDaerah->delete();

        return redirect()->route('spt-luar-daerah.index')
            ->with('success', 'SPT Luar Daerah berhasil dihapus');
    }

    public function print(SptLuarDaerah $sptLuarDaerah)
    {
        return view('spt-luar-daerah.print', compact('sptLuarDaerah'));
    }

    public function export()
    {
        return Excel::download(new SptLuarDaerahExport, 'spt-luar-daerah.xlsx');
    }
} 