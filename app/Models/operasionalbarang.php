<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OperasionalBarang extends Model
{
    use HasFactory;

    protected $table = 'operasional_barang';

    protected $fillable = [
        'kode_op_barang',
        'barang_id',
        'kamar_id',
        'tanggal',
        'kegiatan',
        'biaya',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'biaya'   => 'decimal:2',
    ];

    /**
     * Relasi: Operasional ini mencatat barang apa?
     */
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    /**
     * Relasi: Operasional ini terjadi di kamar mana?
     */
    public function kamar(): BelongsTo
    {
        return $this->belongsTo(Kamar::class, 'kamar_id');
    }
}