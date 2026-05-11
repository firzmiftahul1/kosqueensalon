<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiKontrakSewa extends Model
{
    use HasFactory;

    // Tambahkan baris ini untuk menetapkan nama tabel secara manual
    protected $table = 'transaksikontraksewa';

    // Pastikan juga fillable sudah diisi agar data bisa masuk
    protected $fillable = [
        'kode_transaksi_kontraksewa',
        'id_kontrak_sewa',
        'id_penghuni',
        'id_kamars',
        'nominal',
        'keterangan',
    ];

    // Definisi Relasi (Sangat penting agar Select di Resource tidak error)
    public function kontrak_sewa()
    {
        return $this->belongsTo(KontrakSewa::class, 'id_kontrak_sewa');
    }

    public function penghuni()
    {
        return $this->belongsTo(Penghuni::class, 'id_penghuni');
    }

    public function kamars()
    {
        return $this->belongsTo(Kamar::class, 'id_kamars');
    }
}