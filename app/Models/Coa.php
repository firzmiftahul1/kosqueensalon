<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coa extends Model
{
    protected $table = 'coas'; // Pastikan nama tabel di phpMyAdmin 'coas'
    protected $guarded = [];

    // Relasi kalau nanti dibutuhkan
    public function purchases() {
        return $this->hasMany(Purchase::class);
    }
}