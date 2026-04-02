<?php

namespace App\Livewire\SuperAdmin;

use App\Models\Kapal; // Pastikan menggunakan model Kapal
use Livewire\Component;

class DataKapal extends Component
{
    // Properti untuk menyimpan inputan form dan ID
    public $kapal_id;
    public $nama_kapal, $skpd_ukpd, $jenis_dan_tipe, $material, $tahun_pembuatan, $ukuran, $tonase_kotor_gt, $tenaga_penggerak_kw, $daerah_pelayaran, $list_sertifikat_kapal;
    
    // Properti untuk mengontrol tampilan Modal
    public $isModalOpen = false;

    public function render()
    {
        // Ambil semua data kapal dari database (diurutkan dari yang terbaru)
        $kapals = Kapal::latest()->get();

        // Return view dengan membawa data $kapals dan merender ke dalam layouts.app
        return view('livewire.super-admin.data-kapal', [
            'kapals' => $kapals
        ])->layout('layouts.app');
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
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->kapal_id = '';
        $this->nama_kapal = '';
        $this->skpd_ukpd = '';
        $this->jenis_dan_tipe = '';
        $this->material = '';
        $this->tahun_pembuatan = '';
        $this->ukuran = '';
        $this->tonase_kotor_gt = '';
        $this->tenaga_penggerak_kw = '';
        $this->daerah_pelayaran = '';
        $this->list_sertifikat_kapal = '';
    }

    public function store()
    {
        // Validasi wajib isi
        $this->validate([
            'nama_kapal' => 'required',
            'skpd_ukpd' => 'required',
        ]);

        // Simpan data baru atau update jika $kapal_id sudah ada
        Kapal::updateOrCreate(['id' => $this->kapal_id], [
            'nama_kapal' => $this->nama_kapal,
            'skpd_ukpd' => $this->skpd_ukpd,
            'jenis_dan_tipe' => $this->jenis_dan_tipe,
            'material' => $this->material,
            'tahun_pembuatan' => $this->tahun_pembuatan,
            'ukuran' => $this->ukuran,
            'tonase_kotor_gt' => $this->tonase_kotor_gt,
            'tenaga_penggerak_kw' => $this->tenaga_penggerak_kw,
            'daerah_pelayaran' => $this->daerah_pelayaran,
            'list_sertifikat_kapal' => $this->list_sertifikat_kapal,
        ]);

        session()->flash('message', $this->kapal_id ? 'Data Kapal Berhasil Diperbarui.' : 'Data Kapal Berhasil Ditambahkan.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $kapal = Kapal::findOrFail($id);
        
        $this->kapal_id = $id;
        $this->nama_kapal = $kapal->nama_kapal;
        $this->skpd_ukpd = $kapal->skpd_ukpd;
        $this->jenis_dan_tipe = $kapal->jenis_dan_tipe;
        $this->material = $kapal->material;
        $this->tahun_pembuatan = $kapal->tahun_pembuatan;
        $this->ukuran = $kapal->ukuran;
        $this->tonase_kotor_gt = $kapal->tonase_kotor_gt;
        $this->tenaga_penggerak_kw = $kapal->tenaga_penggerak_kw;
        $this->daerah_pelayaran = $kapal->daerah_pelayaran;
        $this->list_sertifikat_kapal = $kapal->list_sertifikat_kapal;

        $this->openModal();
    }

    public function delete($id)
    {
        Kapal::find($id)->delete();
        session()->flash('message', 'Data Kapal Berhasil Dihapus.');
    }
}