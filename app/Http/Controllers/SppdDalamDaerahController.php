<?php

namespace App\Http\Controllers;

use App\Models\SppdDalamDaerah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'no_agenda' => 'required|string|max:255',
            'no_surat' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'tujuan' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'nama_petugas' => 'required|string',
            'lampiran' => 'nullable|mimes:pdf|max:2048',
        ]);

        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $path = $file->store('lampiran/sppd-dalam-daerah', 'public');
            $validated['lampiran'] = $path;
        }

        SppdDalamDaerah::create($validated);

        return redirect()->route('sppd-dalam-daerah.index')
            ->with('success', 'SPPD Dalam Daerah berhasil ditambahkan');
    }

    public function edit($id)
    {
        $sppdDalamDaerah = SppdDalamDaerah::findOrFail($id);
        return view('sppd-dalam-daerah.edit', compact('sppdDalamDaerah'));
    }

    public function update(Request $request, $id)
    {
        try {
            $sppdDalamDaerah = SppdDalamDaerah::findOrFail($id);
            $validated = $request->validate([
                'no_agenda' => 'required|string|max:255',
                'no_surat' => 'required|string|max:255',
                'tanggal' => 'required|date',
                'tujuan' => 'required|string|max:255',
                'perihal' => 'required|string|max:255',
                'nama_petugas' => 'required|string',
                'lampiran' => 'nullable|mimes:pdf|max:2048',
            ]);

            if ($request->hasFile('lampiran')) {
                $file = $request->file('lampiran');
                $path = $file->store('lampiran/sppd-dalam-daerah', 'public');
                $validated['lampiran'] = $path;
            }

            $sppdDalamDaerah->update($validated);

            return redirect()->route('sppd-dalam-daerah.index')
                ->with('success', 'SPPD Dalam Daerah berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui data!');
        }
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