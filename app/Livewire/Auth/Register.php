<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Livewire\Component;

class Register extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $role = 'penyedia'; // Default role

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
        'role' => 'required|in:sounding,satgas,penyedia,nahkoda,pengawas',
    ];

    public function register()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect dinamis berdasarkan role
        return redirect()->to('/dashboard-' . $user->role);
    }

    public function render()
    {
        return view('livewire.auth.register')->layout('layouts.guest');
    }
}