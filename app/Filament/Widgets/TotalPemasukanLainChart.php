<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\PemasukanLain; // Pastikan memanggil model PemasukanLain Anda

class TotalPemasukanLainChart extends ChartWidget
{
    // 1. Mengatur Judul Grafik yang akan tampil di Dashboard
    protected static ?string $heading = 'Total Pemasukan Lain Berdasarkan Jenis Tagihan';

  protected function getData(): array
{
    // Mengambil data dari tabel pemasukan_lain menggunakan kolom 'jenis' yang benar
    $data = PemasukanLain::query()
        ->selectRaw('jenis, SUM(total) as total_pemasukan')
        ->groupBy('jenis')
        ->get();

    // Memisahkan hasil query ke dalam array label dan total nominal
    $labels = $data->pluck('jenis')->toArray(); // Akan menghasilkan ['Kunci hilang', 'Pembayaran AC', dsb]
    $totals = $data->pluck('total_pemasukan')->toArray(); 

    return [
        'datasets' => [
            [
                'label' => 'Total Pendapatan (IDR)',
                'data' => $totals,
                'backgroundColor' => [
                    '#36A2EB', '#FF6384', '#FFCE56', '#4BC0C0', '#9966FF'
                ],
            ],
        ],
        'labels' => $labels,
    ];
}

    protected function getType(): string
    {
        return 'bar'; // Menentukan tipe chart adalah bar (batang)
    }
}