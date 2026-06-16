<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoaResource\Pages;
use App\Filament\Resources\CoaResource\RelationManagers;
use App\Models\Coa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CoaResource extends Resource
{
   protected static ?string $modelLabel = 'Coa';
protected static ?string $pluralModelLabel = 'Coa';
protected static ?string $navigationLabel = 'Coa';
protected static ?string $navigationIcon = 'heroicon-o-list-bullet'; // Logo daftar

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\TextInput::make('kode_coa')
                ->required(),
            Forms\Components\TextInput::make('nama_coa')
                ->required(),
            
            // INI PERUBAHANNYA: Jadi Dropdown Pilihan
            Forms\Components\Select::make('header')
                ->label('Header Akun')
                ->options([
                    '1' => '1 - Asset',
                    '2' => '2 - Kewajiban',
                    '3' => '3 - Modal',
                    '4' => '4 - Pendapatan',
                    '5' => '5 - Beban',
                ])
                ->required()
                ->native(false), // Biar tampilannya lebih modern

            Forms\Components\Select::make('posisi')
                ->label('Posisi Normal')
                ->options([
                    'Debit' => 'Debit',
                    'Kredit' => 'Kredit',
                ])
                ->required(),
        ]);
}

   public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('kode_coa')
                ->label('Kode')
                ->sortable(),
            Tables\Columns\TextColumn::make('nama_coa')
                ->label('Nama Akun')
                ->searchable(),
            Tables\Columns\TextColumn::make('header')
    ->label('Header')
    ->formatStateUsing(fn (string $state): string => match ($state) {
        '1' => '1 - Asset',
        '2' => '2 - Kewajiban',
        '3' => '3 - Modal',
        '4' => '4 - Pendapatan',
        '5' => '5 - Beban',
        default => $state,
    }),
            Tables\Columns\TextColumn::make('posisi') // SESUAI DATABASE KAMU
                ->label('Posisi')
                ->badge() // Biar keren ada warnanya
                ->color(fn (string $state): string => match ($state) {
                    'Debit' => 'success',
                    'Kredit' => 'danger',
                    default => 'gray',
                }),
        ])
        ->filters([])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoas::route('/'),
            'create' => Pages\CreateCoa::route('/create'),
            'edit' => Pages\EditCoa::route('/{record}/edit'),
        ];
    }
}
