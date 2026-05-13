<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranPenghuni extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran_penghunis';

    protected $fillable = [
        'penghuni_id',
        'nama_pengeluaran',
        'keterangan',
        'nominal',
        'tanggal_pengeluaran',
        'status',
    ];

    /**
     * Relasi ke model Penghuni
     */
    public function penghuni()
    {
        return $this->belongsTo(Penghuni::class);
    }
}