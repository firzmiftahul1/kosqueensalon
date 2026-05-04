<?php

namespace App\Filament\Resources\KontraksewaResource\Pages;

use App\Filament\Resources\KontraksewaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKontraksewa extends EditRecord
{
    protected static string $resource = KontraksewaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
