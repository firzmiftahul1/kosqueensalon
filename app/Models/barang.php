<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    // Mendefinisikan nama tabel secara eksplisit karena nama tabelnya 'barang' (bukan barangs)
    protected $table = 'barang';

    /**
     * Kolom yang boleh diisi secara massal.
     * Sesuaikan dengan kolom yang ada di migrasi kamu.
     */
    protected $fillable = [
        'kode_barang',
        'nama_barang',
    ];
    public function operasionals()
{
    return $this->hasMany(OperasionalBarang::class, 'barang_id');
}
}