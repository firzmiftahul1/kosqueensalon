<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenghuniResource\Pages;
use App\Models\Penghuni;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PenghuniResource extends Resource
{
    protected static ?string $model = Penghuni::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kode_penghuni')
                    ->label('Kode Penghuni')
                    ->default(fn () => Penghuni::getKodePenghuni())
                    ->required()
                    ->readonly()
                    ->maxLength(10),

                Forms\Components\TextInput::make('nama_penghuni')
                    ->label('Nama Penghuni')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('nik')
                    ->label('NIK')
                    ->required()
                    ->maxLength(20)
                    ->unique(ignoreRecord: true),

                Forms\Components\Textarea::make('alamat')
                    ->required(),

                Forms\Components\TextInput::make('nomor_telepon')
                    ->label('Nomor Telepon')
                    ->required()
                    ->maxLength(20),

                Forms\Components\Select::make('jenis_kelamin')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_penghuni')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_penghuni')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nomor_telepon')
                    ->label('Telepon'),

                Tables\Columns\TextColumn::make('alamat')
                    ->limit(30),

                Tables\Columns\TextColumn::make('jenis_kelamin'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(), // 🔥 tambahin ini biar bisa hapus
            ])
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
            'index' => Pages\ListPenghunis::route('/'),
            'create' => Pages\CreatePenghuni::route('/create'),
            'edit' => Pages\EditPenghuni::route('/{record}/edit'),
        ];
    }
}