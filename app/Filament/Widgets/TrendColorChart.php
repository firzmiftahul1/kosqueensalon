<?php

namespace App\Filament\Widgets;

use App\Models\Penghuni;
use Filament\Widgets\ChartWidget;

class TrendColorChart extends ChartWidget
{
    protected static ?string $heading = 'Data Penghuni Berdasarkan Asal Kota';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        // Ambil data penghuni dan kelompokkan berdasarkan kota (alamat)
        $data = Penghuni::selectRaw('alamat, COUNT(*) as total')
            ->groupBy('alamat')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $bgColors = ['#FF6384','#36A2EB','#FFCE56','#4BC0C0','#9966FF',
                     '#FF9F40','#C9CBCF','#7BC8A4','#E7E9ED','#FF6384'];

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Penghuni',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => array_slice($bgColors, 0, count($data)),
                ],
            ],
            'labels' => $data->pluck('alamat')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}