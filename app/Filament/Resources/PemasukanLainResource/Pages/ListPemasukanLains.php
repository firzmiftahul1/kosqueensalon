<?php

namespace App\Filament\Resources\PemasukanLainResource\Pages;

use App\Filament\Resources\PemasukanLainResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPemasukanLains extends ListRecords
{
    protected static string $resource = PemasukanLainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
