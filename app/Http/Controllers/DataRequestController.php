<?php

namespace App\Http\Controllers;

use App\Models\ApprovalRequest;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\DataRequestNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DataRequestController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = ApprovalRequest::with('user')
            ->when(Auth::user()->role != 'admin', function ($query) {
                return $query->where('user_id', Auth::id());
            });

        // Filter berdasarkan status
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Pencarian berdasarkan jenis surat, pengirim, atau catatan
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('letter_type', 'like', "%{$searchTerm}%")
                  ->orWhere('sender', 'like', "%{$searchTerm}%")
                  ->orWhere('notes', 'like', "%{$searchTerm}%");
            });
        }

        $approvalRequests = $query->latest()->get();

        return view('data-requests.index', compact('approvalRequests'));
    }

    public function create()
    {
        return view('data-requests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'letter_type' => 'required|string|max:255',
            'sender' => 'required|string|max:255',
            'notes' => 'required|string',
        ]);

        $dataRequest = ApprovalRequest::create([
            'user_id' => Auth::id(),
            'letter_type' => $validated['letter_type'],
            'sender' => $validated['sender'],
            'notes' => $validated['notes'],
            'status' => 'pending',
        ]);

        // Notify admin
        $admin = \App\Models\User::where('role', 'admin')->first();
        if ($admin) {
            $admin->notify(new DataRequestNotification($dataRequest));
        }

        return redirect()->route('data-requests.index')
            ->with('success', 'Permintaan berhasil dikirim. Menunggu persetujuan admin.');
    }

    public function show(ApprovalRequest $dataRequest)
    {
        $this->authorize('view', $dataRequest);
        return view('data-requests.show', compact('dataRequest'));
    }

    public function edit(ApprovalRequest $dataRequest)
    {
        $this->authorize('update', $dataRequest);
        
        if ($dataRequest->status != 'approved') {
            return redirect()->route('data-requests.index')
                ->with('error', 'Permintaan belum disetujui.');
        }

        $suratMasuk = null;
        if ($dataRequest->data_type == 'disposisi') {
            $suratMasuk = SuratMasuk::all();
        }

        return view('data-requests.edit', compact('dataRequest', 'suratMasuk'));
    }

    public function update(Request $request, ApprovalRequest $dataRequest)
    {
        $this->authorize('update', $dataRequest);

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        $dataRequest->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'],
        ]);

        // Notify user
        $dataRequest->user->notify(new DataRequestNotification($dataRequest));

        return redirect()->route('data-requests.index')
            ->with('success', 'Status permintaan berhasil diperbarui.');
    }
} 