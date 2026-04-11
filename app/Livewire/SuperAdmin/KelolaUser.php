<?php

namespace App\Livewire\SuperAdmin;

use App\Models\User;
use App\Models\Ukpd;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class KelolaUser extends Component
{
    use WithPagination;

    // Properti Form
    public $userId, $name, $email, $password, $ukpd_id, $role;
    public $is_verified = true; // Properti baru untuk verifikasi

    // Properti Filter, Search, Sort
    public $search = '';
    public $filterRole = '';
    public $filterUkpd = '';
    public $filterVerifikasi = ''; // Filter baru
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    // Properti Modal
    public $isModalOpen = false;
    public $modalMode = 'create'; // 'create' atau 'edit'

    // Reset pagination ketika melakukan pencarian atau filtering
    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterRole() { $this->resetPage(); }
    public function updatingFilterUkpd() { $this->resetPage(); }
    public function updatingFilterVerifikasi() { $this->resetPage(); }

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
        $this->reset(['userId', 'name', 'email', 'password', 'ukpd_id', 'role', 'is_verified']);
        $this->modalMode = $mode;

        if ($mode === 'edit' && $id) {
            $user = User::findOrFail($id);
            $this->userId = $user->id;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->ukpd_id = $user->ukpd_id;
            $this->role = $user->role;
            // Cek apakah email_verified_at ada isinya
            $this->is_verified = !is_null($user->email_verified_at);
        } else {
            $this->role = 'sounding'; // Nilai default
            $this->is_verified = true; // Default admin buat user langsung terverifikasi
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
            'role' => 'required|in:superadmin,sounding,satgas,penyedia,nahkoda,pengawas',
            'ukpd_id' => 'nullable|exists:ukpds,id',
            'is_verified' => 'boolean'
        ];

        // Password wajib saat create, opsional saat edit
        if ($this->modalMode === 'create') {
            $rules['password'] = 'required|min:8';
        } else {
            $rules['password'] = 'nullable|min:8';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            // Jika kosong/string kosong, ubah jadi null agar tidak gagal di foreign key constraint
            'ukpd_id' => empty($this->ukpd_id) ? null : $this->ukpd_id, 
        ];

        // Kelola status verifikasi
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

        // Jika password diisi, enkripsi
        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        User::updateOrCreate(
            ['id' => $this->userId],
            $data
        );

        session()->flash('message', $this->modalMode === 'create' ? 'User berhasil ditambahkan.' : 'User berhasil diperbarui.');
        $this->closeModal();
    }

    // Aksi Cepat: Toggle Verifikasi dari Tabel
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
        session()->flash('message', 'User berhasil dihapus.');
    }

    public function render()
    {
        $users = User::with('ukpd')
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('name', 'like', '%' . $this->search . '%')
                             ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterRole, function ($query) {
                $query->where('role', $this->filterRole);
            })
            ->when($this->filterUkpd, function ($query) {
                $query->where('ukpd_id', $this->filterUkpd);
            })
            ->when($this->filterVerifikasi !== '', function ($query) {
                // Filter status verifikasi
                if ($this->filterVerifikasi == '1') {
                    $query->whereNotNull('email_verified_at');
                } else {
                    $query->whereNull('email_verified_at');
                }
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        $ukpds = Ukpd::all();

        return view('livewire.super-admin.kelola-user', [
            'users' => $users,
            'ukpds' => $ukpds,
        ])->layout('layouts.app');
    }
}