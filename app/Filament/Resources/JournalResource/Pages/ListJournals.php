<?php

namespace App\Filament\Resources\JournalResource\Pages;

use App\Filament\Resources\JournalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJournals extends ListRecords
{
    protected static string $resource = JournalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // === TAMBAHKAN KODE INI AGAR GRAFIK AI MUNCUL DI ATAS TABEL JOURNAL ===
    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Resources\JournalResource\Widgets\JurnalUmumAnalyticChart::class,
        ];
    }
}