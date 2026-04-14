<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ukpd extends Model
{
    use HasFactory;

    // Mengizinkan semua kolom untuk diisi secara massal
    protected $guarded = ['id'];

    public function kepalaUkpd()
    {
        return $this->hasOne(User::class, 'ukpd_id')->where('role_id', 3);
    }
}
