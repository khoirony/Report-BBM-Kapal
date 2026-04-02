<?php

namespace App\Livewire\Sounding;

use App\Models\Kapal;
use App\Models\Sounding;
use Livewire\Component;

class SoundingBBM extends Component
{
    public $soundings, $kapals;
    public $sounding_id, $kapal_id, $lokasi, $bbm_awal, $pengisian, $pemakaian, $bbm_akhir, $jam_berangkat, $jam_kembali;
    public $isModalOpen = 0;

    // Menghitung otomatis BBM Akhir saat input berubah
    public function updated($propertyName)
    {
        if (in_array($propertyName, ['bbm_awal', 'pengisian', 'pemakaian'])) {
            $this->bbm_akhir = (float)$this->bbm_awal + (float)$this->pengisian - (float)$this->pemakaian;
        }
    }

    public function render()
    {
        $this->soundings = Sounding::with('kapal')->latest()->get();
        $this->kapals = Kapal::latest()->get();

        return view('livewire.sounding.sounding-bbm')->layout('layouts.app');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetInputFields()
    {
        $this->sounding_id = '';
        $this->kapal_id = '';
        $this->lokasi = '';
        $this->bbm_awal = 0;
        $this->pengisian = 0;
        $this->pemakaian = 0;
        $this->bbm_akhir = 0;
        $this->jam_berangkat = '';
        $this->jam_kembali = '';
    }

    public function store()
    {
        $this->validate([
            'kapal_id' => 'required',
            'lokasi' => 'required|string|max:255',
            'bbm_awal' => 'required|numeric',
            'pengisian' => 'required|numeric',
            'pemakaian' => 'required|numeric',
            'jam_berangkat' => 'required',
            'jam_kembali' => 'required',
        ]);

        // Kalkulasi ulang demi keamanan backend
        $bbm_akhir_calc = (float)$this->bbm_awal + (float)$this->pengisian - (float)$this->pemakaian;

        Sounding::updateOrCreate(['id' => $this->sounding_id], [
            'kapal_id' => $this->kapal_id,
            'lokasi' => $this->lokasi,
            'bbm_awal' => $this->bbm_awal,
            'pengisian' => $this->pengisian,
            'pemakaian' => $this->pemakaian,
            'bbm_akhir' => $bbm_akhir_calc,
            'jam_berangkat' => $this->jam_berangkat,
            'jam_kembali' => $this->jam_kembali,
        ]);

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $sounding = Sounding::findOrFail($id);
        $this->sounding_id = $id;
        $this->kapal_id = $sounding->kapal_id;
        $this->lokasi = $sounding->lokasi;
        $this->bbm_awal = $sounding->bbm_awal;
        $this->pengisian = $sounding->pengisian;
        $this->pemakaian = $sounding->pemakaian;
        $this->bbm_akhir = $sounding->bbm_akhir;
        $this->jam_berangkat = $sounding->jam_berangkat;
        $this->jam_kembali = $sounding->jam_kembali;
    
        $this->openModal();
    }

    public function delete($id)
    {
        Sounding::find($id)->delete();
    }
}