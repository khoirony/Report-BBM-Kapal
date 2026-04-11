<?php

use App\Http\Controllers\PdfController;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Dashboard\NahkodaDashboard;
use App\Livewire\Dashboard\PengawasDashboard;
use App\Livewire\Dashboard\PenyediaDashboard;
use App\Livewire\Dashboard\SatgasDashboard;
use App\Livewire\Dashboard\SoundingDashboard;
use App\Livewire\Dashboard\SuperAdminDashboard;
// Asumsi import komponen baru (sesuaikan jika file belum ada)
use App\Livewire\Dashboard\AdminUkpdDashboard; 
use App\Livewire\Dashboard\PptkDashboard;
use App\Livewire\Dashboard\KepalaUkpdDashboard;
// ... (import livewire lainnya tetap sama) ...
use App\Livewire\Penyedia\PesananMasukBBM;
use App\Livewire\Satgas\BeritaAcaraLaporanPengisian;
use App\Livewire\Satgas\LaporanPengisianBBM;
use App\Livewire\Satgas\LaporanSisaBBM;
use App\Livewire\Satgas\SuratPermohonanPengisianBBM;
use App\Livewire\Satgas\SuratTugasPengisianBBM;
use App\Livewire\Sounding\SoundingBBM;
use App\Livewire\SuperAdmin\DataKapal;
use App\Livewire\SuperAdmin\KelolaUser;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    // UPDATE: Gunakan relasi role->slug
    return match (auth()->user()?->role?->slug->slug) {
        'superadmin'  => redirect()->route('dashboard.superadmin'),
        'admin_ukpd'  => redirect()->route('dashboard.admin_ukpd'), // Tambahan Baru
        'sounding'    => redirect()->route('dashboard.sounding'),
        'satgas'      => redirect()->route('dashboard.satgas'),
        'pengawas'    => redirect()->route('dashboard.pengawas'),
        'pptk'        => redirect()->route('dashboard.pptk'),       // Tambahan Baru
        'kepala_ukpd' => redirect()->route('dashboard.kepala_ukpd'),// Tambahan Baru
        'nahkoda'     => redirect()->route('dashboard.nahkoda'),
        'penyedia'    => redirect()->route('dashboard.penyedia'),
        default       => abort(403, 'Unauthorized action.'),
    };
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
});

// ====================================================================
// ROUTE VERIFIKASI EMAIL
// ====================================================================
Route::middleware('auth')->group(function () {
    
    Route::get('/email/verify', function () {
        return view('auth.verify-email'); 
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        // UPDATE: Gunakan role->slug untuk redirect
        return redirect('/dashboard-' . str_replace('_', '-', auth()->user()?->role?->slug->slug)); 
    })->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    })->middleware('throttle:6,1')->name('verification.send');
    
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');

    // ====================================================================
    // ROUTE UTAMA: VERIFIED
    // ====================================================================
    Route::middleware('verified')->group(function () {
        
        // --- DASHBOARD (Spesifik per Role) ---
        Route::middleware('role:superadmin')->group(function () {
            Route::get('/dashboard-superadmin', SuperAdminDashboard::class)->name('dashboard.superadmin');
            Route::get('/kelola-user', KelolaUser::class)->name('superadmin.kelola-user');
        });
        
        // Tambahan Dashboard untuk Role Baru
        Route::middleware('role:admin_ukpd')->get('/dashboard-admin-ukpd', AdminUkpdDashboard::class)->name('dashboard.admin_ukpd');
        Route::middleware('role:pptk')->get('/dashboard-pptk', PptkDashboard::class)->name('dashboard.pptk');
        Route::middleware('role:kepala_ukpd')->get('/dashboard-kepala-ukpd', KepalaUkpdDashboard::class)->name('dashboard.kepala_ukpd');
        
        Route::middleware('role:sounding')->get('/dashboard-sounding', SoundingDashboard::class)->name('dashboard.sounding');
        Route::middleware('role:satgas')->get('/dashboard-satgas', SatgasDashboard::class)->name('dashboard.satgas');
        Route::middleware('role:penyedia')->get('/dashboard-penyedia', PenyediaDashboard::class)->name('dashboard.penyedia');
        Route::middleware('role:penyedia')->get('/kelola-permohonan', SuratPermohonanPengisianBBM::class)->name('penyedia.surat-permohonan');
        Route::middleware('role:nahkoda')->get('/dashboard-nahkoda', NahkodaDashboard::class)->name('dashboard.nahkoda');
        Route::middleware('role:pengawas')->get('/dashboard-pengawas', PengawasDashboard::class)->name('dashboard.pengawas');

        // --- FITUR SATGAS, ADMIN UKPD, & SUPERADMIN ---
        // (Saya tambahkan admin_ukpd ke akses data kapal sesuai ketentuanmu sebelumnya)
        Route::middleware('role:superadmin,satgas,admin_ukpd')->group(function () {
            Route::get('/data-kapal', DataKapal::class)->name('data-kapal');
            Route::get('/laporan-sisa-bbm', LaporanSisaBBM::class)->name('satgas.laporan-sisa-bbm');
            Route::get('/surat-tugas', SuratTugasPengisianBBM::class)->name('satgas.surat-tugas');
            Route::get('/surat-permohonan', SuratPermohonanPengisianBBM::class)->name('satgas.surat-permohonan');

            Route::get('/pencatatan-pengisian', BeritaAcaraLaporanPengisian::class)->name('satgas.pencatatan-pengisian');
            Route::get('/laporan-pengisian', LaporanPengisianBBM::class)->name('satgas.laporan-pengisian');
            Route::get('/berita-acara-pengisian', BeritaAcaraLaporanPengisian::class)->name('satgas.berita-acara-pengisian');
        });

        // --- FITUR SOUNDING & SUPERADMIN ---
        Route::middleware('role:superadmin,sounding')->group(function () {
            Route::get('/sounding-bbm', SoundingBBM::class)->name('sounding.sounding-bbm');
        });

        // --- FITUR PENYEDIA & SUPERADMIN ---
        Route::middleware('role:superadmin,penyedia')->group(function () {
            Route::get('/pesanan-bbm', PesananMasukBBM::class)->name('penyedia.pesanan-bbm');
        });

        // --- CETAK PDF ---
        Route::get('/laporan-bbm/{id}/pdf', [PdfController::class, 'previewLaporan'])->name('laporan.pdf.preview');
        Route::get('/surat-tugas/{id}/pdf', [PdfController::class, 'previewSuratTugas'])->name('surattugas.pdf.preview');
        Route::get('/laporan-sisa-bbm/{id}/pdf', [PdfController::class, 'previewLaporanSisaBbm'])->name('laporan-sisa-bbm.pdf.preview');
    });
});