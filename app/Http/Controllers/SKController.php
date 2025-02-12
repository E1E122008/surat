<?php

namespace App\Http\Controllers;

use App\Models\SK; // Pastikan model SK sudah ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\SKExport; // Pastikan Anda memiliki export untuk SK
use Maatwebsite\Excel\Facades\Excel;

class SKController extends Controller
{
    public function index()
    {
        $sk = SK::latest()->paginate(10);
        return view('draft-phd.sk.index', compact('sk'));
    }

    public function create()
    {
        return view('draft-phd.sk.create'); // Pastikan tampilan create ada
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_surat' => 'required|string|max:255|unique:sks,no_surat',
            'pengirim' => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'tanggal_terima' => 'required|date',
            'perihal' => 'required|string|max:255',
            'lampiran' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $path = $file->store('lampiran/sk', 'public');
            $validated['lampiran'] = $path;
        }

        SK::create($validated);

        return redirect()->route('draft-phd.sk.index')
            ->with('success', 'SK berhasil ditambahkan');
    }

    public function edit(SK $sk)
    {
        return view('draft-phd.sk.edit', compact('sk'));
    }

    public function update(Request $request, SK $sk)
    {
        $validated = $request->validate([
            'no_surat' => 'required|string|max:255',
            'pengirim' => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'tanggal_terima' => 'required|date',
            'perihal' => 'required|string|max:255',
            'lampiran' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
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
    }

    public function destroy(SK $sk)
    {
        if ($sk->lampiran) {
            Storage::disk('public')->delete($sk->lampiran);
        }
        
        $sk->delete();

        return redirect()->route('draft-phd.sk.index')
            ->with('success', 'SK berhasil dihapus');
    }

    public function export() 
    {
        return Excel::download(new SKExport, 'sk.xlsx'); // Pastikan Anda memiliki export untuk SK
    }
} 