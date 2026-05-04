<?php

namespace App\Filament\Resources\KontraksewaResource\Pages;

use App\Filament\Resources\KontraksewaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKontraksewas extends ListRecords
{
    protected static string $resource = KontraksewaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
