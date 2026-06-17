<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranPenghuni extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran_penghunis'; // ← diubah, hapus huruf "s"

    protected $fillable = [
        'penghuni_id',
        'nama_pengeluaran',
        'keterangan',
        'nominal',
        'tanggal_pengeluaran',
        'status',
    ];

    public function penghuni()
    {
        return $this->belongsTo(Penghuni::class);
    }
}