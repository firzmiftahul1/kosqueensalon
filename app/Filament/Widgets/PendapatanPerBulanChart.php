<?php

namespace App\Filament\Widgets;

use App\Models\TransaksiPembayaran;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PendapatanPerBulanChart extends ChartWidget
{
    protected static ?string $heading = 'Arus Kas Pemasukan Bulanan';

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $year = Carbon::now()->year;
        
        // Menjumlahkan 'total_bayar' per bulan pada tahun berjalan untuk transaksi pemasukan
        $pendapatanPerBulan = TransaksiPembayaran::select(
                DB::raw('MONTH(tanggal) as bulan'),
                DB::raw('SUM(total_bayar) as total')
            )
            ->where('jenis_transaksi', 'pemasukan')
            ->whereYear('tanggal', $year)
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $data = [];
        // Mapping data dari bulan 1 sampai 12
        for ($i = 1; $i <= 12; $i++) {
            $data[] = $pendapatanPerBulan[$i] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Pendapatan Sewa (Rp)',
                    'data' => $data,
                    'fill' => 'start',
                    'backgroundColor' => '#10b98133', // Tailwind success-500 dengan opacity
                    'borderColor' => '#10b981', // Tailwind success-500
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}