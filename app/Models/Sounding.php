<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sounding extends Model
{
    /** @use HasFactory<\Database\Factories\SoundingFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function kapal()
    {
        return $this->belongsTo(Kapal::class);
    }
    
    public function laporanPengisian() 
    {
        return $this->belongsToMany(LaporanPengisian::class, 'laporan_sounding', 'sounding_id', 'laporan_bbm_id')->withTimestamps();
    }
}
