<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penghuni extends Model
{
    use HasFactory;

    // Nama tabel (opsional kalau beda dari default)
    protected $table = 'penghuni';

    // Primary key (opsional)
    protected $primaryKey = 'id';

    // Field yang boleh diisi (WAJIB untuk insert data)
    protected $fillable = [
        'nama',
        'alamat',
        'no_hp',
        'jenis_kelamin',
    ];

    // Kalau tidak pakai created_at & updated_at
    // public $timestamps = false;
}