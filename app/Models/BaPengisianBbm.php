<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaPengisianBbm extends Model
{
    /** @use HasFactory<\Database\Factories\LaporanPengisianFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = ['tanggal' => 'date', 'petugas_list' => 'array'];

    // Relasi ke Kapal
    public function kapal() {
        return $this->belongsTo(Kapal::class);
    }

    public function laporanPengisian() {
        return $this->belongsTo(LaporanPengisianBbm::class, 'laporan_pengisian_bbm_id');
    }

    public function suratPermohonan() {
        return $this->hasOneThrough(
            SuratPermohonanPengisian::class,
            LaporanPengisianBbm::class,
            'id', // Foreign key di LaporanPengisian
            'id', // Foreign key di SuratPermohonan
            'laporan_pengisian_bbm_id', // Local key di BA
            'surat_permohonan_id' // Local key di LaporanPengisian
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
