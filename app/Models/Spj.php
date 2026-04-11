<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spj extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_spj',
        'kapal_id',
        'tanggal_spj',
        'file_spj',
        'status',
        'created_by',
        'ukpd_id',
    ];

    // Relasi ke Kapal
    public function kapal()
    {
        return $this->belongsTo(Kapal::class);
    }

    // Relasi ke User pembuat
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke UKPD
    public function ukpd()
    {
        return $this->belongsTo(Ukpd::class);
    }
}