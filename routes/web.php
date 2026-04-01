<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Dashboard\Nahkoda;
use App\Livewire\Dashboard\Pengawas;
use App\Livewire\Dashboard\Penyedia;
use App\Livewire\Dashboard\Satgas;
use App\Livewire\Dashboard\Sounding;
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
        'sounding' => redirect()->route('dashboard.sounding'),
        'satgas'   => redirect()->route('dashboard.satgas'),
        'penyedia' => redirect()->route('dashboard.penyedia'),
        'nahkoda'  => redirect()->route('dashboard.nahkoda'),
        'pengawas' => redirect()->route('dashboard.pengawas'),
        default    => abort(403, 'Unauthorized action.'), // Atau redirect ke rute default lainnya
    };
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

// Protected Routes (Contoh Dashboard untuk masing-masing role)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard-sounding', Sounding::class)->name('dashboard.sounding');
    Route::get('/dashboard-satgas', Satgas::class)->name('dashboard.satgas');
    Route::get('/dashboard-penyedia', Penyedia::class)->name('dashboard.penyedia');
    Route::get('/dashboard-nahkoda', Nahkoda::class)->name('dashboard.nahkoda');
    Route::get('/dashboard-pengawas', Pengawas::class)->name('dashboard.pengawas');
    
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});