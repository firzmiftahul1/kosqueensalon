<?php

namespace App\Filament\Widgets;

use App\Models\Penghuni;
use Filament\Widgets\ChartWidget;

class TrendMaterialChart extends ChartWidget
{
    protected static ?string $heading = 'Penghuni per Jenis Kelamin';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $lakiLaki = Penghuni::where('jenis_kelamin', 'Laki-laki')->count();
        $perempuan = Penghuni::where('jenis_kelamin', 'Perempuan')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Penghuni',
                    'data' => [$lakiLaki, $perempuan],
                    'backgroundColor' => ['#36A2EB', '#FF6384'],
                ],
            ],
            'labels' => ['Laki-laki', 'Perempuan'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}