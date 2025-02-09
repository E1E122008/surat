<?php

namespace App\Http\Controllers;

use App\Models\SptDalamDaerah;
use Illuminate\Http\Request;
use App\Exports\SptDalamDaerahExport;
use Maatwebsite\Excel\Facades\Excel;

class SptDalamDaerahController extends Controller
{
    public function index()
    {
        $spt = SptDalamDaerah::latest()->paginate(10);
        return view('spt-dalam-daerah.index', compact('spt'));
    }

    public function create()
    {
        $nomorSurat = SptDalamDaerah::generateNomorSurat();
        return view('spt-dalam-daerah.create', compact('nomorSurat'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_surat' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'perihal' => 'required|string|max:255',
            'nama_petugas' => 'required|string',
        ]);

        SptDalamDaerah::create($validated);

        return redirect()->route('spt-dalam-daerah.index')
            ->with('success', 'SPT Dalam Daerah berhasil ditambahkan');
    }

    public function edit(SptDalamDaerah $sptDalamDaerah)
    {
        return view('spt-dalam-daerah.edit', compact('sptDalamDaerah'));
    }

    public function update(Request $request, SptDalamDaerah $sptDalamDaerah)
    {
        $validated = $request->validate([
            'no_surat' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'perihal' => 'required|string|max:255',
            'nama_petugas' => 'required|string',
        ]);

        $sptDalamDaerah->update($validated);

        return redirect()->route('spt-dalam-daerah.index')
            ->with('success', 'SPT Dalam Daerah berhasil diperbarui');
    }

    public function destroy(SptDalamDaerah $sptDalamDaerah)
    {
        $sptDalamDaerah->delete();

        return redirect()->route('spt-dalam-daerah.index')
            ->with('success', 'SPT Dalam Daerah berhasil dihapus');
    }

    public function print(SptDalamDaerah $sptDalamDaerah)
    {
        return view('spt-dalam-daerah.print', compact('sptDalamDaerah'));
    }

    public function export()
    {
        return Excel::download(new SptDalamDaerahExport, 'spt-dalam-daerah.xlsx');
    }
} 