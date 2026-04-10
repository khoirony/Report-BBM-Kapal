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

    public function soundingAwal() {
        return $this->belongsTo(Sounding::class, 'sounding_awal_id');
    }

    public function soundingAkhir() {
        return $this->belongsTo(Sounding::class, 'sounding_akhir_id');
    }

    public function approverNakhoda()
    {
        return $this->belongsTo(User::class, 'disetujui_nakhoda_by');
    }

    public function approverPenyedia()
    {
        return $this->belongsTo(User::class, 'disetujui_penyedia_by');
    }
}
