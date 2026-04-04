<?php

use App\Http\Controllers\PdfController;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Dashboard\NahkodaDashboard;
use App\Livewire\Dashboard\PengawasDashboard;
use App\Livewire\Dashboard\PenyediaDashboard;
use App\Livewire\Dashboard\SatgasDashboard;
use App\Livewire\Dashboard\SoundingDashboard;
use App\Livewire\Dashboard\SuperAdminDashboard;
use App\Livewire\Satgas\LaporanBBMSebelumPengisian;
use App\Livewire\Satgas\SuratPermohonanPengisianBBM;
use App\Livewire\Satgas\SuratTugasPengisianBBM;
use App\Livewire\Sounding\SoundingBBM;
use App\Livewire\SuperAdmin\DataKapal;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Jika belum login, arahkan ke halaman login
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    // Jika sudah login, ambil data user yang sedang aktif
    $user = auth()->user();
    $role = $user->role; 

    // Arahkan ke dashboard berdasarkan role menggunakan fitur match PHP 8
    return match ($role) {
        'superadmin' => redirect()->route('dashboard.superadmin'),
        'sounding' => redirect()->route('dashboard.sounding'),
        'satgas'   => redirect()->route('dashboard.satgas'),
        'penyedia' => redirect()->route('dashboard.penyedia'),
        'nahkoda'  => redirect()->route('dashboard.nahkoda'),
        'pengawas' => redirect()->route('dashboard.pengawas'),
        default    => abort(403, 'Unauthorized action.'),
    };
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

// Protected Routes (Contoh Dashboard untuk masing-masing role)
Route::middleware('auth')->group(function () {
    Route::middleware('role:superadmin')->group(function () {
        Route::get('/dashboard-superadmin', SuperAdminDashboard::class)->name('dashboard.superadmin');
        Route::get('/data-kapal', DataKapal::class)->name('data-kapal');
    });

    Route::middleware('role:sounding')->group(function () {
        Route::get('/dashboard-sounding', SoundingDashboard::class)->name('dashboard.sounding');
        Route::get('/sounding-bbm', SoundingBBM::class)->name('sounding.sounding-bbm');
    });

    Route::middleware('role:satgas')->group(function () {
        Route::get('/dashboard-satgas', SatgasDashboard::class)->name('dashboard.satgas');
        Route::get('/laporan-pengisian', LaporanBBMSebelumPengisian::class)->name('satgas.lapor-pengisian');
        Route::get('/surat-tugas', SuratTugasPengisianBBM::class)->name('satgas.surat-tugas');
        Route::get('/surat-permohonan', SuratPermohonanPengisianBBM::class)->name('satgas.surat-permohonan');
        Route::get('/data-kapal', DataKapal::class)->name('data-kapal');
    });

    Route::middleware('role:penyedia')->group(function () {
        Route::get('/dashboard-penyedia', PenyediaDashboard::class)->name('dashboard.penyedia');
    });

    Route::middleware('role:nahkoda')->group(function () {
        Route::get('/dashboard-nahkoda', NahkodaDashboard::class)->name('dashboard.nahkoda');
    });

    Route::middleware('role:pengawas')->group(function () {
        Route::get('/dashboard-pengawas', PengawasDashboard::class)->name('dashboard.pengawas');
    });
    
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});

Route::get('/laporan-bbm/{id}/pdf', [PdfController::class, 'previewLaporan'])->name('laporan.pdf.preview');
Route::get('/surat-tugas/{id}/pdf', [PdfController::class, 'previewSuratTugas'])->name('surattugas.pdf.preview');