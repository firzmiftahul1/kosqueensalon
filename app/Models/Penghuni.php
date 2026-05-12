<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Penghuni extends Model
{
    use HasFactory;

    protected $table = 'penghunis';

    protected $fillable = [
        'kode_penghuni',
        'nama_penghuni',
        'nik',
        'nomor_telepon',
        'alamat',
        'jenis_kelamin',
    ];

    public static function getKodePenghuni(): string
    {
        $latest = self::max('kode_penghuni');

        if (!$latest) {
            return 'PNG001';
        }

        $noAkhir = (int)substr($latest, -3) + 1;
        return 'PNG' . str_pad($noAkhir, 3, '0', STR_PAD_LEFT);
    }

    protected static function booted(): void
    {
        static::creating(function (self $model): void {
            if (empty($model->kode_penghuni)) {
                $model->kode_penghuni = self::getKodePenghuni();
            }
        });
    }

    public function kontrakSewas()
    {
        return $this->hasMany(KontrakSewa::class, 'penghuni_id');
    }

    public function transaksiPembayarans()
    {
        return $this->hasMany(TransaksiPembayaran::class, 'id_penghuni');
    }

    public function setNomorTeleponAttribute($value): void
    {
        $this->attributes['nomor_telepon'] = str_replace([' ', '-', '.'], '', (string) $value);
    }
}