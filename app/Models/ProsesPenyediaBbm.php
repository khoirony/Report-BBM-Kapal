<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProsesPenyediaBbm extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi balik ke Surat Permohonan
    public function suratPermohonan()
    {
        return $this->belongsTo(SuratPermohonanPengisian::class, 'surat_permohonan_id');
    }

    // Relasi ke User Penyedia
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}