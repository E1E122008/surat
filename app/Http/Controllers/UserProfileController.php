<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
use App\Models\Activity; // Jika menggunakan model Activity
use App\Models\LoginHistory; // Jika menggunakan model LoginHistory

class UserProfileController extends Controller
{
    public function index()
    {
        // Inisialisasi data aktivitas terbaru (kosong untuk sementara)
        $recentActivities = collect([]); // Menggunakan collection kosong
        
        // Inisialisasi riwayat login (kosong untuk sementara)
        $loginHistory = collect([]); // Menggunakan collection kosong

        return view('profile', compact('recentActivities', 'loginHistory'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string|max:20',
            'jabatan' => 'required|string|max:100',
            'nip' => 'nullable|string|max:50',
        ]);

        auth()->user()->update($request->only([
            'name',
            'email',
            'phone',
            'jabatan',
            'nip'
        ]));

        return redirect()->back()->with('success', 'Profil berhasil diperbarui');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, auth()->user()->password)) {
                    $fail('Password saat ini tidak sesuai.');
                }
            }],
            'password' => 'required|string|min:8|confirmed',
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->back()->with('success', 'Password berhasil diperbarui');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if (auth()->user()->avatar) {
                Storage::disk('public')->delete(auth()->user()->avatar);
            }

            // Upload avatar baru
            $path = $request->file('avatar')->store('avatars', 'public');
            
            auth()->user()->update(['avatar' => $path]);

            return redirect()->back()->with('success', 'Foto profil berhasil diperbarui');
        }

        return redirect()->back()->with('error', 'Terjadi kesalahan saat mengupload foto');
    }

    // Anda bisa menghapus metode show jika tidak diperlukan
} 