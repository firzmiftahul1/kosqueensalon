<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KontraksewaResource\Pages;
use App\Models\Kontraksewa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class KontraksewaResource extends Resource
{
    protected static ?string $model = Kontraksewa::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    // Memberikan label yang lebih rapi di navigasi
    protected static ?string $navigationLabel = 'Kontrak Sewa';
    protected static ?string $modelLabel = 'Kontrak Sewa';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        // Relasi ke Penghuni
                        Select::make('id_penghuni')
                            ->relationship('penghuni', 'nama_penghuni') // Pastikan relasi 'penghuni' ada di Model
                            ->searchable()
                            ->preload()
                            ->required(),

                        // Relasi ke Kamar
                        Select::make('id_kamar')
                            ->relationship('kamar', 'nomor_kamar') // Pastikan relasi 'kamar' ada di Model
                            ->searchable()
                            ->preload()
                            ->required(),

                        DatePicker::make('tanggal_mulai')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y'),

                        DatePicker::make('tanggal_selesai')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y'),

                        Select::make('status')
                            ->options([
                                'Aktif' => 'Aktif',
                                'Selesai' => 'Selesai',
                                'Batal' => 'Batal',
                            ])
                            ->required()
                            ->default('Aktif'),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_kontrak')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('penghuni.nama_penghuni')
                    ->label('Nama Penghuni')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kamar.nomor_kamar')
                    ->label('No. Kamar')
                    ->sortable(),

                TextColumn::make('tanggal_mulai')
                    ->date()
                    ->sortable(),

                TextColumn::make('tanggal_selesai')
                    ->date()
                    ->sortable(),

                // Menggunakan Badge agar status terlihat lebih menarik
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Aktif' => 'success',
                        'Selesai' => 'gray',
                        'Batal' => 'danger',
                        default => 'warning',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Aktif' => 'Aktif',
                        'Selesai' => 'Selesai',
                        'Batal' => 'Batal',
                    ]),
            ])
            ->actions([
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKontraksewas::route('/'),
            'create' => Pages\CreateKontraksewa::route('/create'),
            'edit' => Pages\EditKontraksewa::route('/{record}/edit'),
        ];
    }
}