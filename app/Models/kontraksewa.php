<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kontraksewa extends Model
{
    use HasFactory;
    protected $table = 'kontraksewa';

    protected $guarded = [];

    public function penghuni() {
    return $this->belongsTo(Penghuni::class, 'id_penghuni');
}

public function kamar() {
    return $this->belongsTo(Kamar::class, 'id_kamar');
}

protected $primaryKey = 'id_kontrak';
}
