<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class PptkDashboard extends Component
{
    public function render()
    {
        return view('livewire.dashboard.admin-ukpd-dashboard', [
        ])->layout('layouts.app');
    }
}