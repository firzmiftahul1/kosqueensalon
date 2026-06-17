<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketTrend extends Model
{
    use HasFactory;

    // Menyebutkan nama tabel secara eksplisit agar mengarah ke tabel hasil migration kamu
    protected $table = 'market_trend_barangs';

    // Kolom yang diizinkan untuk diisi secara massal
    protected $fillable = [
        'nama_tren',
        'analisis_ai',
        'referensi_visual',
        'saran_barang',
        'warna_populer',
    ];

    // Otomatis mengubah JSON database menjadi Array di PHP
    protected $casts = [
        'referensi_visual' => 'array',
    ];
}