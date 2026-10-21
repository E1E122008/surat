<?php

use App\Http\Controllers\Admin\ApprovalRequestController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BukuAgendaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataRequestController;
use App\Http\Controllers\KategoriKeluarController;
use App\Http\Controllers\PerdaController;
use App\Http\Controllers\PergubController;
use App\Http\Controllers\SKController;
use App\Http\Controllers\SkKaroController;
use App\Http\Controllers\SppdDalamDaerahController;
use App\Http\Controllers\SppdLuarDaerahController;
use App\Http\Controllers\SptDalamDaerahController;
use App\Http\Controllers\SptLuarDaerahController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\TransaksiSuratController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

Route::get('bantuan-akses', function () {
    return view('auth.bantuan');
})->name('bantuan.akses')->middleware('guest');

Route::post('bantuan-akses', function (\Illuminate\Http\Request $request) {
    $helpRequest = \App\Models\HelpRequest::create([
        'nama_pengirim' => $request->input('nama'),
        'instansi' => $request->input('instansi'),
        'kontak' => $request->input('kontak'),
        'kendala' => $request->input('kendala'),
        'deskripsi' => $request->input('deskripsi') ?? '-',
        'status' => 'menunggu',
    ]);

    $admins = \App\Models\User::where('role', 'admin')->get();
    \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\HelpRequestNotification($helpRequest, 'new'));

    return redirect()->route('login')->with('success', 'Permintaan Anda telah tercatat. Admin Sistem akan segera menghubungi Anda.');
})->name('bantuan.akses.kirim')->middleware('guest');
// Rute untuk mengupdate status HelpRequest dari panel admin
Route::post('bantuan/{id}/status', [\App\Http\Controllers\BantuanController::class, 'updateStatus'])->name('bantuan.status.update')->middleware(['auth', 'checkRole:admin,superadmin']);

