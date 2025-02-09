<?php

namespace App\Http\Controllers;

use App\Models\SppdDalamDaerah;
use Illuminate\Http\Request;

class SppdDalamDaerahController extends Controller
{
    public function index()
    {
        $sppd = SppdDalamDaerah::latest()->paginate(10);
        return view('sppd-dalam-daerah.index', compact('sppd'));
    }

    public function create()
    {
        $nomorSurat = SppdDalamDaerah::generateNomorSurat();
        return view('sppd-dalam-daerah.create', compact('nomorSurat'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_surat' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'perihal' => 'required|string|max:255',
            'nama_petugas' => 'required|string',
        ]);

        SppdDalamDaerah::create($validated);

        return redirect()->route('sppd-dalam-daerah.index')
            ->with('success', 'SPPD Dalam Daerah berhasil ditambahkan');
    }

    public function edit(SppdDalamDaerah $sppdDalamDaerah)
    {
        return view('sppd-dalam-daerah.edit', compact('sppdDalamDaerah'));
    }

    public function update(Request $request, SppdDalamDaerah $sppdDalamDaerah)
    {
        $validated = $request->validate([
            'no_surat' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'perihal' => 'required|string|max:255',
            'nama_petugas' => 'required|string',
        ]);

        $sppdDalamDaerah->update($validated);

        return redirect()->route('sppd-dalam-daerah.index')
            ->with('success', 'SPPD Dalam Daerah berhasil diperbarui');
    }

    public function destroy(SppdDalamDaerah $sppdDalamDaerah)
    {
        $sppdDalamDaerah->delete();

        return redirect()->route('sppd-dalam-daerah.index')
            ->with('success', 'SPPD Dalam Daerah berhasil dihapus');
    }

    public function print(SppdDalamDaerah $sppdDalamDaerah)
    {
        return view('sppd-dalam-daerah.print', compact('sppdDalamDaerah'));
    }

    public function export()
    {
        return Excel::download(new SppdDalamDaerahExport, 'sppd-dalam-daerah.xlsx');
    }
} 