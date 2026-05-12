<?php

namespace App\Filament\Resources\KontrakSewaResource\Pages;

use App\Filament\Resources\KontrakSewaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKontrakSewa extends EditRecord
{
    protected static string $resource = KontrakSewaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
