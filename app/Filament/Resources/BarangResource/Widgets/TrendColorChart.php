<?php

namespace App\Filament\Resources\BarangResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\MarketTrend;

class TrendColorChart extends ChartWidget
{
    public function getHeading(): string
    {
        $targetYear = now()->year;
        return "Top 10 Tren Barang Kos {$targetYear}";
    }

    protected int | string | array $columnSpan = '1';    

    protected function getData(): array
    {
        // 1. Ambil data warna dari database menggunakan model MarketTrend
        $rawText = MarketTrend::whereYear('created_at', now()->year)
                                ->pluck('warna_populer')
                                ->implode(',');

        // 2. Pecah string berdasarkan koma (,) menggunakan Regex
        $phrasesArray = preg_split('/\s*,\s*/', strtolower($rawText), -1, PREG_SPLIT_NO_EMPTY);
        
        // 3. Bersihkan sisa spasi atau titik di ujung teks
        $cleanPhrases = array_map(function($phrase) {
            return trim($phrase, " \t\n\r\0\x0B.");
        }, $phrasesArray);

        // 4. Filter frasa yang terlalu pendek atau tidak relevan
        $stopWords = ['warna', 'warna-warna', 'seperti', 'dan'];
        $filteredPhrases = array_filter($cleanPhrases, function($phrase) use ($stopWords) {
            return strlen($phrase) > 2 && !in_array($phrase, $stopWords);
        });

        // 5. Hitung frekuensi frasa dan ambil 10 besar
        $counts = array_count_values($filteredPhrases);
        arsort($counts);
        $topTen = array_slice($counts, 0, 10);

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Rekomendasi',
                    'data' => array_values($topTen),
                    'backgroundColor' => [
                        '#94a3b8', '#f87171', '#fbbf24', '#34d399', '#60a5fa', 
                        '#818cf8', '#a78bfa', '#f472b6', '#fb923c', '#2dd4bf'
                    ],
                ],
            ],
            'labels' => array_map('ucwords', array_keys($topTen)),
        ];
    }

    protected function getType(): string
    {
        // Tetap menggunakan grafik horizontal bar agar rapi
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'scales' => [
                'x' => [
                    'ticks' => [
                        'stepSize' => 1,
                        'precision' => 0,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }
}