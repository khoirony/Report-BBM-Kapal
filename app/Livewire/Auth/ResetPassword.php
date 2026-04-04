<?php

namespace App\Livewire\Auth;

use Illuminate\Auth\Events\PasswordReset as EventsPasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Component;

class ResetPassword extends Component
{
    public $token;
    public $email;
    public $password;
    public $password_confirmation;

    public function mount($token)
    {
        $this->token = $token;
        // Mengambil email dari parameter URL (?email=...) bawaan Laravel
        $this->email = request()->query('email'); 
    }

    protected $rules = [
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:6|same:password_confirmation',
    ];

    public function resetPassword()
    {
        $this->validate();

        $status = Password::broker()->reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new EventsPasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            session()->flash('status', 'Password Anda berhasil direset! Silakan login dengan password baru.');
            return redirect()->route('login');
        }

        $this->addError('email', __($status));
    }

    public function render()
    {
        return view('livewire.auth.reset-password')->layout('layouts.guest');
    }
}