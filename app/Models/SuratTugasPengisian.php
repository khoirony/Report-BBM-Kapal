<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratTugasPengisian extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = ['tanggal_surat' => 'date'];

    public function suratPermohonan()
    {
        return $this->belongsTo(SuratPermohonanPengisian::class, 'surat_permohonan_id');
    }

    public function BaPengisianBbm()
    {
        return $this->belongsTo(BaPengisianBbm::class, 'laporan_pengisian_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function petugas()
    {
        return $this->hasMany(PetugasSuratTugas::class, 'surat_tugas_pengisian_id');
    }

    public function ukpd() 
    {
        return $this->belongsTo(Ukpd::class);
    }
}
