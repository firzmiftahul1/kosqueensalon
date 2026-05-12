<?php

namespace App\Filament\Resources\OperasionalbarangResource\Pages;

use App\Filament\Resources\OperasionalbarangResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOperasionalbarang extends EditRecord
{
    protected static string $resource = OperasionalbarangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
