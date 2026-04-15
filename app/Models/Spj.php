<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spj extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relasi ke Kapal
    public function kapal()
    {
        return $this->belongsTo(Kapal::class);
    }

    // Relasi ke User pembuat
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke UKPD
    public function ukpd()
    {
        return $this->belongsTo(Ukpd::class);
    }

    public function pemberiPersetujuanPptk()
    {
        return $this->belongsTo(User::class, 'disetujui_pptk_by');
    }

    public function pemberiPersetujuanKaUkpd()
    {
        return $this->belongsTo(User::class, 'disetujui_kepala_ukpd_by');
    }
}