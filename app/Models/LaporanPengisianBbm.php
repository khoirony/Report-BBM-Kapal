<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPengisianBbm extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function suratTugas()
    {
        return $this->belongsTo(SuratTugasPengisian::class, 'surat_tugas_id');
    }

    public function suratPermohonan()
    {
        return $this->belongsTo(SuratPermohonanPengisian::class, 'surat_permohonan_id');
    }
}
