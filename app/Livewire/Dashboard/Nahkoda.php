<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\User; // Ganti dengan Model yang ingin kamu tampilkan datanya

class Nahkoda extends Component
{
    public function render()
    {
        // Contoh Query ke Database (misal ambil 5 user terbaru)
        $data_dummy = User::latest()->take(5)->get();

        return view('livewire.dashboard.nahkoda', [
            'data_dummy' => $data_dummy
        ])->layout('layouts.app');
    }
}