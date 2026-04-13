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
use App\Livewire\Satgas\SuratSpj;
use App\Livewire\Satgas\VerifikasiInvoiceBBM;
use App\Livewire\Sounding\SoundingBBM;
use App\Livewire\SuperAdmin\DataKapal;
use App\Livewire\SuperAdmin\KelolaUser;
use App\Livewire\SuperAdmin\KelolaUkpd;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

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
// ROUTE VERIFIKASI EMAIL & AUTH
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
        // Route::get('/kelola-permohonan', SuratPermohonanPengisianBBM::class)->middleware('role:penyedia')->name('penyedia.surat-permohonan');
        
        // --- DASHBOARD (Spesifik per Role) ---
        Route::get('/dashboard-superadmin', SuperAdminDashboard::class)->middleware('role:superadmin')->name('dashboard.superadmin');
        Route::get('/dashboard-admin-ukpd', AdminUkpdDashboard::class)->middleware('role:admin_ukpd')->name('dashboard.admin-ukpd');
        Route::get('/dashboard-sounding', SoundingDashboard::class)->middleware('role:sounding')->name('dashboard.sounding');
        Route::get('/dashboard-satgas', SatgasDashboard::class)->middleware('role:satgas')->name('dashboard.satgas');
        Route::get('/dashboard-pengawas', PengawasDashboard::class)->middleware('role:pengawas')->name('dashboard.pengawas');
        Route::get('/dashboard-pptk', PptkDashboard::class)->middleware('role:pptk')->name('dashboard.pptk');
        Route::get('/dashboard-kepala-ukpd', KepalaUkpdDashboard::class)->middleware('role:kepala_ukpd')->name('dashboard.kepala-ukpd');
        Route::get('/dashboard-penyedia', PenyediaDashboard::class)->middleware('role:penyedia')->name('dashboard.penyedia');
        Route::get('/dashboard-nahkoda', NahkodaDashboard::class)->middleware('role:nahkoda')->name('dashboard.nahkoda');

        Route::get('/kelola-ukpd', KelolaUkpd::class)->middleware('role:superadmin')->name('superadmin.kelola-ukpd');
        Route::get('/kelola-user', KelolaUser::class)->middleware('role:superadmin,admin_ukpd')->name('superadmin.kelola-user');
        Route::get('/data-kapal', DataKapal::class)->middleware('role:superadmin,satgas,sounding,admin_ukpd,kepala_ukpd')->name('data-kapal');

        Route::get('/sounding-bbm', SoundingBBM::class)->middleware('role:superadmin,sounding,admin_ukpd')->name('sounding.sounding-bbm');

        Route::get('/laporan-sisa-bbm', LaporanSisaBBM::class)->middleware('role:superadmin,satgas,admin_ukpd,kepala_ukpd,pengawas')->name('satgas.laporan-sisa-bbm');
        Route::get('/surat-tugas', SuratTugasPengisianBBM::class)->middleware('role:superadmin,satgas,admin_ukpd,kepala_ukpd')->name('satgas.surat-tugas');
        Route::get('/surat-permohonan', SuratPermohonanPengisianBBM::class)->middleware('role:superadmin,satgas,admin_ukpd,kepala_ukpd,pptk')->name('satgas.surat-permohonan');
        
        Route::get('/pencatatan-pengisian', PencatatanHasilPengisian::class)->middleware('role:superadmin,admin_ukpd,nahkoda,pengawas,penyedia,kepala_ukpd')->name('satgas.pencatatan-pengisian');
        Route::get('/laporan-pengisian', LaporanPengisianBBM::class)->middleware('role:superadmin,satgas,admin_ukpd,kepala_ukpd')->name('satgas.laporan-pengisian');
        Route::get('/berita-acara-pengisian', BeritaAcaraLaporanPengisian::class)->middleware('role:superadmin,satgas,admin_ukpd,kepala_ukpd,pptk')->name('satgas.berita-acara-pengisian');

        // --- FITUR VERIFIKASI TAGIHAN ---
        Route::get('/verifikasi-tagihan', VerifikasiInvoiceBBM::class)->middleware('role:superadmin,satgas,admin_ukpd,kepala_ukpd,pptk')->name('satgas.verifikasi-tagihan');

        // --- FITUR SURAT SPJ ---
        Route::get('/surat-spj', SuratSpj::class)->middleware('role:superadmin,satgas,admin_ukpd,pptk,kepala_ukpd')->name('satgas.surat-spj');

        // --- FITUR PENYEDIA & SUPERADMIN ---
        Route::get('/pesanan-bbm', PesananMasukBBM::class)->middleware('role:superadmin,penyedia')->name('penyedia.pesanan-bbm');
        Route::get('/tagihan-invoice', InvoiceManager::class)->middleware('role:superadmin,penyedia')->name('penyedia.invoice-tagihan');

        // --- CETAK PDF ---
        Route::get('/laporan-bbm/{id}/pdf', [PdfController::class, 'previewLaporan'])->name('laporan.pdf.preview');
        Route::get('/surat-tugas/{id}/pdf', [PdfController::class, 'previewSuratTugas'])->name('surattugas.pdf.preview');
        Route::get('/laporan-sisa-bbm/{id}/pdf', [PdfController::class, 'previewLaporanSisaBbm'])->name('laporan-sisa-bbm.pdf.preview');
    });
});