<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PencatatanHasil extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function suratPermohonan() {
        return $this->belongsTo(SuratPermohonanPengisian::class, 'surat_permohonan_id');
    }
    
    public function kapal() {
        return $this->belongsTo(Kapal::class);
    }
    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }
}