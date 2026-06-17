<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KamarResource\Pages;
use App\Models\Kamar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

// components
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

use Filament\Tables\Columns\TextColumn;

class KamarResource extends Resource
{
    protected static ?string $model = Kamar::class;

    protected static ?string $navigationIcon = 'heroicon-o-home';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
                TextInput::make('kode_kamar')
                    ->default(fn () => Kamar::getKodeKamar())
                    ->label('Kode Kamar')
                    ->required()
                    ->readonly(),

                TextInput::make('nama_kamar')
                    ->required()
                    ->placeholder('Masukkan nama kamar'),

                TextInput::make('harga')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->prefix('Rp')
                    ->placeholder('Masukkan harga sewa'),

                Select::make('status_kamar')
                    ->options([
                        'kosong' => 'Kosong',
                        'terisi' => 'Terisi',
                        
                    ])
                    ->required()
                    ->placeholder('Pilih status kamar'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                
                TextColumn::make('kode_kamar')
                    ->searchable(),

                TextColumn::make('nama_kamar')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('harga')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('status_kamar')
                    ->badge()
                    ->colors([
                        'success' => 'kosong',
                        'danger' => 'terisi',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKamars::route('/'),
            'create' => Pages\CreateKamar::route('/create'),
            'edit' => Pages\EditKamar::route('/{record}/edit'),
        ];
    }
}