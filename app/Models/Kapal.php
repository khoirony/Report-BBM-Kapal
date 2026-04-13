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

    public function nahkoda()
    {
        return $this->belongsTo(User::class, 'nahkoda_id');
    }
}
