<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $guarded = [];

    // INI JEMBATAN OTOMATISNYA
    protected static function booted()
    {
        static::created(function ($purchase) {
            // 1. BARIS PERTAMA (DEBET)
            // Biasanya masuk ke akun barang yang dibeli (sesuai coa_id yang dipilih)
            \App\Models\Journal::create([
                'transaction_date' => $purchase->purchase_date,
                'reference_no'     => 'PURCH-' . $purchase->id,
                'description'      => 'Pembelian: ' . $purchase->item_name . ' (Debet)',
                'debit'            => $purchase->amount,
                'credit'           => 0,
                'coa_id'           => $purchase->coa_id, 
            ]);

            // 2. BARIS KEDUA (KREDIT)
            // Biasanya otomatis ngurangin Kas/Bank. 
            // Kamu bisa ganti angka '1' di coa_id bawah ini dengan ID Akun Kas kamu di database
            \App\Models\Journal::create([
                'transaction_date' => $purchase->purchase_date,
                'reference_no'     => 'PURCH-' . $purchase->id,
                'description'      => 'Pembelian: ' . $purchase->item_name . ' (Kredit)',
                'debit'            => 0,
                'credit'           => $purchase->amount,
                'coa_id'           => 1, // <--- GANTI INI dengan ID Akun Kas/Bank kamu
            ]);
        });
    }
    public function coa() {
        return $this->belongsTo(Coa::class, 'coa_id');
    }
}