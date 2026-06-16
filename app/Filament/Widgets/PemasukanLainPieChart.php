<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\PemasukanLain;

class PemasukanLainPieChart extends ChartWidget
{
    protected static ?string $heading = 'Persentase Pemasukan Berdasarkan Jenis Tagihan';

    protected function getData(): array
    {
        // 1. Mengambil data transaksi dan menjumlahkannya berdasarkan kolom 'jenis'
        $data = PemasukanLain::query()
            ->selectRaw('jenis, SUM(total) as total_dana')
            ->groupBy('jenis')
            ->get();

        // 2. Hitung total keseluruhan uang agar bisa mencari persentase di PHP
        $grandTotal = $data->sum('total_dana');

        $labels = [];
        $totals = [];

        // 3. Looping data untuk langsung menempelkan teks persen di Label-nya!
        foreach ($data as $item) {
            $persen = $grandTotal > 0 ? number_format(($item->total_dana / $grandTotal) * 100, 1) : 0;
            
            // Format label langsung jadi: "Kunci Hilang (45.5%)"
            $labels[] = $item->jenis . ' (' . $persen . '%)';
            $totals[] = $item->total_dana;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Pemasukan (IDR)',
                    'data' => $totals,
                    'backgroundColor' => [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie'; // Grafik Lingkaran
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom', // Menaruh keterangan warna di bawah grafik
                ],
            ],
            'scales' => [
                // Menghilangkan garis koordinat angka matematika yang mengganggu kemarin
                'x' => ['display' => false],
                'y' => ['display' => false],
            ],
        ];
    }
}