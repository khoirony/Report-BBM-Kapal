<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileSuratPermohonan extends Model
{
    protected $guarded = ['id'];

    // Relasi balik ke tabel utama
    public function suratPermohonan()
    {
        return $this->belongsTo(SuratPermohonanPengisian::class, 'surat_permohonan_id');
    }
}
