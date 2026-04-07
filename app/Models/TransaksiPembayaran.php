<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TransaksiPembayaran extends Model
{
    use HasFactory;

    protected $table = 'transaksi_pembayaran';

    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'tanggal',
        'id_penghuni',
        'id_kontrak',
        'id_metode',
        'id_supplier',
        'jenis_transaksi',
        'total_bayar',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total_bayar' => 'decimal:2',
    ];

    // 🔢 Generate Kode Transaksi (TRX001, TRX002, dst)
    public static function getKodeTransaksi()
    {
        $latest = self::max('id_transaksi');

        if (!$latest) {
            return 'TRX001';
        }

        $noAkhir = (int)$latest + 1;
        return 'TRX' . str_pad($noAkhir, 3, "0", STR_PAD_LEFT);
    }

    // Relasi ke Metode Pembayaran
    public function metodePembayaran()
    {
        return $this->belongsTo(MetodePembayaran::class, 'id_metode', 'id_metode');
    }

    // Relasi ke Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier', 'id');
    }
}