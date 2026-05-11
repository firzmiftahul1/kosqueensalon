<?php

namespace App\Filament\Resources\PengeluaranPenghuniResource\Pages;

use App\Filament\Resources\PengeluaranPenghuniResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengeluaranPenghunis extends ListRecords
{
    protected static string $resource = PengeluaranPenghuniResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
