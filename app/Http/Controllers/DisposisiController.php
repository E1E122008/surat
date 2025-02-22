<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\SK;
use App\Models\Perda;
use App\Models\Pergub;
use Illuminate\Http\Request;
use App\Models\Disposisi;

class DisposisiController extends Controller
{
    public function index()
    {
        $surat = SuratMasuk::all();
        $sk = SK::all();
        $pergub = Pergub::all();
        $perda = Perda::all();
        return view('disposisi.index', compact('surat', 'sk', 'pergub', 'perda'));
    }
    public function detail($id)
    {
        $surat = SuratMasuk::find($id);
        return view('disposisi.detail', compact('surat'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'disposisi' => 'required|string',
            
        ]);

        // Cari data surat masuk berdasarkan ID
        $surat = SuratMasuk::findOrFail($id);

        // Update disposisi
        $surat->update([
            'disposisi' => $request->disposisi,
            
        ]);

        // Redirect ke halaman edit disposisi
        return redirect()->route('surat-masuk.index', $id)->with('success', 'Disposisi berhasil diperbarui!');
    }

    public function edit($id)
    {
        $suratMasuk = SuratMasuk::findOrFail($id);
        return view('disposisi.update', compact('suratMasuk'));
    }
}