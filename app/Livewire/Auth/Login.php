<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
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

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();
            
            // Redirect dinamis berdasarkan role user
            $role = Auth::user()->role;
            return redirect()->intended('/dashboard-' . $role);
        }

        $this->addError('email', 'Kredensial tidak cocok dengan data kami.');
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.guest');
    }
}