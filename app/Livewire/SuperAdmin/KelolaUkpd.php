<?php

namespace App\Livewire\SuperAdmin;

use App\Models\User;
use App\Models\Ukpd;
use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;

class KelolaUkpd extends Component
{
    use WithPagination;

    // Properti Form
    public $ukpd_id;
    public $nama, $singkatan, $alamat, $email, $kode_pos;

    // Properti Fitur
    public $search = '';
    public $isOpen = false;        // State untuk Modal Create/Edit
    public $isDeleteOpen = false;  // State untuk Modal Delete

    // Reset pagination ketika melakukan pencarian
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $dataUkpd = Ukpd::where('nama', 'like', '%' . $this->search . '%')
            ->orWhere('singkatan', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.super-admin.kelola-ukpd', [
            'dataUkpd' => $dataUkpd
        ])->layout('layouts.app');
    }

    // Membuka dan Menutup Modal Form
    public function create()
    {
        $this->resetInputFields();
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->ukpd_id = '';
        $this->nama = '';
        $this->singkatan = '';
        $this->alamat = '';
        $this->email = '';
        $this->kode_pos = '';
        $this->resetValidation();
    }

    // Menyimpan / Update Data
    public function store()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'singkatan' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'kode_pos' => 'nullable|string|max:10',
        ]);

        Ukpd::updateOrCreate(
            ['id' => $this->ukpd_id], // Jika ID ada, maka update. Jika null, create.
            [
                'nama' => $this->nama,
                'singkatan' => $this->singkatan,
                'alamat' => $this->alamat,
                'email' => $this->email,
                'kode_pos' => $this->kode_pos,
            ]
        );

        session()->flash('message', $this->ukpd_id ? 'Data UKPD Berhasil Diperbarui.' : 'Data UKPD Berhasil Ditambahkan.');

        $this->closeModal();
    }

    // Mengambil data untuk di-edit
    public function edit($id)
    {
        $ukpd = Ukpd::findOrFail($id);
        
        $this->ukpd_id = $id;
        $this->nama = $ukpd->nama;
        $this->singkatan = $ukpd->singkatan;
        $this->alamat = $ukpd->alamat;
        $this->email = $ukpd->email;
        $this->kode_pos = $ukpd->kode_pos;

        $this->isOpen = true;
    }

    // Mengeksekusi Hapus Data
    public function delete($id)
    {
        Ukpd::find($id)->delete();
        
        session()->flash('message', 'Data UKPD Berhasil Dihapus.');
        
        $this->resetInputFields();
    }
}