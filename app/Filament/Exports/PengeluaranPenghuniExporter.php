<?php

namespace App\Filament\Exports;

use App\Models\PengeluaranPenghuni;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PengeluaranPenghuniExporter extends Exporter
{
    protected static ?string $model = PengeluaranPenghuni::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id'),
            ExportColumn::make('penghuni_id'),
            ExportColumn::make('nama_pengeluaran'),
            ExportColumn::make('keterangan'),
            ExportColumn::make('nominal'),
            ExportColumn::make('tanggal_pengeluaran'),
            ExportColumn::make('status'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your pengeluaran penghuni export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}