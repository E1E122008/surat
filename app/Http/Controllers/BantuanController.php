<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\HelpRequest;
use App\Models\User;
use App\Notifications\HelpRequestNotification;
use Illuminate\Support\Facades\Notification;

class BantuanController extends Controller
{
    public function index()
    {
        if (in_array(Auth::user()->role, ['admin', 'superadmin'])) {
            if (request()->has('sort')) {
                session(['bantuan_sort' => request('sort')]);
            }
            $sortOrder = session('bantuan_sort', 'desc');
            $sortDir = ($sortOrder === 'asc') ? 'asc' : 'desc';

            $query = HelpRequest::orderBy('created_at', $sortDir);

            if (request()->filled('search')) {
                $search = request('search');
                $query->where(function($q) use ($search) {
                    $q->where('nama_pengirim', 'like', "%{$search}%")
                      ->orWhere('instansi', 'like', "%{$search}%")
                      ->orWhere('kendala', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            }

            if (request()->filled('status')) {
                $query->where('status', request('status'));
            }

            $requests = $query->paginate(15)->appends(request()->query());
            return view('bantuan.admin', compact('requests', 'sortOrder'));
        }
        $riwayat = HelpRequest::where('user_id', Auth::id())->latest()->get();
        return view('bantuan.index', compact('riwayat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kendala' => 'required|string',
            'deskripsi' => 'nullable|string'
        ]);

        $helpRequest = HelpRequest::create([
            'user_id' => Auth::id(),
            'nama_pengirim' => Auth::user()->name,
            'kontak' => Auth::user()->email,
            'kendala' => $request->input('kendala'),
            'deskripsi' => $request->input('deskripsi') ?? '-',
            'status' => 'menunggu'
        ]);

        $admins = User::whereIn('role', ['admin', 'superadmin'])->get();
        Notification::send($admins, new HelpRequestNotification($helpRequest, 'new'));

        return redirect()->back()->with('success', 'Keluhan berhasil terkirim. Admin atau pihak Biro Hukum akan segera memeriksa permasalahan Anda.');
    }

    public function updateStatus(Request $request, $id)
    {
        $helpRequest = HelpRequest::findOrFail($id);
        $helpRequest->update([
            'status' => $request->status,
            'balasan_admin' => $request->filled('balasan_admin') ? $request->balasan_admin : $helpRequest->balasan_admin
        ]);

        if ($helpRequest->user_id) {
            $helpRequest->user->notify(new HelpRequestNotification($helpRequest, 'updated'));
        }

        return redirect()->back()->with('success', 'Status laporan berhasil diperbarui.');
    }

    public function riwayat()
    {
        $riwayat = HelpRequest::where('user_id', Auth::id())->latest()->get();
        return view('bantuan.riwayat', compact('riwayat'));
    }
}
