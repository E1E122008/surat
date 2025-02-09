<?php

namespace App\Http\Controllers;

use App\Models\SppdLuarDaerah;
use Illuminate\Http\Request;
use App\Exports\SppdLuarDaerahExport;
use Maatwebsite\Excel\Facades\Excel;

class SppdLuarDaerahController extends Controller
{
    public function index()
    {
        $sppd = SppdLuarDaerah::latest()->paginate(10);
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
            'no_surat' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'perihal' => 'required|string|max:255',
            'nama_petugas' => 'required|string',
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
        $validated = $request->validate([
            'no_surat' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'perihal' => 'required|string|max:255',
            'nama_petugas' => 'required|string',
        ]);

        $sppdLuarDaerah->update($validated);

        return redirect()->route('sppd-luar-daerah.index')
            ->with('success', 'SPPD Luar Daerah berhasil diperbarui');
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