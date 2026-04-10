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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function files()
    {
        return $this->hasMany(FileSuratPermohonan::class, 'surat_permohonan_id');
    }

    public function prosesPenyedia()
    {
        return $this->hasOne(ProsesPenyediaBbm::class, 'surat_permohonan_id');
    }
}