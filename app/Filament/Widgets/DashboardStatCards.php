<?php

namespace App\Filament\Widgets;

use App\Models\TransaksiPembayaran;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatCards extends BaseWidget
{
    protected function getStats(): array
    {
        // Menggunakan kolom 'total_bayar' dan 'jenis_transaksi' = 'pemasukan' 
        // sesuai dengan struktur database dan model TransaksiPembayaran yang kita buat sebelumnya.
        $totalPendapatan = TransaksiPembayaran::where('jenis_transaksi', 'pemasukan')->sum('total_bayar');
        
        $totalTransaksi = TransaksiPembayaran::where('jenis_transaksi', 'pemasukan')->count();
        
        $rataRata = $totalTransaksi > 0 ? $totalPendapatan / $totalTransaksi : 0;

        return [
            Stat::make('Total Pendapatan Sewa', 'Rp ' . number_format($totalPendapatan, 0, ',', '.'))
                ->description('Pemasukan Kos (Lunas)')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Transaksi', $totalTransaksi)
                ->description('Jumlah Catatan Pemasukan')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('primary'),

            Stat::make('Rata-rata Pembayaran', 'Rp ' . number_format($rataRata, 0, ',', '.'))
                ->description('Rata-rata Nominal per Transaksi')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('warning'),
        ];
    }
}