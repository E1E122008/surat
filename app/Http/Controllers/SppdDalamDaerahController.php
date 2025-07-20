<?php

namespace App\Http\Controllers;

use App\Models\SppdDalamDaerah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\SppdDalamDaerahExport;
use Maatwebsite\Excel\Facades\Excel;

class SppdDalamDaerahController extends Controller
{
    public function index(Request $request)
    {
        $query = SppdDalamDaerah::query();
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_surat', 'LIKE', "%{$search}%")
                  ->orWhere('tujuan', 'LIKE', "%{$search}%")
                  ->orWhere('perihal', 'LIKE', "%{$search}%")
                  ->orWhere('nama_petugas', 'LIKE', "%{$search}%");
            });
        }
        
        $sppd = $query->paginate(10);
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
            'tujuan' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'nama_petugas' => 'required|string',
            'lampiran' => 'required|mimes:pdf,doc,docx,jpg,jpeg,png|max:2097152',
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

    public function detail($id)
    {
        $sppd = SppdDalamDaerah::findOrFail($id);
        return view('sppd-dalam-daerah.detail', compact('sppd'));
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
                'no_surat' => 'required|string|max:255',
                'tanggal' => 'required|date',
                'tujuan' => 'required|string|max:255',
                'perihal' => 'required|string|max:255',
                'nama_petugas' => 'required|string',
                'lampiran' => 'nullable|mimes:pdf,doc,docx,jpg,jpeg,png|max:2097152',
            ]);

            if ($request->hasFile('lampiran')) {
                // Hapus file lama jika ada
                if ($sppdDalamDaerah->lampiran) {
                    Storage::disk('public')->delete($sppdDalamDaerah->lampiran);
                }
                
                // Upload file baru
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
        // Hapus file lampiran jika ada
        if ($sppdDalamDaerah->lampiran) {
            Storage::disk('public')->delete($sppdDalamDaerah->lampiran);
        }

        $sppdDalamDaerah->delete();

        return redirect()->route('sppd-dalam-daerah.index')
            ->with('success', 'SPPD Dalam Daerah berhasil dihapus');
    }

    public function export()
    {
        return Excel::download(new SppdDalamDaerahExport, 'sppd-dalam-daerah.xlsx');
    }
} 