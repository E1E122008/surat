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
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::resource('surat-masuk', SuratMasukController::class);
    Route::get('surat-masuk-export', [SuratMasukController::class, 'export'])
        ->name('surat-masuk.export');

    Route::resource('surat-keluar', SuratKeluarController::class);
    Route::get('surat-keluar-export', [SuratKeluarController::class, 'export'])
        ->name('surat-keluar.export');

    Route::resource('sppd-dalam-daerah', SppdDalamDaerahController::class);
    Route::get('sppd-dalam-daerah/{sppdDalamDaerah}/print', [SppdDalamDaerahController::class, 'print'])
        ->name('sppd-dalam-daerah.print');
    Route::get('sppd-dalam-daerah-export', [SppdDalamDaerahController::class, 'export'])
        ->name('sppd-dalam-daerah.export');

    Route::resource('sppd-luar-daerah', SppdLuarDaerahController::class);
    Route::get('sppd-luar-daerah/{sppdLuarDaerah}/print', [SppdLuarDaerahController::class, 'print'])
        ->name('sppd-luar-daerah.print');
    Route::get('sppd-luar-daerah-export', [SppdLuarDaerahController::class, 'export'])
        ->name('sppd-luar-daerah.export');

    Route::resource('spt-dalam-daerah', SptDalamDaerahController::class);
    Route::get('spt-dalam-daerah/{sptDalamDaerah}/print', [SptDalamDaerahController::class, 'print'])
        ->name('spt-dalam-daerah.print');
    Route::get('spt-dalam-daerah-export', [SptDalamDaerahController::class, 'export'])
        ->name('spt-dalam-daerah.export');

    Route::resource('spt-luar-daerah', SptLuarDaerahController::class);
    Route::get('spt-luar-daerah/{sptLuarDaerah}/print', [SptLuarDaerahController::class, 'print'])
        ->name('spt-luar-daerah.print');
    Route::get('spt-luar-daerah-export', [SptLuarDaerahController::class, 'export'])
        ->name('spt-luar-daerah.export');

    Route::get('/buku-agenda', [BukuAgendaController::class, 'index'])->name('buku-agenda.index');

    Route::get('/profile', [UserProfileController::class, 'index'])->name('profile');


});

Route::get('/draft-phd/sk', [SKController::class, 'index'])->name('draft-phd.sk.index');

Route::get('/draft-phd/perda', [PerdaController::class, 'perdaIndex'])->name('draft-phd.perda.index');

Route::get('/draft-phd/pergub', [PergubController::class, 'pergubIndex'])->name('draft-phd.pergub.index');

// Rute untuk menampilkan form tambah SK
Route::get('/draft-phd/sk/create', [SKController::class, 'create'])->name('draft-phd.sk.create');

// Rute untuk menyimpan SK
Route::post('/draft-phd/sk', [SKController::class, 'store'])->name('draft-phd.sk.store');

// Rute untuk mengekspor SK
Route::get('/draft-phd/sk/export', [SKController::class, 'export'])->name('draft-phd.sk.export');

// Rute untuk menampilkan form edit SK
Route::get('/draft-phd/sk/{sk}/edit', [SKController::class, 'edit'])->name('draft-phd.sk.edit');

// Rute untuk memperbarui SK
Route::put('/draft-phd/sk/{sk}', [SKController::class, 'update'])->name('draft-phd.sk.update');

// Rute untuk menghapus SK
Route::delete('/draft-phd/sk/{id}', [SKController::class, 'destroy'])->name('draft-phd.sk.destroy');

Route::post('/update-disposisi/{id}', [SuratMasukController::class, 'updateDisposisi']);

Route::post('/surat-masuk/{id}/update-catatan', [SuratMasukController::class, 'updateCatatan'])->name('surat-masuk.update-catatan');

Route::post('/draft-phd/sk/{id}/update-catatan', [SKController::class, 'updateCatatan']);
