<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\SppdDalamDaerahController;
use App\Http\Controllers\SppdLuarDaerahController;
use App\Http\Controllers\SptDalamDaerahController;
use App\Http\Controllers\SptLuarDaerahController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BukuAgendaController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\SKController;
use App\Http\Controllers\PerdaController;
use App\Http\Controllers\PergubController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DisposisiController;
use App\Http\Controllers\KategoriKeluarController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TransaksiSuratController;
use App\Http\Controllers\Admin\ApprovalRequestController;
use App\Http\Controllers\DataRequestController;
use App\Http\Controllers\SkKaroController;


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
        'status' => 'menunggu'
    ]);
    
    $admins = \App\Models\User::where('role', 'admin')->get();
    \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\HelpRequestNotification($helpRequest, 'new'));

    return redirect()->route('login')->with('success', 'Permintaan Anda telah tercatat. Admin Sistem akan segera menghubungi Anda.');
})->name('bantuan.akses.kirim')->middleware('guest');
// Rute untuk mengupdate status HelpRequest dari panel admin
Route::post('bantuan/{id}/status', [\App\Http\Controllers\BantuanController::class, 'updateStatus'])->name('bantuan.status.update')->middleware('auth');

Route::match(['get', 'post'], 'logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout')
    ->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
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
        Route::put('surat-masuk/{suratMasuk}', [SuratMasukController::class, 'update'])->name('surat-masuk.update')->middleware('preventMonitor');
        Route::post('/surat-masuk/{id}/update-status', [SuratMasukController::class, 'updateStatus'])
            ->name('surat-masuk.update-status')->middleware('preventMonitor');
        Route::delete('surat-masuk/{suratMasuk}', [SuratMasukController::class, 'destroy'])
            ->name('surat-masuk.destroy')->middleware('preventMonitor');
        Route::get('surat-masuk-export', [SuratMasukController::class, 'export'])
            ->name('surat-masuk.export')->middleware('preventMonitor');

        Route::resource('surat-keluar', SuratKeluarController::class);
        Route::get('surat-keluar-export', [SuratKeluarController::class, 'export'])
            ->name('surat-keluar.export');

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
        Route::get('sppd-dalam-daerah/{sppdDalamDaerah}/print', [SppdDalamDaerahController::class, 'print'])
            ->name('sppd-dalam-daerah.print')->middleware('preventMonitor');
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
        Route::delete('sppd-luar-daerah/{sppdLuarDaerah}', [SppdLuarDaerahController::class, 'destroy'])->name('sppd-luar-daerah.destroy')->middleware('preventMonitor');
        Route::get('sppd-luar-daerah/{sppdLuarDaerah}/print', [SppdLuarDaerahController::class, 'print'])
            ->name('sppd-luar-daerah.print')->middleware('preventMonitor');
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
        Route::get('spt-dalam-daerah/{sptDalamDaerah}/print', [SptDalamDaerahController::class, 'print'])
            ->name('spt-dalam-daerah.print')->middleware('preventMonitor');
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

        // Routes untuk semua role (termasuk monitor) - hanya view
        Route::get('/buku-agenda', [BukuAgendaController::class, 'index'])->name('buku-agenda.index');
        Route::get('/buku-agenda/kategori-masuk', [BukuAgendaController::class, 'kategoriMasuk'])->name('buku-agenda.kategori-masuk.index');
        Route::get('/buku-agenda/kategori-keluar', [KategoriKeluarController::class, 'index'])->name('buku-agenda.kategori-keluar.index');
        
        // Routes yang dibatasi untuk monitor - export
        Route::get('/buku-agenda/export', [BukuAgendaController::class, 'export'])->name('buku-agenda.export')->middleware('preventMonitor');
        Route::get('/buku-agenda/kategori-keluar/export', [KategoriKeluarController::class, 'export'])
            ->name('buku-agenda.kategori-keluar.export')->middleware('preventMonitor');
        Route::get('/buku-agenda/kategori-keluar/export-pdf', [KategoriKeluarController::class, 'exportPDF'])
            ->name('buku-agenda.kategori-keluar.export-pdf')->middleware('preventMonitor');
        Route::get('/buku-agenda/export-pdf', [BukuAgendaController::class, 'exportPDF'])->name('buku-agenda.export-pdf')->middleware('preventMonitor');

        // Routes SK KARO (Opsi B: Menu Utama Independen)
        Route::get('sk-karo', [SkKaroController::class, 'index'])->name('sk-karo.index');
        Route::get('sk-karo/create', [SkKaroController::class, 'create'])->name('sk-karo.create')->middleware('preventMonitor');
        Route::get('sk-karo-export', [SkKaroController::class, 'export'])->name('sk-karo.export');
        Route::post('sk-karo', [SkKaroController::class, 'store'])->name('sk-karo.store')->middleware('preventMonitor');
        Route::get('sk-karo/{id}/edit', [SkKaroController::class, 'edit'])->name('sk-karo.edit')->middleware('preventMonitor');
        Route::put('sk-karo/{id}', [SkKaroController::class, 'update'])->name('sk-karo.update')->middleware('preventMonitor');
        Route::delete('sk-karo/{id}', [SkKaroController::class, 'destroy'])->name('sk-karo.destroy')->middleware('preventMonitor');
    });

    Route::get('/profile', [UserProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [UserProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/avatar', [UserProfileController::class, 'updateAvatar'])->name('profile.avatar');

    Route::get('/profile/history', [UserProfileController::class, 'history'])->name('profile.history');
    Route::get('/profile/activity', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('profile.activity');
    Route::get('/profile/login-history', [UserProfileController::class, 'loginHistory'])->name('profile.login-history');
    Route::get('/profile/logout-history', [UserProfileController::class, 'logoutHistory'])->name('profile.logout-history');
    Route::get('/profile/avatar', [UserProfileController::class, 'avatar'])->name('profile.avatar');
    Route::post('/profile/avatar', [UserProfileController::class, 'updateAvatar'])->name('profile.update-avatar');
    Route::get('/profile/password', [UserProfileController::class, 'password'])->name('profile.password');
    Route::post('/profile/password', [UserProfileController::class, 'updatePassword'])->name('profile.update-password');

    Route::middleware(['checkRole:admin,superadmin,monitor'])->group(function () {
        Route::get('/draft-phd/sk', [SKController::class, 'index'])->name('draft-phd.sk.index');

        Route::get('/draft-phd/perda', [PerdaController::class, 'index'])->name('draft-phd.perda.index');

        Route::put('/draft-phd/perda/{perda}/update-status', [PerdaController::class, 'updateStatus'])->name('draft-phd.perda.update-status')->middleware('preventMonitor');
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

        Route::post('/update-disposisi/{id}', [SuratMasukController::class, 'updateDisposisi'])->middleware('preventMonitor');
        Route::post('/surat-masuk/{id}/update-catatan', [SuratMasukController::class, 'updateCatatan'])->name('surat-masuk.update-catatan')->middleware('preventMonitor');
        
        Route::put('sppd-dalam-daerah/{id}', [SppdDalamDaerahController::class, 'update'])->name('sppd-dalam-daerah.update')->middleware('preventMonitor');

        //Route perda - Routes untuk semua role (termasuk monitor) - hanya view
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

        //Route pergub - Routes untuk semua role (termasuk monitor) - hanya view
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

        // Routes untuk semua role (termasuk monitor) - hanya view
        Route::get('/disposisi', [DisposisiController::class, 'index'])->name('disposisi.index');
        Route::get('/disposisi/{id}', [DisposisiController::class, 'detail'])->name('disposisi.detail');
        
        // Routes yang dibatasi untuk monitor
        Route::put('/disposisi/{id}', [DisposisiController::class, 'update'])->name('disposisi.update')->middleware('preventMonitor');
        Route::get('/disposisi/{id}/edit', [DisposisiController::class, 'edit'])->name('disposisi.edit')->middleware('preventMonitor');
        Route::get('/surat-masuk/{id}/status', [SuratMasukController::class, 'status'])->name('surat-masuk.status')->middleware('preventMonitor');
        Route::post('/surat-masuk/{id}/update-status', [SuratMasukController::class, 'updateStatus'])->name('surat-masuk.update-status')->middleware('preventMonitor');
        Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');
        Route::post('/surat-masuk/{id}/disposisi', [SuratMasukController::class, 'disposisi'])
            ->name('surat-masuk.disposisi')->middleware('preventMonitor');
        Route::post('/draft-phd/sk/{id}/disposisi', [SKController::class, 'disposisi'])
            ->name('draft-phd.sk.disposisi')->middleware('preventMonitor');
    });

    // User Management Routes - untuk admin (terbatas) dan superadmin (penuh)
    Route::resource('users', UserController::class)->middleware('checkRole:admin,superadmin');
    Route::put('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword')->middleware('checkRole:admin,superadmin');
    Route::resource('roles', RoleController::class)->middleware('checkRole:superadmin');

    // Transaksi Surat - hanya untuk user (bukan monitor)
    Route::get('/transaksi-surat', [TransaksiSuratController::class, 'index'])->name('transaksi-surat.index')->middleware('checkRole:user,admin');

    // Data Request Routes - index dan detail untuk semua role (termasuk monitor), create/store/cancel hanya untuk user dan admin
    Route::get('data-requests', [DataRequestController::class, 'index'])->name('data-requests.index');
    Route::get('data-requests/{dataRequest}', [DataRequestController::class, 'show'])->name('data-requests.show');
    Route::get('data-requests/create', [DataRequestController::class, 'create'])->name('data-requests.create')->middleware('checkRole:user,admin');
    Route::post('data-requests', [DataRequestController::class, 'store'])->name('data-requests.store')->middleware('checkRole:user,admin');
    Route::get('data-requests/{dataRequest}/edit', [DataRequestController::class, 'edit'])->name('data-requests.edit');
    Route::put('data-requests/{dataRequest}', [DataRequestController::class, 'update'])->name('data-requests.update');
    Route::delete('/data-requests/{dataRequest}/cancel', [DataRequestController::class, 'cancel'])->name('data-requests.cancel')->middleware('checkRole:user,admin');

});

// API Route untuk detail surat masuk JSON
Route::get('/api/surat-masuk/{id}', [App\Http\Controllers\SuratMasukController::class, 'apiShow']);

// API Route untuk detail perda JSON
Route::get('/api/perda/{id}', [App\Http\Controllers\PerdaController::class, 'apiShow']);

// API Route untuk detail pergub JSON
Route::get('/api/pergub/{id}', [App\Http\Controllers\PergubController::class, 'apiShow']);

// User Routes
Route::middleware(['auth', 'checkRole:user'])->group(function () {
    // Transaksi Surat routes
    Route::get('/transaksi-surat', [TransaksiSuratController::class, 'index'])->name('transaksi-surat.index');
});

// Approval Request Routes
Route::get('/transaksi-surat/request-approval', [TransaksiSuratController::class, 'showRequestForm'])
    ->name('transaksi-surat.request-form')
    ->middleware(['auth', 'checkRole:user']);

Route::post('/transaksi-surat/request-approval', [TransaksiSuratController::class, 'requestApproval'])
    ->name('transaksi-surat.request-approval')
    ->middleware(['auth', 'checkRole:user']);

Route::post('/transaksi-surat/approve/{id}', [TransaksiSuratController::class, 'approveRequest'])
    ->name('transaksi-surat.approve')
    ->middleware(['auth', 'checkRole:admin']);

Route::post('/transaksi-surat/reject/{id}', [TransaksiSuratController::class, 'rejectRequest'])
    ->name('transaksi-surat.reject')
    ->middleware(['auth', 'checkRole:admin']);

// Admin Routes
Route::middleware(['auth', 'checkRole:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Approval Requests
    Route::get('/approval-requests', [ApprovalRequestController::class, 'index'])
        ->name('approval-requests.index');
    Route::post('/approval-requests/{id}/approve', [ApprovalRequestController::class, 'approve'])
        ->name('approval-requests.approve');
    Route::post('/approval-requests/{id}/reject', [ApprovalRequestController::class, 'reject'])
        ->name('approval-requests.reject');
    Route::post('/approval-requests', [ApprovalRequestController::class, 'store'])->name('approval-requests.store');
    Route::post('/approval-requests/{id}/toggle-fisik', [ApprovalRequestController::class, 'toggleFisik'])->name('admin.approval-requests.toggle-fisik');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/read/{id}', [App\Http\Controllers\NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read.all');
});

