<?php

namespace App\Livewire\SuperAdmin;

use App\Models\User;
use App\Models\Ukpd;
use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class KelolaPerusahaan extends Component
{
    use WithPagination;

    // Properti Form
    public $userId, $name, $email, $password, $ukpd_id, $role_id;
    public $is_verified = true; 

    // Properti Search & Sort
    public $search = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    // Properti Modal
    public $isModalOpen = false;
    public $modalMode = 'create'; 

    public function updatingSearch() 
    { 
        $this->resetPage(); 
    }

    public function sortByField($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
            $this->sortBy = $field;
        }
    }

    public function openModal($mode = 'create', $id = null)
    {
        $this->resetValidation();
        $this->reset(['userId', 'name', 'email', 'password', 'ukpd_id', 'role_id', 'is_verified']);
        $this->modalMode = $mode;

        // Cari Role Penyedia secara dinamis
        $rolePenyedia = Role::where('slug', 'penyedia')->first();

        if ($mode === 'edit' && $id) {
            $user = User::findOrFail($id);
            $this->userId = $user->id;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->ukpd_id = $user->ukpd_id;
            $this->role_id = $user->role_id;
            $this->is_verified = !is_null($user->email_verified_at);
        } else {
            // Default ID untuk Penyedia
            $this->role_id = $rolePenyedia ? $rolePenyedia->id : null; 
            $this->is_verified = true; 
        }

        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required', 
                'email', 
                'max:255', 
                Rule::unique('users')->ignore($this->userId)
            ],
            'role_id' => 'required|exists:roles,id',
            'ukpd_id' => 'nullable|exists:ukpds,id',
            'is_verified' => 'boolean'
        ];

        if ($this->modalMode === 'create') {
            $rules['password'] = 'required|min:8';
        } else {
            $rules['password'] = 'nullable|min:8';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role_id' => $this->role_id,
            'ukpd_id' => empty($this->ukpd_id) ? null : $this->ukpd_id, 
        ];

        if ($this->modalMode === 'edit') {
            $user = User::find($this->userId);
            if ($this->is_verified && is_null($user->email_verified_at)) {
                $data['email_verified_at'] = now();
            } elseif (!$this->is_verified) {
                $data['email_verified_at'] = null;
            }
        } else {
            $data['email_verified_at'] = $this->is_verified ? now() : null;
        }

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        User::updateOrCreate(
            ['id' => $this->userId],
            $data
        );

        session()->flash('message', $this->modalMode === 'create' ? 'User Penyedia berhasil ditambahkan.' : 'User Penyedia berhasil diperbarui.');
        $this->closeModal();
    }

    public function toggleVerification($id)
    {
        $user = User::findOrFail($id);
        if (is_null($user->email_verified_at)) {
            $user->update(['email_verified_at' => now()]);
            session()->flash('message', "User {$user->name} berhasil diverifikasi.");
        } else {
            $user->update(['email_verified_at' => null]);
            session()->flash('message', "Verifikasi User {$user->name} berhasil dicabut.");
        }
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();
        session()->flash('message', 'User Penyedia berhasil dihapus.');
    }

    public function render()
    {
        // Dapatkan ID Role Penyedia
        $rolePenyedia = Role::where('slug', 'penyedia')->first();
        $roleId = $rolePenyedia ? $rolePenyedia->id : null;

        $users = User::with(['ukpd', 'role'])
            ->where('role_id', $roleId) // KUNCI: Hanya menampilkan role penyedia
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('name', 'like', '%' . $this->search . '%')
                             ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        $ukpds = Ukpd::all();

        return view('livewire.super-admin.kelola-perusahaan', [
            'users' => $users,
            'ukpds' => $ukpds,
        ])->layout('layouts.app');
    }
}