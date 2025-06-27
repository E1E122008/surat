<?php

namespace App\Http\Controllers;

use App\Models\ApprovalRequest;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\DataRequestNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DataRequestController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = ApprovalRequest::with('user')
            ->when(Auth::user()->role != 'admin', function ($query) {
                return $query->where('user_id', Auth::id());
            });

        // Total semua data (tanpa filter status/search)
        $totalAll = (clone $query)->count();

        // Filter berdasarkan status
        if ($request->filled('status') && $request->status != 'all') {
            if ($request->status == 'pending_review') {
                $query->where('status', 'pending');
            } else {
                $query->where('status', $request->status);
            }
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

        // Total setelah filter
        $totalFiltered = (clone $query)->count();

        $approvalRequests = $query->latest()->paginate(10)->withQueryString();

        return view('data-requests.index', compact('approvalRequests', 'totalFiltered', 'totalAll'));
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
            'tanggal_surat' => 'required|date',
            'perihal' => 'required|string|max:255',
            'lampiran' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'notes' => 'nullable|string',
            'no_surat' => 'required|string|max:255',
        ]);

        $lampiranPath = null;
        if ($request->hasFile('lampiran')) {
            try {
                $lampiranPath = $request->file('lampiran')->store('lampiran', 'public');
            } catch (\Exception $e) {
                Log::error('Failed to upload lampiran: ' . $e->getMessage());
                return redirect()->back()
                    ->with('error', 'Gagal mengunggah lampiran. Silakan coba lagi.');
            }
        }

        $dataRequest = ApprovalRequest::create([
            'user_id' => Auth::id(),
            'letter_type' => $validated['letter_type'],
            'sender' => $validated['sender'],
            'tanggal_surat' => $validated['tanggal_surat'],
            'perihal' => $validated['perihal'],
            'lampiran' => $lampiranPath,
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
            'no_surat' => $validated['no_surat'],
        ]);

        Log::info('Data Request created:', $dataRequest->toArray());

        // Notify admin
        $admin = \App\Models\User::where('role', 'admin')->first();
        if ($admin) {
            $admin->notify(new DataRequestNotification($dataRequest));
        }

        Log::info('Redirecting to data-requests.index');
        return redirect()->route('data-requests.index')
            ->with('success', 'Permintaan berhasil dikirim. Menunggu review admin.');
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

    public function cancel(ApprovalRequest $dataRequest)
    {
        // You might want to add authorization here, e.g., using a policy
        // $this->authorize('cancel', $dataRequest);

        // Optional: Check if the request status is pending before canceling
        if ($dataRequest->status !== 'pending') {
            return redirect()->route('data-requests.index')
                ->with('error', 'Permintaan hanya bisa dibatalkan jika statusnya masih Menunggu Review.');
        }

        try {
            // If there's an uploaded file, delete it from storage
            if ($dataRequest->lampiran) {
                Storage::disk('public')->delete($dataRequest->lampiran);
            }

            $dataRequest->delete();
            return redirect()->route('data-requests.index')
                ->with('success', 'Permintaan berhasil dibatalkan.');
        } catch (\Exception $e) {
            Log::error('Failed to cancel data request: ' . $e->getMessage());
            return redirect()->route('data-requests.index')
                ->with('error', 'Gagal membatalkan permintaan. Silakan coba lagi.');
        }
    }
} 