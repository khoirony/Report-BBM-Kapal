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
use App\Livewire\Satgas\BeritaAcaraLaporanPengisian;
use App\Livewire\Satgas\LaporanBBMSebelumPengisian;
use App\Livewire\Satgas\LaporanPengisianBBM;
use App\Livewire\Satgas\LaporanSisaBBM;
use App\Livewire\Satgas\SuratPermohonanPengisianBBM;
use App\Livewire\Satgas\SuratTugasPengisianBBM;
use App\Livewire\Sounding\SoundingBBM;
use App\Livewire\SuperAdmin\DataKapal;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    return match (auth()->user()->role) {
        'superadmin' => redirect()->route('dashboard.superadmin'),
        'sounding'   => redirect()->route('dashboard.sounding'),
        'satgas'     => redirect()->route('dashboard.satgas'),
        'penyedia'   => redirect()->route('dashboard.penyedia'),
        'nahkoda'    => redirect()->route('dashboard.nahkoda'),
        'pengawas'   => redirect()->route('dashboard.pengawas'),
        default      => abort(403, 'Unauthorized action.'),
    };
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
});

// ====================================================================
// ROUTE VERIFIKASI EMAIL (Hanya bisa diakses jika sudah login)
// ====================================================================
Route::middleware('auth')->group(function () {
    
    // 1. Menampilkan halaman info "Cek email Anda"
    Route::get('/email/verify', function () {
        return view('auth.verify-email'); 
    })->name('verification.notice');

    // 2. Handler ketika user mengklik tombol/link di dalam email
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        // Redirect sesuai role setelah verifikasi berhasil
        return redirect('/dashboard-' . auth()->user()->role); 
    })->middleware('signed')->name('verification.verify');

    // 3. Handler untuk tombol "Kirim Ulang Email Verifikasi" (Resend)
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    })->middleware('throttle:6,1')->name('verification.send');
    
    // --- LOGOUT ---
    // (Diletakkan di sini agar user yang belum verifikasi tetap bisa logout)
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');

    // ====================================================================
    // ROUTE UTAMA: HANYA BISA DIAKSES JIKA SUDAH LOGIN & SUDAH VERIFIKASI
    // ====================================================================
    Route::middleware('verified')->group(function () {
        
        // --- DASHBOARD (Spesifik per Role) ---
        Route::middleware('role:superadmin')->get('/dashboard-superadmin', SuperAdminDashboard::class)->name('dashboard.superadmin');
        Route::middleware('role:sounding')->get('/dashboard-sounding', SoundingDashboard::class)->name('dashboard.sounding');
        Route::middleware('role:satgas')->get('/dashboard-satgas', SatgasDashboard::class)->name('dashboard.satgas');
        Route::middleware('role:penyedia')->get('/dashboard-penyedia', PenyediaDashboard::class)->name('dashboard.penyedia');
        Route::middleware('role:penyedia')->get('/kelola-permohonan', SuratPermohonanPengisianBBM::class)->name('penyedia.surat-permohonan');
        Route::middleware('role:nahkoda')->get('/dashboard-nahkoda', NahkodaDashboard::class)->name('dashboard.nahkoda');
        Route::middleware('role:pengawas')->get('/dashboard-pengawas', PengawasDashboard::class)->name('dashboard.pengawas');

        // --- FITUR SATGAS & SUPERADMIN ---
        Route::middleware('role:superadmin,satgas')->group(function () {
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

        // --- CETAK PDF (Dilindungi Auth agar tidak bocor) ---
        Route::get('/laporan-bbm/{id}/pdf', [PdfController::class, 'previewLaporan'])->name('laporan.pdf.preview');
        Route::get('/surat-tugas/{id}/pdf', [PdfController::class, 'previewSuratTugas'])->name('surattugas.pdf.preview');
        Route::get('/laporan-sisa-bbm/{id}/pdf', [PdfController::class, 'previewLaporanSisaBbm'])->name('laporan-sisa-bbm.pdf.preview');
    });
});