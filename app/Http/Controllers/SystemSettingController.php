<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SystemSettingController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'superadmin') {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Superadmin.');
        }

        $setting = \App\Models\SystemSetting::first();
        return view('admin.system-settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        if (Auth::user()->role !== 'superadmin') {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Superadmin.');
        }

        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'headline' => 'required|string|max:255',
            'description' => 'required|string',
            'operational_time' => 'required|string|max:255',
        ]);

        $setting = \App\Models\SystemSetting::first();
        if (!$setting) {
            $setting = new \App\Models\SystemSetting();
        }

        $setting->headline = $request->headline;
        $setting->description = $request->description;
        $setting->operational_time = $request->operational_time;

        if ($request->hasFile('logo')) {
            // Delete old logo if exist
            if ($setting->logo && \Illuminate\Support\Facades\Storage::disk('public')->exists($setting->logo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($setting->logo);
            }
            // Store new logo
            $path = $request->file('logo')->store('settings', 'public');
            $setting->logo = $path;
        }

        $setting->save();

        return redirect()->back()->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }
}
