<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekonsiliasiInvoice extends Model
{
    protected $guarded = ['id'];

    public function penyedia() {
        return $this->belongsTo(User::class, 'penyedia_id');
    }

    public function ukpd() {
        return $this->belongsTo(Ukpd::class, 'ukpd_id');
    }

    public function suratPermohonan() {
        return $this->hasMany(SuratPermohonanPengisian::class, 'rekonsiliasi_invoice_id');
    }
}