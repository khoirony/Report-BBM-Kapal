<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'superadmin', 'color' => 'rose'],
            ['name' => 'Admin UKPD', 'slug' => 'admin_ukpd', 'color' => 'cyan'],
            ['name' => 'Kepala UKPD', 'slug' => 'kepala_ukpd', 'color' => 'orange'],
            ['name' => 'Pengawas', 'slug' => 'pengawas', 'color' => 'purple'],
            ['name' => 'PPTK', 'slug' => 'pptk', 'color' => 'teal'],
            ['name' => 'Tim Sounding', 'slug' => 'sounding', 'color' => 'blue'],
            ['name' => 'Satgas BBM', 'slug' => 'satgas', 'color' => 'emerald'],
            ['name' => 'Nakhoda', 'slug' => 'nakhoda', 'color' => 'indigo'],
            ['name' => 'Penyedia BBM', 'slug' => 'penyedia', 'color' => 'amber'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}