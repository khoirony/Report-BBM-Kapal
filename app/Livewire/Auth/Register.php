<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Models\Role; // Tambahkan Model Role
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Livewire\Component;

class Register extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $role_slug = 'penyedia'; // Ganti variabel jadi role_slug

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
        // Validasi ini sekarang otomatis mengecek apakah slug ada di tabel roles
        'role_slug' => 'required|exists:roles,slug', 
    ];

    public function register()
    {
        // batasi pendaftaran maksimal 50 pengguna per hari untuk mencegah bot spam
        $todayRegistrations = User::whereDate('created_at', now()->toDateString())->count();
        if ($todayRegistrations >= 50) {
            $this->addError('email', 'Mohon maaf, batas maksimal pendaftaran (50 pengguna/hari) telah tercapai. Silakan coba lagi besok.');
            return;
        }

        $this->validate();

        // Cari ID Role berdasarkan slug yang dipilih di UI
        $role = Role::where('slug', $this->role_slug)->first();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role_id' => $role->id, // Gunakan role_id
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect dinamis berdasarkan role yang baru dibuat
        $dashboardRoute = '/dashboard-' . str_replace('_', '-', $role->slug);
        return redirect()->to($dashboardRoute);
    }

    public function render()
    {
        return view('livewire.auth.register')->layout('layouts.guest');
    }
}