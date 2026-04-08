<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanSisaBbm extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function sounding() 
    {
        return $this->belongsTo(Sounding::class);
    }

    public function ukpd() 
    {
        return $this->belongsTo(Ukpd::class);
    }
}
