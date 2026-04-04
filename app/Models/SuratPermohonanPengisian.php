<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratPermohonanPengisian extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function suratTugas()
    {
        return $this->belongsTo(SuratTugasPengisian::class, 'surat_tugas_id');
    }
}