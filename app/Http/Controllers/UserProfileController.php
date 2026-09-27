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
        // 3 recent activities (dengan rule yang sama dengan halaman Riwayat Global)
        $query = \App\Models\ActivityLog::with('user')->latest();

        if (auth()->user()->role !== 'superadmin') {
            $query->where('user_id', auth()->id());
        }

        $recentActivities = $query->take(3)->get();
        
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
            'role' => 'nullable|in:admin,user,monitor',
        ]);

        $updateData = $request->only([
            'name',
            'email',
            'phone',
            'jabatan',
            'nip'
        ]);

        // Hanya admin yang bisa mengubah role
        if (auth()->user()->role === 'admin' && $request->has('role')) {
            $updateData['role'] = $request->role;
        }

        auth()->user()->update($updateData);

        \App\Models\ActivityLog::log('profile_update', "Melakukan pembaruan data profil");

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

        \App\Models\ActivityLog::log('password_change', "Mengganti kata sandi akun");

        return redirect()->back()->with('success', 'Password berhasil diperbarui');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2097152'
        ]);

        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if (auth()->user()->avatar) {
                Storage::disk('public')->delete(auth()->user()->avatar);
            }

            // Upload avatar baru
            $path = $request->file('avatar')->store('avatars', 'public');
            
            auth()->user()->update(['avatar' => $path]);

            \App\Models\ActivityLog::log('avatar_update', "Memperbarui foto profil");

            return redirect()->back()->with('success', 'Foto profil berhasil diperbarui');
        }

        return redirect()->back()->with('error', 'Terjadi kesalahan saat mengupload foto');
    }
} 