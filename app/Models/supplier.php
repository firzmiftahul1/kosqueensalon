<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// tambahan
use Illuminate\Support\Facades\DB;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'supplier'; // sesuaikan dengan migration kamu

    protected $guarded = [];

    // 🔢 Generate Kode Supplier (SUP001, SUP002, dst)
    public static function getKodeSupplier()
    {
        $sql = "SELECT IFNULL(MAX(kode_supplier), 'SUP000') as kode_supplier 
                FROM supplier";
        $kodesupplier = DB::select($sql);

        foreach ($kodesupplier as $kdspl) {
            $kd = $kdspl->kode_supplier;
        }

        // ambil 3 digit terakhir
        $noawal = substr($kd, -3);
        $noakhir = $noawal + 1;

        // format jadi SUP001, SUP002, dst
        $noakhir = 'SUP' . str_pad($noakhir, 3, "0", STR_PAD_LEFT);

        return $noakhir;
    }

    // 📞 Mutator nomor telepon (hapus spasi / simbol)
    public function setNomorTeleponAttribute($value)
    {
        $this->attributes['nomor_telepon'] = str_replace([' ', '-', '.'], '', $value);
    }
}