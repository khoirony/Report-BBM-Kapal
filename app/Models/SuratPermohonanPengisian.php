<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratPermohonanPengisian extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function LaporanSisaBbm()
    {
        return $this->belongsTo(LaporanSisaBbm::class, 'laporan_sisa_bbm_id');
    }

    public function suratTugas()
    {
        return $this->hasOne(SuratTugasPengisian::class, 'surat_permohonan_id');
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

    public function penyedia()
    {
        return $this->belongsTo(User::class, 'penyedia_id');
    }

    public function invoice() 
    {
        return $this->belongsTo(RekonsiliasiInvoice::class, 'rekonsiliasi_invoice_id');
    }

    public function pencatatanHasil()
    {
        return $this->hasOne(PencatatanHasil::class, 'surat_permohonan_id'); 
    }
}