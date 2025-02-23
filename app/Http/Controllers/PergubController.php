<?php

namespace App\Http\Controllers;

use App\Models\Pergub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\PergubExport;
use Maatwebsite\Excel\Facades\Excel;

class PergubController extends Controller
{
    public function index(Request $request)
    {
        $query = Pergub::query();
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_agenda', 'LIKE', "%{$search}%")
                  ->orWhere('no_surat', 'LIKE', "%{$search}%")
                  ->orWhere('pengirim', 'LIKE', "%{$search}%")
                  ->orWhere('perihal', 'LIKE', "%{$search}%");
            });
        }
        
        $pergubs = $query->latest()->paginate(10);
        return view('draft-phd.pergub.index', compact('pergubs'));
    }

    public function create()
    {
        return view('draft-phd.pergub.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'no_agenda' => 'required|string|max:255',
                'no_surat' => 'required|string|max:255|unique:pergub,no_surat',
                'pengirim' => 'required|string|max:255',
                'tanggal_surat' => 'required|date',
                'tanggal_terima' => 'required|date',
                'perihal' => 'required|string|max:255',
                'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            ]);

            if ($request->hasFile('lampiran')) {
                $file = $request->file('lampiran');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('lampiran/pergub', $fileName, 'public');
                $validated['lampiran'] = $path;
            }

            $pergub = Pergub::create($validated);

            if (!$pergub) {
                throw new \Exception('Gagal menyimpan data pergub');
            }

            return redirect()->route('draft-phd.pergub.index')
                ->with('success', 'Pergub berhasil ditambahkan');
        } catch (\Exception $e) {
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan data!')
                ->withInput();

        }
    }

    public function detail($id)
    {
        $pergub = Pergub::findOrFail($id);
        return view('draft-phd.pergub.detail', compact('pergub'));
    }

    public function edit($id)
    {       
        $pergub = Pergub::findOrFail($id);
        return view('draft-phd.pergub.edit', compact('pergub'));
    }   

    public function update(Request $request, $id)
    {
        try {
            $pergub = Pergub::findOrFail($id);
            $validated = $request->validate([
                'no_agenda' => 'required|string|max:255',
                'no_surat' => 'required|string|max:255',
                'pengirim' => 'required|string|max:255',
                'tanggal_surat' => 'required|date',
                'tanggal_terima' => 'required|date',
                'perihal' => 'required|string|max:255',
                'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048', 
            ]);

            if ($request->hasFile('lampiran')) {
                if ($pergub->lampiran) {
                    Storage::disk('public')->delete($pergub->lampiran);
                }

                $file = $request->file('lampiran');
                $path = $file->store('lampiran/pergub', 'public');
                $validated['lampiran'] = $path;
            }

            $pergub->update($validated);

            return redirect()->route('draft-phd.pergub.index')
                ->with('success', 'Pergub berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui data!');
        }
    }

    public function updateCatatan(Request $request, $id)
    {
        try {
            $pergub = Pergub::findOrFail($id);
            $pergub->update([
                'catatan' => $request->catatan
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Catatan berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui catatan'
            ], 500);
        }
    }

    public function destroy(Pergub $pergub)
    {   
        if ($pergub->lampiran) {
            Storage::disk('public')->delete($pergub->lampiran);
        }

        $pergub->delete();

        return redirect()->route('draft-phd.pergub.index')
            ->with('success', 'Pergub berhasil dihapus');
    }

    public function export()
    {
        return Excel::download(new PergubExport(), 'pergub.xlsx');
    }

    
}