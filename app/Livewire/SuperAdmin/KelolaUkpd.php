<?php

namespace App\Livewire\SuperAdmin;

use App\Models\User;
use App\Models\Ukpd;
use App\Models\Role; // Tambahkan Model Role
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class KelolaUkpd extends Component
{
    use WithPagination;

    // ================= Properti UKPD =================
    public $ukpd_id, $nama, $singkatan, $alamat, $email, $kode_pos, $user_nip;
    public $search = '';
    public $isOpen = false;

    // ================= Properti User =================
    // UPDATE: Gunakan user_role_id alih-alih user_role
    public $user_id, $user_name, $user_email, $user_password, $user_role_id, $user_ukpd_id;
    public $isUserModalOpen = false;
    
    // Properti Filter & Sort User
    public $searchUser = '';
    public $sortByUser = 'created_at';
    public $sortDirectionUser = 'desc';
    public $filterRoleUser = ''; // Akan menyimpan role_id
    public $filterUkpdUser = '';
    public $filterVerifikasiUser = '';

    // ================= Reset Pagination =================
    public function updatingSearch() { $this->resetPage('ukpdPage'); }
    public function updatingSearchUser() { $this->resetPage('userPage'); }
    public function updatingFilterRoleUser() { $this->resetPage('userPage'); }
    public function updatingFilterUkpdUser() { $this->resetPage('userPage'); }
    public function updatingFilterVerifikasiUser() { $this->resetPage('userPage'); }

    public function render()
    {
        $dataUkpd = Ukpd::where('nama', 'like', '%' . $this->search . '%')
            ->orWhere('singkatan', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10, ['*'], 'ukpdPage');

        // Ambil Daftar Role khusus UKPD dari tabel Roles (Sesuaikan kondisi 'name' dengan data di database Anda)
        $rolesList = Role::whereIn('name', ['admin_ukpd', 'kepala_ukpd', 'Admin UKPD', 'Kepala UKPD'])->get();
        $roleIds = $rolesList->pluck('id')->toArray();

        // Query Tabel User (Hanya yang memiliki role_id UKPD tersebut)
        $queryUser = User::with(['ukpd', 'role'])->whereIn('role_id', $roleIds);

        if (!empty($this->searchUser)) {
            $queryUser->where(function ($query) {
                $query->where('name', 'like', '%' . $this->searchUser . '%')
                      ->orWhere('email', 'like', '%' . $this->searchUser . '%');
            });
        }

        // UPDATE: Filter berdasarkan role_id
        if (!empty($this->filterRoleUser)) {
            $queryUser->where('role_id', $this->filterRoleUser);
        }

        if (!empty($this->filterUkpdUser)) {
            $queryUser->where('ukpd_id', $this->filterUkpdUser);
        }

        if ($this->filterVerifikasiUser !== '') {
            if ($this->filterVerifikasiUser == '1') {
                $queryUser->whereNotNull('email_verified_at');
            } else {
                $queryUser->whereNull('email_verified_at');
            }
        }

        // Ambil data user
        $dataUser = $queryUser->orderBy($this->sortByUser, $this->sortDirectionUser)
            ->paginate(10, ['*'], 'userPage');

        // List UKPD untuk dropdown form
        $listUkpd = Ukpd::orderBy('nama', 'asc')->get();

        return view('livewire.super-admin.kelola-ukpd', [
            'dataUkpd' => $dataUkpd,
            'dataUser' => $dataUser,
            'listUkpd' => $listUkpd,
            'rolesList' => $rolesList
        ])->layout('layouts.app');
    }

    public function sortByFieldUser($field)
    {
        // Pengecualian khusus jika mengklik filter "role" di table header, kita sorting berdasarkan role_id
        $sortField = $field === 'role' ? 'role_id' : $field;

        if ($this->sortByUser === $sortField) {
            $this->sortDirectionUser = $this->sortDirectionUser === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortByUser = $sortField;
            $this->sortDirectionUser = 'asc';
        }
    }

    public function toggleVerification($id)
    {
        $user = User::find($id);
        if($user) {
            $user->email_verified_at = $user->email_verified_at ? null : now();
            $user->save();
            session()->flash('message', 'Status Verifikasi User Berhasil Diubah.');
        }
    }

    public function create() { $this->resetInputFields(); $this->isOpen = true; }
    public function closeModal() { $this->isOpen = false; $this->resetInputFields(); }
    
    private function resetInputFields() {
        $this->ukpd_id = ''; $this->nama = ''; $this->singkatan = ''; 
        $this->alamat = ''; $this->email = ''; $this->kode_pos = ''; 
        $this->resetValidation();
    }

    public function store() {
        $this->validate([
            'nama' => 'required|string|max:255', 'singkatan' => 'nullable|string|max:255',
            'alamat' => 'nullable|string', 'email' => 'nullable|email|max:255', 'kode_pos' => 'nullable|string|max:10',
        ]);
        Ukpd::updateOrCreate(['id' => $this->ukpd_id], [
            'nama' => $this->nama, 'singkatan' => $this->singkatan, 'alamat' => $this->alamat,
            'email' => $this->email, 'kode_pos' => $this->kode_pos,
        ]);
        session()->flash('message', $this->ukpd_id ? 'Data UKPD Berhasil Diperbarui.' : 'Data UKPD Berhasil Ditambahkan.');
        $this->closeModal();
    }

    public function edit($id) {
        $ukpd = Ukpd::findOrFail($id);
        $this->ukpd_id = $id; $this->nama = $ukpd->nama; $this->singkatan = $ukpd->singkatan;
        $this->alamat = $ukpd->alamat; $this->email = $ukpd->email; $this->kode_pos = $ukpd->kode_pos;
        $this->isOpen = true;
    }

    public function delete($id) {
        Ukpd::find($id)->delete(); session()->flash('message', 'Data UKPD Berhasil Dihapus.');
    }

    public function createUser()
    {
        $this->resetUserFields();
        $this->isUserModalOpen = true;
    }

    public function closeUserModal()
    {
        $this->isUserModalOpen = false;
        $this->resetUserFields();
    }

    private function resetUserFields()
    {
        $this->user_id = '';
        $this->user_name = '';
        $this->user_email = '';
        $this->user_password = '';
        $this->user_role_id = '';
        $this->user_ukpd_id = '';
        $this->user_nip = '';
        $this->resetValidation();
    }

    public function storeUser()
    {
        $rules = [
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|max:255|unique:users,email,' . $this->user_id,
            'user_role_id' => 'required|exists:roles,id', // UPDATE Validasi ke tabel roles
            'user_ukpd_id' => 'required|exists:ukpds,id',
            'user_nip' => 'required|string|max:50|unique:users,nip,' . $this->user_id,
        ];

        if (!$this->user_id || $this->user_password) {
            $rules['user_password'] = 'required|string|min:6';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->user_name,
            'nip' => $this->user_nip,
            'email' => $this->user_email,
            'role_id' => $this->user_role_id, // UPDATE Field Role ID
            'ukpd_id' => $this->user_ukpd_id,
        ];

        if ($this->user_password) {
            $data['password'] = Hash::make($this->user_password);
        }

        User::updateOrCreate(['id' => $this->user_id], $data);

        session()->flash('message', $this->user_id ? 'Data User Berhasil Diperbarui.' : 'Data User Berhasil Ditambahkan.');
        $this->closeUserModal();
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        
        $this->user_id = $id;
        $this->user_name = $user->name;
        $this->user_email = $user->email;
        $this->user_role_id = $user->role_id;
        $this->user_ukpd_id = $user->ukpd_id;
        $this->user_nip = $user->nip;

        $this->isUserModalOpen = true;
    }

    public function deleteUser($id)
    {
        User::find($id)->delete();
        session()->flash('message', 'Data User Berhasil Dihapus.');
    }
}