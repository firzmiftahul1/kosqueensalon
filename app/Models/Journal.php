<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    protected $guarded = []; // Biar semua kolom (tanggal_jurnal, debet, dll) bisa masuk

    public function coa()
    {
        return $this->belongsTo(Coa::class, 'coa_id');
    }
}