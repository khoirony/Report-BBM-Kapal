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
use App\Livewire\Dashboard\AdminUkpdDashboard; 
use App\Livewire\Dashboard\PptkDashboard;
use App\Livewire\Dashboard\KepalaUkpdDashboard;
use App\Livewire\Nahkoda\PencatatanHasilPengisian;
use App\Livewire\Penyedia\InvoiceManager;
use App\Livewire\Penyedia\PesananMasukBBM;
use App\Livewire\Satgas\BeritaAcaraLaporanPengisian;
use App\Livewire\Satgas\LaporanPengisianBBM;
use App\Livewire\Satgas\LaporanSisaBBM;
use App\Livewire\Satgas\SuratPermohonanPengisianBBM;
use App\Livewire\Satgas\SuratTugasPengisianBBM;
use App\Livewire\Satgas\SuratSpj; // <-- IMPORT BARU UNTUK SPJ
use App\Livewire\Satgas\VerifikasiInvoiceBBM;
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

    // UPDATE: Perbaikan pemanggilan relasi role->slug (sebelumnya ->slug->slug)
    return match (auth()->user()?->role?->slug) {
        'superadmin'  => redirect()->route('dashboard.superadmin'),
        'admin_ukpd'  => redirect()->route('dashboard.admin_ukpd'), 
        'sounding'    => redirect()->route('dashboard.sounding'),
        'satgas'      => redirect()->route('dashboard.satgas'),
        'pengawas'    => redirect()->route('dashboard.pengawas'),
        'pptk'        => redirect()->route('dashboard.pptk'),       
        'kepala_ukpd' => redirect()->route('dashboard.kepala_ukpd'),
        'nahkoda'     => redirect()->route('dashboard.nahkoda'),
        'penyedia'    => redirect()->route('dashboard.penyedia'),
        default       => abort(403, 'Unauthorized action.'),
    };
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    // Route::get('/register', Register::class)->name('register');
    // Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    // Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
});

// ====================================================================
// ROUTE VERIFIKASI EMAIL
// ====================================================================
Route::middleware('auth')->group(function () {
    
    // Route::get('/email/verify', function () {
    //     return view('auth.verify-email'); 
    // })->name('verification.notice');

    // Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    //     $request->fulfill();
    //     return redirect('/dashboard-' . str_replace('_', '-', auth()->user()?->role?->slug)); 
    // })->middleware('signed')->name('verification.verify');

    // Route::post('/email/verification-notification', function (Request $request) {
    //     $request->user()->sendEmailVerificationNotification();
    //     return back()->with('message', 'Verification link sent!');
    // })->middleware('throttle:6,1')->name('verification.send');
    
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');

    Route::middleware('verified')->group(function () {
        
        // --- DASHBOARD (Spesifik per Role) ---
        Route::middleware('role:superadmin')->group(function () {
            Route::get('/dashboard-superadmin', SuperAdminDashboard::class)->name('dashboard.superadmin');
            Route::get('/kelola-user', KelolaUser::class)->name('superadmin.kelola-user');
        });
        
        // Tambahan Dashboard untuk Role Baru
        Route::middleware('role:admin_ukpd')->get('/dashboard-admin-ukpd', AdminUkpdDashboard::class)->name('dashboard.admin_ukpd');
        Route::middleware('role:sounding')->get('/dashboard-sounding', SoundingDashboard::class)->name('dashboard.sounding');
        Route::middleware('role:satgas')->get('/dashboard-satgas', SatgasDashboard::class)->name('dashboard.satgas');
        Route::middleware('role:pengawas')->get('/dashboard-pengawas', PengawasDashboard::class)->name('dashboard.pengawas');
        Route::middleware('role:pptk')->get('/dashboard-pptk', PptkDashboard::class)->name('dashboard.pptk');
        Route::middleware('role:kepala_ukpd')->get('/dashboard-kepala-ukpd', KepalaUkpdDashboard::class)->name('dashboard.kepala_ukpd');
        Route::middleware('role:penyedia')->get('/dashboard-penyedia', PenyediaDashboard::class)->name('dashboard.penyedia');
        Route::middleware('role:penyedia')->get('/kelola-permohonan', SuratPermohonanPengisianBBM::class)->name('penyedia.surat-permohonan');
        Route::middleware('role:nahkoda')->get('/dashboard-nahkoda', NahkodaDashboard::class)->name('dashboard.nahkoda');

        // --- FITUR PENCATATAN HASIL (Akses Global untuk Kolaborasi) ---
        Route::middleware('role:superadmin,satgas,admin_ukpd,nahkoda,pengawas,penyedia,kepala_ukpd')->group(function () {
            Route::get('/pencatatan-pengisian', PencatatanHasilPengisian::class)->name('satgas.pencatatan-pengisian');
        });

        // --- FITUR SATGAS, ADMIN UKPD, & SUPERADMIN ---
        Route::middleware('role:superadmin,satgas,admin_ukpd')->group(function () {
            Route::get('/data-kapal', DataKapal::class)->name('data-kapal');
            Route::get('/laporan-sisa-bbm', LaporanSisaBBM::class)->name('satgas.laporan-sisa-bbm');
            Route::get('/surat-tugas', SuratTugasPengisianBBM::class)->name('satgas.surat-tugas');
            Route::get('/surat-permohonan', SuratPermohonanPengisianBBM::class)->name('satgas.surat-permohonan');

            Route::get('/laporan-pengisian', LaporanPengisianBBM::class)->name('satgas.laporan-pengisian');
            Route::get('/berita-acara-pengisian', BeritaAcaraLaporanPengisian::class)->name('satgas.berita-acara-pengisian');
        });

        Route::middleware('role:superadmin,satgas,admin_ukpd,pptk')->group(function () {
            Route::get('/verifikasi-tagihan', VerifikasiInvoiceBBM::class)->name('satgas.verifikasi-tagihan');
        });

        // --- FITUR SURAT SPJ (Bisa diakses oleh pembuat dan approver) ---
        // Penambahan role pptk & kepala_ukpd agar mereka bisa mengakses view dan approve
        Route::middleware('role:superadmin,satgas,admin_ukpd,pptk,kepala_ukpd')->group(function () {
            Route::get('/surat-spj', SuratSpj::class)->name('satgas.surat-spj');
        });

        // --- FITUR SOUNDING & SUPERADMIN ---
        Route::middleware('role:superadmin,sounding')->group(function () {
            Route::get('/sounding-bbm', SoundingBBM::class)->name('sounding.sounding-bbm');
        });

        // --- FITUR PENYEDIA & SUPERADMIN ---
        Route::middleware('role:superadmin,penyedia')->group(function () {
            Route::get('/pesanan-bbm', PesananMasukBBM::class)->name('penyedia.pesanan-bbm');
            Route::get('/tagihan-invoice', InvoiceManager::class)->name('penyedia.invoice-tagihan');
        });

        // --- CETAK PDF ---
        Route::get('/laporan-bbm/{id}/pdf', [PdfController::class, 'previewLaporan'])->name('laporan.pdf.preview');
        Route::get('/surat-tugas/{id}/pdf', [PdfController::class, 'previewSuratTugas'])->name('surattugas.pdf.preview');
        Route::get('/laporan-sisa-bbm/{id}/pdf', [PdfController::class, 'previewLaporanSisaBbm'])->name('laporan-sisa-bbm.pdf.preview');
    });
});