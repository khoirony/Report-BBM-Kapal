<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanSebelumPengisian extends Model
{
    /** @use HasFactory<\Database\Factories\LaporanPengisianFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = ['tanggal' => 'date', 'petugas_list' => 'array'];

    // Relasi ke Kapal
    public function kapal() {
        return $this->belongsTo(Kapal::class);
    }

    // Relasi One-to-Many ke Sounding
    public function soundings() 
    {
        return $this->belongsToMany(Sounding::class, 'laporan_sounding', 'laporan_pengisian_id', 'sounding_id')->withTimestamps();
    }

    public function suratTugas()
    {
        return $this->hasOne(SuratTugasPengisian::class, 'laporan_bbm_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
