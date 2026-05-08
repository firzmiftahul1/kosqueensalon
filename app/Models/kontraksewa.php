<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KontrakSewa extends Model
{
    use HasFactory;

    protected $table = 'kontrak_sewas';

    protected $fillable = [
        'kode_kontrak',
        'penghuni_id',
        'kamar_id',
        'tanggal_masuk',
        'tanggal_keluar',
        'harga_sewa',
        'status_kontrak',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'tanggal_keluar' => 'date',
        'harga_sewa' => 'decimal:2',
    ];

    public static function getKodeKontrak(): string
    {
        $latest = self::max('kode_kontrak');

        if (!$latest) {
            return 'KTR001';
        }

        $noAkhir = (int)substr($latest, -3) + 1;
        return 'KTR' . str_pad($noAkhir, 3, '0', STR_PAD_LEFT);
    }

    protected static function booted(): void
    {
        static::creating(function (self $model): void {
            if (empty($model->kode_kontrak)) {
                $model->kode_kontrak = self::getKodeKontrak();
            }

            if (empty($model->status_kontrak)) {
                $model->status_kontrak = 'aktif';
            }
        });

        static::created(function (self $model): void {
            if ($model->kamar_id) {
                Kamar::whereKey($model->kamar_id)->update(['status_kamar' => 'terisi']);
            }
        });

        static::updating(function (self $model): void {
            if ($model->isDirty('status_kontrak') && $model->status_kontrak === 'selesai') {
                if ($model->kamar_id) {
                    Kamar::whereKey($model->kamar_id)->update(['status_kamar' => 'kosong']);
                }
            }
        });

        static::deleting(function (self $model): void {
            if ($model->kamar_id) {
                Kamar::whereKey($model->kamar_id)->update(['status_kamar' => 'kosong']);
            }
        });
    }

    public function penghuni()
    {
        return $this->belongsTo(Penghuni::class, 'penghuni_id');
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'kamar_id');
    }

    public function transaksiPembayarans()
    {
        return $this->hasMany(TransaksiPembayaran::class, 'id_kontrak');
    }
}
