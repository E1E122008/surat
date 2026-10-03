<?php

namespace App\Http\Controllers;

use App\Models\SkKaro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\SkKaroExport;
use Maatwebsite\Excel\Facades\Excel;

class SkKaroController extends Controller
{
    public function index(Request $request)
    {
        $query = SkKaro::query();
        
        if ($request->has('sort')) {
            session(['sk_karo_sort' => $request->sort]);
        }
        $sortOrder = session('sk_karo_sort', 'desc');

        if ($sortOrder === 'asc') {
            $query->oldest();
        } else {
            $query->latest();
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_sk', 'LIKE', "%{$search}%")
                  ->orWhere('perihal', 'LIKE', "%{$search}%");
            });
        }
        
        $sk_karos = $query->paginate(10)->appends($request->query());
        return view('sk_karo.index', compact('sk_karos', 'sortOrder'));
    }

    public function create()
    {
        $nomor_sk = SkKaro::generateNomorSk();
        return view('sk_karo.create', compact('nomor_sk'));
    }

    public function export()
    {
        return Excel::download(new SkKaroExport, 'sk-karo.xlsx');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'no_sk' => 'required|string|max:255',
                'tanggal_sk' => 'required|date',
                'perihal' => 'required|string',
                'pejabat_ttd' => 'required|string|max:255',
                'file_surat' => 'nullable|array',
                'file_surat.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // 5MB
            ]);

            $lampiranPaths = [];
            if ($request->hasFile('file_surat')) {
                $files = $request->file('file_surat');
                if (!is_array($files)) {
                    $files = [$files];
                }
                foreach ($files as $file) {
                    $lampiranPaths[] = [
                        'path' => $file->store('lampiran/sk-karo', 'public'),
                        'name' => $file->getClientOriginalName(),
                    ];
                }
                $validated['file_surat'] = json_encode($lampiranPaths);
            }

            SkKaro::create($validated);
            
            return redirect()->route('sk-karo.index')->with('success', 'SK KARO berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            // Hapus jika gagal
            if (isset($lampiranPaths)) {
                foreach ($lampiranPaths as $lampiran) {
                    if (isset($lampiran['path']) && Storage::disk('public')->exists($lampiran['path'])) {
                        Storage::disk('public')->delete($lampiran['path']);
                    }
                }
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        $sk_karo = SkKaro::findOrFail($id);
        return view('sk_karo.edit', compact('sk_karo'));
    }

    public function update(Request $request, $id)
    {
        try {
            $sk = SkKaro::findOrFail($id);
            $validated = $request->validate([
                'no_sk' => 'required|string|max:255',
                'tanggal_sk' => 'required|date',
                'perihal' => 'required|string',
                'pejabat_ttd' => 'required|string|max:255',
                'file_surat' => 'nullable|array',
                'file_surat.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            ]);

            $lampiranLama = $request->input('lampiran_lama', []);
            $lampiranDihapus = $request->input('lampiran_dihapus', []);
            $lampiranData = [];
            $lampiranSebelumnya = json_decode($sk->file_surat, true) ?? [];

            foreach ($lampiranSebelumnya as $file) {
                if (!in_array($file['path'], $lampiranDihapus) && in_array($file['path'], $lampiranLama)) {
                    $lampiranData[] = $file;
                } else {
                    if (Storage::disk('public')->exists($file['path'])) {
                        Storage::disk('public')->delete($file['path']);
                    }
                }
            }

            if ($request->hasFile('file_surat')) {
                foreach ($request->file('file_surat') as $file) {
                    $lampiranData[] = [
                        'path' => $file->store('lampiran/sk-karo', 'public'),
                        'name' => $file->getClientOriginalName(),
                    ];
                }
            }

            $validated['file_surat'] = empty($lampiranData) ? null : json_encode($lampiranData);

            $sk->update($validated);

            return redirect()->route('sk-karo.index')->with('success', 'SK KARO berhasil diperbarui!');
                
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $sk = SkKaro::findOrFail($id);
            if ($sk->file_surat) {
                $lampiranData = json_decode($sk->file_surat, true);
                if (is_array($lampiranData)) {
                    foreach ($lampiranData as $lampiran) {
                        if (isset($lampiran['path']) && Storage::disk('public')->exists($lampiran['path'])) {
                            Storage::disk('public')->delete($lampiran['path']);
                        }
                    }
                }
            }

            $sk->delete();

            return redirect()->back()->with('success', 'SK KARO berhasil dihapus!');
                
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}
