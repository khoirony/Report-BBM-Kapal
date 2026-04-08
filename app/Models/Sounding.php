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
        return $this->belongsToMany(BaPengisianBbm::class, 'laporan_sounding', 'sounding_id', 'ba_pengisian_bbm_id')->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
