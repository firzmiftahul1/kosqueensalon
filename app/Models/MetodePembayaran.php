<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodePembayaran extends Model
{
    use HasFactory;

    protected $table = 'metode_pembayaran';

    protected $primaryKey = 'id_metode';

    protected $fillable = [
        'nama_metode',
        'jenis_metode',
        'no_rekening',
        'nama_bank',
        'atas_nama',
        'status',
        'keterangan',
    ];

    public function transaksiPembayaran()
    {
        return $this->hasMany(TransaksiPembayaran::class, 'id_metode', 'id_metode');
    }
}