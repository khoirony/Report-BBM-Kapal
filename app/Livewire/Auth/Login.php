<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Component;

class Login extends Component
{
    public $email = '';
    public $password = '';

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function login()
    {
        $this->validate();

        $throttleKey = Str::transliterate(Str::lower($this->email).'|'.request()->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 7)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            
            $this->addError('email', 'Terlalu banyak percobaan login. Silakan coba lagi dalam ' . $seconds . ' detik.');
            return;
        }

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            RateLimiter::clear($throttleKey);
            
            session()->regenerate();
            
            // AMBIL SLUG ROLE DARI RELASI DAN UBAH FORMATNYA
            $roleSlug = Auth::user()->role->slug;
            $dashboardRoute = '/dashboard-' . str_replace('_', '-', $roleSlug);
            
            return redirect()->intended($dashboardRoute);
        }

        RateLimiter::hit($throttleKey);

        $this->addError('email', 'Kredensial tidak cocok dengan data kami.');
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.guest');
    }
}