<?php

namespace App\Livewire\Satgas;

use Livewire\Component;
use Livewire\WithPagination;

class LaporanPengisianBBM extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.satgas.laporan-pengisian-bbm', [
        ])->layout('layouts.app');
    }
}