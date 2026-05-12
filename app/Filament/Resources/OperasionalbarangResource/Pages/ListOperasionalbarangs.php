<?php

namespace App\Filament\Resources\OperasionalbarangResource\Pages;

use App\Filament\Resources\OperasionalbarangResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOperasionalbarangs extends ListRecords
{
    protected static string $resource = OperasionalbarangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
