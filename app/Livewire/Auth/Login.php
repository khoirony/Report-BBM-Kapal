<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Component;

class Login extends Component
{
    public $login = ''; 
    public $password = '';

    protected $rules = [
        'login' => 'required',
        'password' => 'required',
    ];

    public function authenticate()
    {
        $this->validate();

        $throttleKey = Str::transliterate(Str::lower($this->login).'|'.request()->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 7)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            
            $this->addError('login', 'Terlalu banyak percobaan login. Silakan coba lagi dalam ' . $seconds . ' detik.');
            return;
        }

        // Cari user berdasarkan email, username, atau NIP
        $user = User::where('email', $this->login)
                    ->orWhere('username', $this->login)
                    ->orWhere('nip', $this->login)
                    ->first();

        // Jika user ditemukan, gunakan emailnya untuk proses autentikasi bawaan Laravel
        if ($user && Auth::attempt(['email' => $user->email, 'password' => $this->password])) {
            RateLimiter::clear($throttleKey);
            
            session()->regenerate();
            
            $roleSlug = Auth::user()->role->slug;
            $dashboardRoute = '/dashboard-' . str_replace('_', '-', $roleSlug);
            
            return redirect()->intended($dashboardRoute);
        }

        RateLimiter::hit($throttleKey);

        $this->addError('login', 'Kredensial tidak cocok dengan data kami.');
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.guest');
    }
}