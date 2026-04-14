<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kapal extends Model
{
    use HasFactory;

    // Mengizinkan semua kolom untuk diisi secara massal
    protected $guarded = ['id'];

    public function ukpd()
    {
        return $this->belongsTo(Ukpd::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sounding() 
    {
        return $this->hasOne(Sounding::class);
    }

    public function nakhoda()
    {
        return $this->belongsTo(User::class, 'nakhoda_id');
    }
}
