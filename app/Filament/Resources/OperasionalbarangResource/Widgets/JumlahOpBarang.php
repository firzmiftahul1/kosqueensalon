<?php

namespace App\Filament\Resources\OperasionalbarangResource\Widgets;

use App\Models\OperasionalBarang;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class JumlahOpBarang extends ChartWidget
{
    protected static ?string $heading = 'Grafik Pengeluaran & Total Operasional per Barang';

    protected function getData(): array
    {
        // 1. Ambil data dengan grouping berdasarkan barang_id dan join ke tabel barang untuk dapat nama_barang
        $data = OperasionalBarang::query()
            ->join('barang', 'operasional_barang.barang_id', '=', 'barang.id') // Pastikan nama tabel barang Anda sesuai (misal: 'barangs' atau 'barang')
            ->select(
                'barang.nama_barang',
                DB::raw('SUM(operasional_barang.biaya) as total_biaya'),
                DB::raw('COUNT(operasional_barang.id) as total_count')
            )
            ->groupBy('operasional_barang.barang_id', 'barang.nama_barang')
            ->get();

        // 2. Pecah data menjadi array untuk label dan masing-masing dataset
        $labels = $data->pluck('nama_barang')->toArray();
        $biayaData = $data->pluck('total_biaya')->toArray();
        $countData = $data->pluck('total_count')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Total Pengeluaran (Rp)',
                    'data' => $biayaData,
                    'backgroundColor' => '#f59e0b', // Warna Oranye/Kuning
                    'borderColor' => '#d97706',
                ],
                [
                    'label' => 'Jumlah Operasional (Kali)',
                    'data' => $countData,
                    'backgroundColor' => '#3b82f6', // Warna Biru
                    'borderColor' => '#2563eb',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}