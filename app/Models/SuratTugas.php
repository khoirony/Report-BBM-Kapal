<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratTugas extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = ['tanggal_dikeluarkan' => 'date'];

    public function laporanBbm()
    {
        return $this->belongsTo(LaporanPengisian::class, 'laporan_pengisian_id');
    }
}
