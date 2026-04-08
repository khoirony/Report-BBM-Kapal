<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ukpd extends Model
{
    use HasFactory;

    // Mengizinkan semua kolom untuk diisi secara massal
    protected $guarded = ['id'];
}