Route::match(['get', 'post'], 'logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout')
    ->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');
    Route::get('/bantuan/riwayat', [\App\Http\Controllers\BantuanController::class, 'riwayat'])->name('bantuan.riwayat');
    Route::get('/bantuan', [\App\Http\Controllers\BantuanController::class, 'index'])->name('bantuan.index');
    Route::post('/bantuan', [\App\Http\Controllers\BantuanController::class, 'store'])->name('bantuan.store');

    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::middleware(['checkRole:admin,superadmin,monitor'])->group(function () {
        // Routes untuk semua role (termasuk monitor) - hanya view
        Route::get('surat-masuk', [SuratMasukController::class, 'index'])->name('surat-masuk.index');
        Route::get('surat-masuk/{id}/detail', [SuratMasukController::class, 'detail'])->name('surat-masuk.detail');

        // Routes yang dibatasi untuk monitor - hanya admin dan user
        Route::get('surat-masuk/create', [SuratMasukController::class, 'create'])->name('surat-masuk.create')->middleware('preventMonitor');
        Route::post('surat-masuk', [SuratMasukController::class, 'store'])->name('surat-masuk.store')->middleware('preventMonitor');
        Route::get('surat-masuk/{id}/status', [SuratMasukController::class, 'status'])->name('surat-masuk.status')->middleware('preventMonitor');
        Route::get('surat-masuk/{id}/edit', [SuratMasukController::class, 'edit'])->name('surat-masuk.edit')->middleware('preventMonitor');
        Route::put('surat-masuk/{id}', [SuratMasukController::class, 'update'])->name('surat-masuk.update')->middleware('preventMonitor');
        Route::post('/surat-masuk/{id}/update-status', [SuratMasukController::class, 'updateStatus'])
            ->name('surat-masuk.update-status')->middleware('preventMonitor');
        Route::delete('surat-masuk/{suratMasuk}', [SuratMasukController::class, 'destroy'])
            ->name('surat-masuk.destroy')->middleware('preventMonitor');
        Route::get('surat-masuk-export', [SuratMasukController::class, 'export'])
            ->name('surat-masuk.export')->middleware('preventMonitor');

        // Daftar boleh dilihat monitor; tambah/edit/hapus dibatasi seperti modul lain
        Route::resource('surat-keluar', SuratKeluarController::class)->only('index');
        Route::resource('surat-keluar', SuratKeluarController::class)->except(['index', 'show'])
            ->middleware('preventMonitor');
        Route::get('surat-keluar-export', [SuratKeluarController::class, 'export'])
            ->name('surat-keluar.export')->middleware('preventMonitor');

        // Routes untuk semua role (termasuk monitor) - hanya view
        Route::get('sppd-dalam-daerah', [SppdDalamDaerahController::class, 'index'])->name('sppd-dalam-daerah.index');
        Route::get('sppd-dalam-daerah/{id}/detail', [SppdDalamDaerahController::class, 'detail'])
            ->name('sppd-dalam-daerah.detail');

        // Routes yang dibatasi untuk monitor
        Route::get('sppd-dalam-daerah/create', [SppdDalamDaerahController::class, 'create'])
            ->name('sppd-dalam-daerah.create')->middleware('preventMonitor');
        Route::post('sppd-dalam-daerah', [SppdDalamDaerahController::class, 'store'])->name('sppd-dalam-daerah.store')->middleware('preventMonitor');
        Route::get('sppd-dalam-daerah/{id}/edit', [SppdDalamDaerahController::class, 'edit'])
            ->name('sppd-dalam-daerah.edit')->middleware('preventMonitor');
        Route::put('sppd-dalam-daerah/{id}', [SppdDalamDaerahController::class, 'update'])
            ->name('sppd-dalam-daerah.update')->middleware('preventMonitor');
        Route::delete('sppd-dalam-daerah/{id}', [SppdDalamDaerahController::class, 'destroy'])
            ->name('sppd-dalam-daerah.destroy')->middleware('preventMonitor');
        Route::get('sppd-dalam-daerah-export', [SppdDalamDaerahController::class, 'export'])
            ->name('sppd-dalam-daerah.export')->middleware('preventMonitor');

        // Routes untuk semua role (termasuk monitor) - hanya view
        Route::get('sppd-luar-daerah', [SppdLuarDaerahController::class, 'index'])->name('sppd-luar-daerah.index');
        Route::get('/sppd-luar-daerah/{id}/detail', [SppdLuarDaerahController::class, 'detail'])->name('sppd-luar-daerah.detail');

        // Routes yang dibatasi untuk monitor
        Route::get('sppd-luar-daerah/create', [SppdLuarDaerahController::class, 'create'])
            ->name('sppd-luar-daerah.create')->middleware('preventMonitor');
        Route::post('sppd-luar-daerah', [SppdLuarDaerahController::class, 'store'])->name('sppd-luar-daerah.store')->middleware('preventMonitor');
        Route::get('/sppd-luar-daerah/{sppdLuarDaerah}/edit', [SppdLuarDaerahController::class, 'edit'])
            ->name('sppd-luar-daerah.edit')->middleware('preventMonitor');
        Route::put('sppd-luar-daerah/{id}', [SppdLuarDaerahController::class, 'update'])
            ->name('sppd-luar-daerah.update')->middleware('preventMonitor');
        Route::delete('sppd-luar-daerah/{id}', [SppdLuarDaerahController::class, 'destroy'])->name('sppd-luar-daerah.destroy')->middleware('preventMonitor');
        Route::get('sppd-luar-daerah-export', [SppdLuarDaerahController::class, 'export'])
            ->name('sppd-luar-daerah.export')->middleware('preventMonitor');

        // Routes untuk semua role (termasuk monitor) - hanya view
        Route::get('spt-dalam-daerah', [SptDalamDaerahController::class, 'index'])->name('spt-dalam-daerah.index');
        Route::get('spt-dalam-daerah/{id}/detail', [SptDalamDaerahController::class, 'detail'])
            ->name('spt-dalam-daerah.detail');

        // Routes yang dibatasi untuk monitor
        Route::get('spt-dalam-daerah/create', [SptDalamDaerahController::class, 'create'])->name('spt-dalam-daerah.create')->middleware('preventMonitor');
        Route::post('spt-dalam-daerah', [SptDalamDaerahController::class, 'store'])->name('spt-dalam-daerah.store')->middleware('preventMonitor');
        Route::get('spt-dalam-daerah/{sptDalamDaerah}/edit', [SptDalamDaerahController::class, 'edit'])
            ->name('spt-dalam-daerah.edit')->middleware('preventMonitor');
        Route::put('spt-dalam-daerah/{sptDalamDaerah}', [SptDalamDaerahController::class, 'update'])
            ->name('spt-dalam-daerah.update')->middleware('preventMonitor');
        Route::delete('spt-dalam-daerah/{sptDalamDaerah}', [SptDalamDaerahController::class, 'destroy'])->name('spt-dalam-daerah.destroy')->middleware('preventMonitor');
        Route::get('spt-dalam-daerah-export', [SptDalamDaerahController::class, 'export'])
            ->name('spt-dalam-daerah.export')->middleware('preventMonitor');

        // Routes untuk semua role (termasuk monitor) - hanya view
        Route::get('spt-luar-daerah', [SptLuarDaerahController::class, 'index'])->name('spt-luar-daerah.index');
        Route::get('spt-luar-daerah/{id}/detail', [SptLuarDaerahController::class, 'detail'])
            ->name('spt-luar-daerah.detail');

        // Routes yang dibatasi untuk monitor
        Route::get('spt-luar-daerah/create', [SptLuarDaerahController::class, 'create'])->name('spt-luar-daerah.create')->middleware('preventMonitor');
        Route::post('spt-luar-daerah', [SptLuarDaerahController::class, 'store'])->name('spt-luar-daerah.store')->middleware('preventMonitor');
        Route::get('spt-luar-daerah/{id}/edit', [SptLuarDaerahController::class, 'edit'])
            ->name('spt-luar-daerah.edit')->middleware('preventMonitor');
        Route::put('spt-luar-daerah/{sptLuarDaerah}', [SptLuarDaerahController::class, 'update'])
            ->name('spt-luar-daerah.update')->middleware('preventMonitor');
        Route::delete('spt-luar-daerah/{sptLuarDaerah}', [SptLuarDaerahController::class, 'destroy'])
            ->name('spt-luar-daerah.destroy')->middleware('preventMonitor');
        Route::get('spt-luar-daerah-export', [SptLuarDaerahController::class, 'export'])
            ->name('spt-luar-daerah.export')->middleware('preventMonitor');

        // Arsip (Buku Agenda) - khusus admin dan superadmin, sesuai menu sidebar
        Route::middleware('checkRole:admin,superadmin')->group(function () {
            Route::get('/buku-agenda', [BukuAgendaController::class, 'index'])->name('buku-agenda.index');
            Route::get('/buku-agenda/kategori-keluar', [KategoriKeluarController::class, 'index'])->name('buku-agenda.kategori-keluar.index');
            Route::get('/buku-agenda/export', [BukuAgendaController::class, 'export'])->name('buku-agenda.export');
            Route::get('/buku-agenda/kategori-keluar/export', [KategoriKeluarController::class, 'export'])
                ->name('buku-agenda.kategori-keluar.export');
        });

        // Routes SK KARO (Opsi B: Menu Utama Independen)
        Route::get('sk-karo', [SkKaroController::class, 'index'])->name('sk-karo.index');
        Route::get('sk-karo/create', [SkKaroController::class, 'create'])->name('sk-karo.create')->middleware('preventMonitor');
        Route::get('sk-karo-export', [SkKaroController::class, 'export'])->name('sk-karo.export')->middleware('preventMonitor');
        Route::post('sk-karo', [SkKaroController::class, 'store'])->name('sk-karo.store')->middleware('preventMonitor');
        Route::get('sk-karo/{id}/edit', [SkKaroController::class, 'edit'])->name('sk-karo.edit')->middleware('preventMonitor');
        Route::put('sk-karo/{id}', [SkKaroController::class, 'update'])->name('sk-karo.update')->middleware('preventMonitor');
        Route::delete('sk-karo/{id}', [SkKaroController::class, 'destroy'])->name('sk-karo.destroy')->middleware('preventMonitor');
    });

    Route::get('/profile', [UserProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [UserProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/avatar', [UserProfileController::class, 'updateAvatar'])->name('profile.avatar');

    Route::get('/profile/activity', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('profile.activity');

    Route::middleware(['checkRole:admin,superadmin,monitor'])->group(function () {
        Route::get('/draft-phd/sk', [SKController::class, 'index'])->name('draft-phd.sk.index');

        Route::get('/draft-phd/perda', [PerdaController::class, 'index'])->name('draft-phd.perda.index');

        Route::get('/draft-phd/pergub', [PergubController::class, 'index'])->name('draft-phd.pergub.index');

        // Routes untuk semua role (termasuk monitor) - hanya view
        Route::get('/draft-phd/sk/{sk}/detail', [SKController::class, 'detail'])->name('draft-phd.sk.detail');

        // Routes yang dibatasi untuk monitor
        Route::get('/draft-phd/sk/create', [SKController::class, 'create'])->name('draft-phd.sk.create')->middleware('preventMonitor');
        Route::post('/draft-phd/sk', [SKController::class, 'store'])->name('draft-phd.sk.store')->middleware('preventMonitor');
        Route::get('/draft-phd/sk/{sk}/status', [SKController::class, 'status'])->name('draft-phd.sk.status')->middleware('preventMonitor');
        Route::post('/sk/update-status/{id}', [SKController::class, 'updateStatus'])
            ->name('sk.update-status')->middleware('preventMonitor');
        Route::get('/draft-phd/sk/export', [SKController::class, 'export'])->name('draft-phd.sk.export')->middleware('preventMonitor');
        Route::get('/draft-phd/sk/{sk}/edit', [SKController::class, 'edit'])->name('draft-phd.sk.edit')->middleware('preventMonitor');
        Route::put('/draft-phd/sk/{sk}', [SKController::class, 'update'])->name('draft-phd.sk.update')->middleware('preventMonitor');
        Route::delete('/draft-phd/sk/{sk}', [SKController::class, 'destroy'])->name('draft-phd.sk.destroy')->middleware('preventMonitor');
        Route::post('/draft-phd/sk/{id}/update-catatan', [SKController::class, 'updateCatatan'])->middleware('preventMonitor');

        Route::post('/surat-masuk/{id}/update-disposisi', [SuratMasukController::class, 'updateDisposisi'])
            ->name('surat-masuk.update-disposisi')->middleware('preventMonitor');
        Route::post('/surat-masuk/{id}/update-catatan', [SuratMasukController::class, 'updateCatatan'])->name('surat-masuk.update-catatan')->middleware('preventMonitor');

        // Route perda - Routes untuk semua role (termasuk monitor) - hanya view
        Route::get('/draft-phd/perda/{perda}/detail', [PerdaController::class, 'detail'])->name('draft-phd.perda.detail');

        // Routes yang dibatasi untuk monitor
        Route::get('/draft-phd/perda/create', [PerdaController::class, 'create'])->name('draft-phd.perda.create')->middleware('preventMonitor');
        Route::post('/draft-phd/perda', [PerdaController::class, 'store'])->name('draft-phd.perda.store')->middleware('preventMonitor');
        Route::get('/draft-phd/perda/{perda}/edit', [PerdaController::class, 'edit'])->name('draft-phd.perda.edit')->middleware('preventMonitor');
        Route::put('/draft-phd/perda/{perda}', [PerdaController::class, 'update'])->name('draft-phd.perda.update')->middleware('preventMonitor');
        Route::delete('/draft-phd/perda/{perda}', [PerdaController::class, 'destroy'])->name('draft-phd.perda.destroy')->middleware('preventMonitor');
        Route::post('/draft-phd/perda/{id}/update-catatan', [PerdaController::class, 'updateCatatan'])->middleware('preventMonitor');
        Route::get('/draft-phd/perda/export', [PerdaController::class, 'export'])->name('draft-phd.perda.export')->middleware('preventMonitor');
        Route::put('/draft-phd/perda/{id}/update-status', [PerdaController::class, 'updateStatus'])
            ->name('draft-phd.perda.update-status')->middleware('preventMonitor');
        Route::post('/draft-phd/perda/{id}/disposisi', [PerdaController::class, 'disposisi'])
            ->name('draft-phd.perda.disposisi')->middleware('preventMonitor');
        Route::post('/draft-phd/perda/{id}/update-disposisi', [PerdaController::class, 'updateDisposisi'])
            ->name('draft-phd.perda.update-disposisi')->middleware('preventMonitor');

        // Route pergub - Routes untuk semua role (termasuk monitor) - hanya view
        Route::get('/draft-phd/pergub/{pergub}/detail', [PergubController::class, 'detail'])->name('draft-phd.pergub.detail');

        // Routes yang dibatasi untuk monitor
        Route::get('/draft-phd/pergub/create', [PergubController::class, 'create'])->name('draft-phd.pergub.create')->middleware('preventMonitor');
        Route::post('/draft-phd/pergub', [PergubController::class, 'store'])->name('draft-phd.pergub.store')->middleware('preventMonitor');

        Route::put('/draft-phd/pergub/{id}/update-status', [PergubController::class, 'updateStatus'])
            ->name('draft-phd.pergub.update-status')->middleware('preventMonitor');
        Route::get('/draft-phd/pergub/{pergub}/edit', [PergubController::class, 'edit'])->name('draft-phd.pergub.edit')->middleware('preventMonitor');
        Route::put('/draft-phd/pergub/{pergub}', [PergubController::class, 'update'])->name('draft-phd.pergub.update')->middleware('preventMonitor');
        Route::delete('/draft-phd/pergub/{pergub}', [PergubController::class, 'destroy'])->name('draft-phd.pergub.destroy')->middleware('preventMonitor');
        Route::post('/draft-phd/pergub/{id}/update-catatan', [PergubController::class, 'updateCatatan'])->middleware('preventMonitor');
        Route::get('/draft-phd/pergub/export', [PergubController::class, 'export'])->name('draft-phd.pergub.export')->middleware('preventMonitor');
        Route::post('/draft-phd/pergub/{id}/disposisi', [PergubController::class, 'disposisi'])
            ->name('draft-phd.pergub.disposisi')->middleware('preventMonitor');
        Route::post('/draft-phd/pergub/{id}/update-disposisi', [PergubController::class, 'updateDisposisi'])
            ->name('draft-phd.pergub.update-disposisi')->middleware('preventMonitor');

        // Disposisi melekat pada tiap surat (kolom `disposisi`), diisi lewat modal di halaman modul
        Route::post('/surat-masuk/{id}/disposisi', [SuratMasukController::class, 'disposisi'])
            ->name('surat-masuk.disposisi')->middleware('preventMonitor');
        Route::post('/draft-phd/sk/{id}/disposisi', [SKController::class, 'disposisi'])
            ->name('draft-phd.sk.disposisi')->middleware('preventMonitor');
    });

    // User Management Routes - untuk admin (terbatas) dan superadmin (penuh)
    Route::resource('users', UserController::class)->except('show')->middleware('checkRole:admin,superadmin');
    Route::put('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword')->middleware('checkRole:admin,superadmin');

    // System Settings (Superadmin Only)
    Route::get('system-settings', [\App\Http\Controllers\SystemSettingController::class, 'index'])->name('system-settings.index')->middleware('checkRole:superadmin');
    Route::post('system-settings', [\App\Http\Controllers\SystemSettingController::class, 'update'])->name('system-settings.update')->middleware('checkRole:superadmin');

    // Data Request Routes - hanya untuk user (form pengajuan ada di modal halaman index)
    Route::get('data-requests', [DataRequestController::class, 'index'])->name('data-requests.index')->middleware('checkRole:user');
    // 'create' harus didaftarkan sebelum '{dataRequest}' agar tidak tertangkap sebagai ID
    Route::get('data-requests/create', [DataRequestController::class, 'create'])->name('data-requests.create')->middleware('checkRole:user');
    Route::get('data-requests/{dataRequest}', [DataRequestController::class, 'show'])->name('data-requests.show')->middleware('checkRole:user');
    Route::post('data-requests', [DataRequestController::class, 'store'])->name('data-requests.store')->middleware('checkRole:user');
    Route::delete('/data-requests/{dataRequest}/cancel', [DataRequestController::class, 'cancel'])->name('data-requests.cancel')->middleware('checkRole:user');

});

// API JSON detail disposisi/status (dipakai halaman Surat Masuk, Perda, Pergub).
// Wajib login dengan role yang sama seperti halaman modulnya.
Route::middleware(['auth', 'checkRole:admin,superadmin,monitor'])->group(function () {
    Route::get('/api/surat-masuk/{id}', [SuratMasukController::class, 'apiShow']);
    Route::get('/api/perda/{id}', [PerdaController::class, 'apiShow']);
    Route::get('/api/pergub/{id}', [PergubController::class, 'apiShow']);
});

// User Routes
Route::middleware(['auth', 'checkRole:user'])->group(function () {
    // Transaksi Surat routes
    Route::get('/transaksi-surat', [TransaksiSuratController::class, 'index'])->name('transaksi-surat.index');
});

// Akses Index Admin & Monitor (Monitor hanya read-only)
Route::middleware(['auth', 'checkRole:admin,monitor,superadmin'])->prefix('admin')->name('admin.')->group(function () {
    // Approval Requests Index
    Route::get('/approval-requests', [ApprovalRequestController::class, 'index'])
        ->name('approval-requests.index');
});

// Admin Only Routes, Edit, Post, Update Persetujuan
Route::middleware(['auth', 'checkRole:admin,superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('/approval-requests/{id}/approve', [ApprovalRequestController::class, 'approve'])
        ->name('approval-requests.approve');
    Route::post('/approval-requests/{id}/reject', [ApprovalRequestController::class, 'reject'])
        ->name('approval-requests.reject');
    Route::post('/approval-requests/{id}/toggle-fisik', [ApprovalRequestController::class, 'toggleFisik'])->name('approval-requests.toggle-fisik');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/read/{id}', [App\Http\Controllers\NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read.all');
});
