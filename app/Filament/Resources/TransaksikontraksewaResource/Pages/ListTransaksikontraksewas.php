<?php

namespace App\Filament\Resources\TransaksikontraksewaResource\Pages;

use App\Filament\Resources\TransaksikontraksewaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransaksikontraksewa extends ListRecords
{
    protected static string $resource = TransaksikontraksewaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
