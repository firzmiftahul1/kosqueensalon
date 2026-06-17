<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemasukanLain extends Model
{
    use HasFactory;

    protected $table = 'pemasukan_lain';

    protected $fillable = [
        'tanggal',
        'kamar_id',
        'penghuni_id',
        'jenis',
        'jumlah',
        'harga',
        'total',
        'keterangan',
    ];

    // =========================
    // RELASI
    // =========================

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'kamar_id');
    }

    public function penghuni()
    {
        return $this->belongsTo(Penghuni::class, 'penghuni_id');
    }

    // =========================
    // AUTO HITUNG TOTAL
    // =========================

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->total = $model->jumlah * $model->harga;
        });
    }
}