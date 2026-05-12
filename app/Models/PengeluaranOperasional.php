<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengeluaranOperasional extends Model
{
    protected $table = 'pengeluaran_operasional';
    
    protected $fillable = [
        'kode_pengeluaran',
        'kode_supplier',
        'tanggal',
        'jenis_pengeluaran',
        'jumlah',
        'keterangan',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'kode_supplier', 'kode_supplier');
    }

    public static function getKodePengeluaran()
    {
        $last = self::orderBy('id', 'desc')->first();

        if (!$last) {
            return 'OPS001';
        }

        $number = intval(substr($last->kode_pengeluaran, 3)) + 1;

        return 'OPS' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
}