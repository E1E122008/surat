<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Admin tidak boleh melihat akun superadmin (Intership)
        if (auth()->user()->role === 'admin') {
            $query->where('role', '!=', 'superadmin');
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        if ($request->has('sort')) {
            session(['users_sort' => $request->sort]);
        }
        $sortOrder = session('users_sort', 'desc');
        $query->orderBy('created_at', $sortOrder);

        $users = $query->paginate(10)->withQueryString();
        
        if (auth()->user()->role === 'admin') {
            $totalUsers = User::where('role', '!=', 'superadmin')->count();
        } else {
            $totalUsers = User::count();
        }

        return view('users.index', compact('users', 'totalUsers', 'sortOrder'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,user,monitor,superadmin'],
            'dinas' => ['required', 'string', 'max:255'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'dinas' => $request->dinas,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        if (auth()->user()->role === 'admin') {
            abort(403, 'Anda tidak memiliki hak akses untuk mengedit pengguna.');
        }
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if (auth()->user()->role === 'admin') {
            abort(403, 'Anda tidak memiliki hak akses untuk mengedit pengguna.');
        }
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // Update role dan dinas jika ada
        if ($request->has('role')) {
            $user->update(['role' => $request->role]);
        }
        if ($request->has('dinas')) {
            $user->update(['dinas' => $request->dinas]);
        }

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if (auth()->user()->role === 'admin') {
            abort(403, 'Anda tidak memiliki hak akses untuk menghapus pengguna.');
        }
        $user->delete();
        \App\Models\ActivityLog::log('user_delete', "Menghapus akun {$user->name}");
        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    public function resetPassword(Request $request, User $user)
    {
        if (!in_array(auth()->user()->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized action.');
        }

        if (auth()->user()->role === 'admin' && $user->role === 'superadmin') {
            abort(403, 'Admin tidak dapat mereset sandi Superadmin.');
        }

        // Tidak ada lagi pencegahan khusus

        $request->validate([
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Catat di activity log
        $actorRole = ucfirst(auth()->user()->role);
        \App\Models\ActivityLog::log('password_reset', "{$actorRole} " . auth()->user()->name . " mereset kata sandi akun {$user->name}");

        return redirect()->route('users.index')
            ->with('success', 'Password pengguna berhasil direset.');
    }
} 
