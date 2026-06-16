<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\PemasukanLain; // Memanggil model PemasukanLain Anda

class PemasukanLainStats extends BaseWidget
{
    protected function getStats(): array
    {
        // 1. Menghitung total seluruh baris transaksi yang tercatat
        $totalTransaksi = PemasukanLain::count();

        // 2. Menghitung total akumulasi nominal uang (SUM) dari kolom 'total'
        $totalPendapatan = PemasukanLain::sum('total');

        return [
            // Kartu 1: Menampilkan jumlah transaksi
            Stat::make('Total Transaksi Pemasukan', $totalTransaksi)
                ->description('Jumlah riwayat transaksi pemasukan lain')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            // Kartu 2: Menampilkan total nominal uang masuk
            Stat::make('Total Nominal Pemasukan', 'Rp ' . number_format($totalPendapatan, 0, ',', '.'))
                ->description('Total akumulasi dana masuk')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('primary'),
        ];
    }
}