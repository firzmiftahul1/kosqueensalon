<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KontrakSewa extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'kontrak_sewa';

    // Mengizinkan semua field diisi (karena kamu pakai guarded kosong)
    protected $guarded = [];

    /**
     * Relasi ke model Penghuni
     */
    public function penghuni()
{
    // Mengarahkan foreign key ke id_penghuni sesuai file SQL
    return $this->belongsTo(Penghuni::class, 'id_penghuni');
}

public function kamars()
{
    // Mengarahkan foreign key ke id_kamar sesuai file SQL
    return $this->belongsTo(Kamar::class, 'id_kamars');
}
}