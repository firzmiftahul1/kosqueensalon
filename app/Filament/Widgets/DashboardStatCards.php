<?php

namespace App\Filament\Widgets;

use App\Models\OperasionalBarang;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class DashboardStatCards extends BaseWidget
{
    // Trait ini penting agar widget bisa membaca filter dari halaman Dashboard
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        // 1. Ambil data tanggal dari filter halaman
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        // 2. Query dasar untuk total biaya operasional
        $queryTotal = OperasionalBarang::query();
        $queryBulanIni = OperasionalBarang::query()->whereMonth('tanggal', Carbon::now()->month)->whereYear('tanggal', Carbon::now()->year);
        $queryTahunIni = OperasionalBarang::query()->whereYear('tanggal', Carbon::now()->year);

        // 3. Terapkan filter range tanggal jika diisi oleh user
        if ($startDate) {
            $queryTotal->whereDate('tanggal', '>=', Carbon::parse($startDate));
        }
        if ($endDate) {
            $queryTotal->whereDate('tanggal', '<=', Carbon::parse($endDate));
        }

        // 4. Hitung total biayanya
        $totalBiayaFilter = $queryTotal->sum('biaya');
        $totalBulanIni = $queryBulanIni->sum('biaya');
        $totalTahunIni = $queryTahunIni->sum('biaya');

        // Label dinamis untuk memperjelas range yang sedang dipilih
        $labelTotal = "Total Biaya Operasional";
        if ($startDate && $endDate) {
            $labelTotal .= " (" . Carbon::parse($startDate)->format('d/m/Y') . " - " . Carbon::parse($endDate)->format('d/m/Y') . ")";
        } elseif ($startDate) {
            $labelTotal .= " (Sejak " . Carbon::parse($startDate)->format('d/m/Y') . ")";
        } elseif ($endDate) {
            $labelTotal .= " (Hingga " . Carbon::parse($endDate)->format('d/m/Y') . ")";
        } else {
            $labelTotal .= " (Semua Periode)";
        }

        return [
            Stat::make($labelTotal, 'Rp ' . number_format($totalBiayaFilter, 0, ',', '.'))
                ->description('Berdasarkan filter tanggal')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary'),

            Stat::make('Operasional Bulan Ini', 'Rp ' . number_format($totalBulanIni, 0, ',', '.'))
                ->description('Periode: ' . Carbon::now()->translatedFormat('F Y'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Operasional Tahun Ini', 'Rp ' . number_format($totalTahunIni, 0, ',', '.'))
                ->description('Periode: Tahun ' . Carbon::now()->year)
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('warning'),
        ];
    }
}