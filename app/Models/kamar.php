<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Kamar extends Model
{
    use HasFactory;

    protected $table = 'kamars';

    protected $fillable = [
        'kode_kamar',
        'nama_kamar',
        'tipe_kamar',
        'harga',
        'status_kamar'
    ];

    public static function getKodeKamar()
    {
        // Ambil nilai kode_kamar terbesar saja
        $latest = self::max('kode_kamar');

        // Jika data masih kosong, set ke KM000
        if (!$latest) {
            $latest = 'KM000';
        }

        // Ambil 3 angka terakhir, tambah 1
        $noAwal = substr($latest, -3);
        $noAkhir = (int)$noAwal + 1;

        // Susun kode baru
        return 'KM' . str_pad($noAkhir, 3, "0", STR_PAD_LEFT);
    }
}