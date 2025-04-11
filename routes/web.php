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


Route::get('/', function () {
    return view('welcome');
});

Route::resource('surat-masuk', SuratMasukController::class);

Route::get('surat-masuk/export', [SuratMasukController::class, 'export'])->name('surat-masuk.export');

Route::get('surat-keluar/export', [SuratKeluarController::class, 'export'])->name('surat-keluar.export');

Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout')
    ->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::resource('surat-masuk', SuratMasukController::class);
    Route::get('surat-masuk/{id}/status', [SuratMasukController::class, 'status'])->name('surat-masuk.status');
    Route::get('surat-masuk/{id}/detail', [SuratMasukController::class, 'detail'])->name('surat-masuk.detail'); 
    Route::get('surat-masuk/{id}/edit', [SuratMasukController::class, 'edit'])->name('surat-masuk.edit');
    Route::post('/surat-masuk/{id}/update-status', [SuratMasukController::class, 'updateStatus'])
        ->name('surat-masuk.update-status');
    Route::delete('surat-masuk/{suratMasuk}', [SuratMasukController::class, 'destroy'])
        ->name('surat-masuk.destroy');
    Route::get('surat-masuk-export', [SuratMasukController::class, 'export'])
        ->name('surat-masuk.export');

    Route::resource('surat-keluar', SuratKeluarController::class);
    Route::get('surat-keluar/{id}/edit', [SuratKeluarController::class, 'edit'])->name('surat-keluar.edit');
    Route::put('surat-keluar/{id}', [SuratKeluarController::class, 'update'])
        ->name('surat-keluar.update');
    Route::get('surat-keluar-export', [SuratKeluarController::class, 'export'])
        ->name('surat-keluar.export');

    Route::resource('sppd-dalam-daerah', SppdDalamDaerahController::class)->except(['show']);
    Route::get('sppd-dalam-daerah/create', [SppdDalamDaerahController::class, 'create'])
        ->name('sppd-dalam-daerah.create');
    Route::get('sppd-dalam-daerah/{id}/detail', [SppdDalamDaerahController::class, 'detail'])
        ->name('sppd-dalam-daerah.detail');
    Route::get('sppd-dalam-daerah/{id}/edit', [SppdDalamDaerahController::class, 'edit'])
        ->name('sppd-dalam-daerah.edit');
    Route::put('sppd-dalam-daerah/{id}', [SppdDalamDaerahController::class, 'update'])
        ->name('sppd-dalam-daerah.update');
    Route::delete('sppd-dalam-daerah/{id}', [SppdDalamDaerahController::class, 'destroy'])
        ->name('sppd-dalam-daerah.destroy');
    Route::get('sppd-dalam-daerah/{sppdDalamDaerah}/print', [SppdDalamDaerahController::class, 'print'])
        ->name('sppd-dalam-daerah.print');
    Route::get('sppd-dalam-daerah-export', [SppdDalamDaerahController::class, 'export'])
        ->name('sppd-dalam-daerah.export');

    Route::resource('sppd-luar-daerah', SppdLuarDaerahController::class);
    Route::get('sppd-luar-daerah/create', [SppdLuarDaerahController::class, 'create'])
        ->name('sppd-luar-daerah.create');
    Route::get('/sppd-luar-daerah/{id}/detail', [SppdLuarDaerahController::class, 'detail'])->name('sppd-luar-daerah.detail');
    Route::get('/sppd-luar-daerah/{sppdLuarDaerah}/edit', [SppdLuarDaerahController::class, 'edit'])
        ->name('sppd-luar-daerah.edit');
    Route::put('sppd-luar-daerah/{id}', [SppdLuarDaerahController::class, 'update'])
        ->name('sppd-luar-daerah.update');
    Route::get('sppd-luar-daerah/{sppdLuarDaerah}/print', [SppdLuarDaerahController::class, 'print'])
        ->name('sppd-luar-daerah.print');
    Route::get('sppd-luar-daerah-export', [SppdLuarDaerahController::class, 'export'])
        ->name('sppd-luar-daerah.export');

    Route::resource('spt-dalam-daerah', SptDalamDaerahController::class);
    Route::get('spt-dalam-daerah/{id}/detail', [SptDalamDaerahController::class, 'detail'])
        ->name('spt-dalam-daerah.detail');
    Route::get('spt-dalam-daerah/{sptDalamDaerah}/edit', [SptDalamDaerahController::class, 'edit'])
        ->name('spt-dalam-daerah.edit');
    Route::put('spt-dalam-daerah/{sptDalamDaerah}', [SptDalamDaerahController::class, 'update'])
        ->name('spt-dalam-daerah.update');
    Route::get('spt-dalam-daerah/{sptDalamDaerah}/print', [SptDalamDaerahController::class, 'print'])
        ->name('spt-dalam-daerah.print');
    Route::get('spt-dalam-daerah-export', [SptDalamDaerahController::class, 'export'])
        ->name('spt-dalam-daerah.export');

    Route::resource('spt-luar-daerah', SptLuarDaerahController::class);
    Route::get('spt-luar-daerah/{id}/detail', [SptLuarDaerahController::class, 'detail'])
        ->name('spt-luar-daerah.detail');
    Route::get('spt-luar-daerah/{id}/edit', [SptLuarDaerahController::class, 'edit'])
        ->name('spt-luar-daerah.edit');
    Route::put('spt-luar-daerah/{sptLuarDaerah}', [SptLuarDaerahController::class, 'update'])
        ->name('spt-luar-daerah.update');
    Route::delete('spt-luar-daerah/{sptLuarDaerah}', [SptLuarDaerahController::class, 'destroy'])
        ->name('spt-luar-daerah.destroy');
    Route::get('spt-luar-daerah-export', [SptLuarDaerahController::class, 'export'])
        ->name('spt-luar-daerah.export');

    Route::get('/buku-agenda', [BukuAgendaController::class, 'index'])->name('buku-agenda.index');
    Route::get('/buku-agenda/export', [BukuAgendaController::class, 'export'])->name('buku-agenda.export');
    Route::get('/buku-agenda/kategori-masuk', [BukuAgendaController::class, 'kategoriMasuk'])->name('buku-agenda.kategori-masuk.index');
    Route::get('/buku-agenda/kategori-keluar', [KategoriKeluarController::class, 'index'])->name('buku-agenda.kategori-keluar.index');
    Route::get('/buku-agenda/kategori-keluar/export', [KategoriKeluarController::class, 'export'])
        ->name('buku-agenda.kategori-keluar.export');
    Route::get('/buku-agenda/kategori-keluar/export-pdf', [KategoriKeluarController::class, 'exportPDF'])
        ->name('buku-agenda.kategori-keluar.export-pdf');
    Route::get('/buku-agenda/export-pdf', [BukuAgendaController::class, 'exportPDF'])->name('buku-agenda.export-pdf');
    Route::get('/profile', [UserProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [UserProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/avatar', [UserProfileController::class, 'updateAvatar'])->name('profile.avatar');

    Route::get('/profile/history', [UserProfileController::class, 'history'])->name('profile.history');
    Route::get('/profile/activity', [UserProfileController::class, 'activity'])->name('profile.activity');
    Route::get('/profile/login-history', [UserProfileController::class, 'loginHistory'])->name('profile.login-history');
    Route::get('/profile/logout-history', [UserProfileController::class, 'logoutHistory'])->name('profile.logout-history');
    Route::get('/profile/avatar', [UserProfileController::class, 'avatar'])->name('profile.avatar');
    Route::post('/profile/avatar', [UserProfileController::class, 'updateAvatar'])->name('profile.update-avatar');
    Route::get('/profile/password', [UserProfileController::class, 'password'])->name('profile.password');
    Route::post('/profile/password', [UserProfileController::class, 'updatePassword'])->name('profile.update-password');

    Route::get('/draft-phd/sk', [SKController::class, 'index'])->name('draft-phd.sk.index');

    Route::get('/draft-phd/perda', [PerdaController::class, 'perdaIndex'])->name('draft-phd.perda.index');
    Route::get('/draft-phd/perda/{perda}/status', [PerdaController::class, 'status'])->name('draft-phd.perda.status');
    Route::put('/draft-phd/perda/{perda}/update-status', [PerdaController::class, 'updateStatus'])->name('draft-phd.perda.update-status');
    Route::get('/draft-phd/pergub', [PergubController::class, 'pergubIndex'])->name('draft-phd.pergub.index');

    // Rute untuk menampilkan form tambah SK
    Route::get('/draft-phd/sk/create', [SKController::class, 'create'])->name('draft-phd.sk.create');
    Route::post('/draft-phd/sk', [SKController::class, 'store'])->name('draft-phd.sk.store');
    Route::get('/draft-phd/sk/{sk}/status', [SKController::class, 'status'])->name('draft-phd.sk.status');
    Route::post('/sk/update-status/{id}', [SKController::class, 'updateStatus'])
        ->name('sk.update-status');
    Route::get('/draft-phd/sk/{sk}/detail', [SKController::class, 'detail'])->name('draft-phd.sk.detail');
    Route::get('/draft-phd/sk/export', [SKController::class, 'export'])->name('draft-phd.sk.export');
    Route::get('/draft-phd/sk/{sk}/edit', [SKController::class, 'edit'])->name('draft-phd.sk.edit');
    Route::put('/draft-phd/sk/{sk}', [SKController::class, 'update'])->name('draft-phd.sk.update');
    Route::delete('/draft-phd/sk/{sk}', [SKController::class, 'destroy'])->name('draft-phd.sk.destroy');
    Route::post('/draft-phd/sk/{id}/update-catatan', [SKController::class, 'updateCatatan']);

    Route::post('/update-disposisi/{id}', [SuratMasukController::class, 'updateDisposisi']);
    Route::post('/surat-masuk/{id}/update-catatan', [SuratMasukController::class, 'updateCatatan'])->name('surat-masuk.update-catatan');
    
    Route::put('sppd-dalam-daerah/{id}', [SppdDalamDaerahController::class, 'update'])->name('sppd-dalam-daerah.update');

    //Route perda
    Route::get('/draft-phd/perda', [PerdaController::class, 'index'])->name('draft-phd.perda.index');
    Route::get('/draft-phd/perda/create', [PerdaController::class, 'create'])->name('draft-phd.perda.create');
    Route::get('/draft-phd/perda/{perda}/detail', [PerdaController::class, 'detail'])->name('draft-phd.perda.detail');
    Route::get('/draft-phd/perda/{perda}/edit', [PerdaController::class, 'edit'])->name('draft-phd.perda.edit');
    Route::put('/draft-phd/perda/{perda}', [PerdaController::class, 'update'])->name('draft-phd.perda.update');
    Route::delete('/draft-phd/perda/{perda}', [PerdaController::class, 'destroy'])->name('draft-phd.perda.destroy');
    Route::post('/draft-phd/perda/{id}/update-catatan', [PerdaController::class, 'updateCatatan']);
    Route::post('/draft-phd/perda', [PerdaController::class, 'store'])->name('draft-phd.perda.store');
    Route::get('/draft-phd/perda/export', [PerdaController::class, 'export'])->name('draft-phd.perda.export');
    Route::put('/draft-phd/perda/{id}/update-status', [PerdaController::class, 'updateStatus'])
        ->name('draft-phd.perda.update-status');
    Route::post('/draft-phd/perda/{id}/disposisi', [PerdaController::class, 'disposisi'])
        ->name('draft-phd.perda.disposisi');
    Route::post('/draft-phd/perda/{id}/update-disposisi', [PerdaController::class, 'updateDisposisi'])
        ->name('draft-phd.perda.update-disposisi');

    //Route pergub
    Route::get('/draft-phd/pergub', [PergubController::class, 'index'])->name('draft-phd.pergub.index');
    Route::get('/draft-phd/pergub/create', [PergubController::class, 'create'])->name('draft-phd.pergub.create');
    Route::get('/draft-phd/pergub/{pergub}/status', [PergubController::class, 'status'])->name('draft-phd.pergub.status');
    Route::post('/draft-phd/pergub/{id}/update-status', [PergubController::class, 'updateStatus'])
        ->name('draft-phd.pergub.update-status');
    Route::get('/draft-phd/pergub/{pergub}/detail', [PergubController::class, 'detail'])->name('draft-phd.pergub.detail');
    Route::get('/draft-phd/pergub/{pergub}/edit', [PergubController::class, 'edit'])->name('draft-phd.pergub.edit');
    Route::put('/draft-phd/pergub/{pergub}', [PergubController::class, 'update'])->name('draft-phd.pergub.update');
    Route::delete('/draft-phd/pergub/{pergub}', [PergubController::class, 'destroy'])->name('draft-phd.pergub.destroy');
    Route::post('/draft-phd/pergub/{id}/update-catatan', [PergubController::class, 'updateCatatan']);
    Route::post('/draft-phd/pergub', [PergubController::class, 'store'])->name('draft-phd.pergub.store');   
    Route::get('/draft-phd/pergub/export', [PergubController::class, 'export'])->name('draft-phd.pergub.export');
    Route::post('/draft-phd/pergub/{id}/disposisi', [PergubController::class, 'disposisi'])
        ->name('draft-phd.pergub.disposisi');
    Route::get('/pergub', [PergubController::class, 'index'])->name('pergub.index');


    Route::get('/disposisi', [DisposisiController::class, 'index'])->name('disposisi.index');
    Route::get('/disposisi/{id}', [DisposisiController::class, 'detail'])->name('disposisi.detail');
    Route::put('/disposisi/{id}', [DisposisiController::class, 'update'])->name('disposisi.update');
    Route::get('/disposisi/{id}/edit', [DisposisiController::class, 'edit'])->name('disposisi.edit');

    Route::get('/surat-masuk/{id}/status', [SuratMasukController::class, 'status'])->name('surat-masuk.status');
    Route::post('/surat-masuk/{id}/update-status', [SuratMasukController::class, 'updateStatus'])->name('surat-masuk.update-status');

    Route::get('/disposisi', [DisposisiController::class, 'index'])->name('disposisi.index');
    Route::get('/disposisi/{id}', [DisposisiController::class, 'detail'])->name('disposisi.detail');
    Route::put('/disposisi/{id}', [DisposisiController::class, 'update'])->name('disposisi.update');
    Route::get('/disposisi/{id}/edit', [DisposisiController::class, 'edit'])->name('disposisi.edit');

    Route::get('/surat-masuk/{id}/status', [SuratMasukController::class, 'status'])->name('surat-masuk.status');
    Route::post('/surat-masuk/{id}/update-status', [SuratMasukController::class, 'updateStatus'])->name('surat-masuk.update-status');

    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');

    Route::post('/surat-masuk/{id}/disposisi', [SuratMasukController::class, 'disposisi'])
        ->name('surat-masuk.disposisi');

    Route::post('/draft-phd/sk/{id}/disposisi', [SKController::class, 'disposisi'])
        ->name('draft-phd.sk.disposisi');

    // User Management Routes
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);

    Route::get('/transaksi-surat', [TransaksiSuratController::class, 'index'])->name('transaksi-surat.index');

});

// User Routes
Route::middleware(['auth', 'checkRole:user'])->group(function () {
    
    
    // Transaksi Surat routes
    Route::get('/transaksi-surat', [TransaksiSuratController::class, 'index'])->name('transaksi-surat.index');
});

// Approval Request Routes
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
});