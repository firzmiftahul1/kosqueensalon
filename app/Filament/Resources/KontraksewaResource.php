<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KontrakSewaResource\Pages;
use App\Models\KontrakSewa;
use App\Models\Kamar;
use App\Models\MetodePembayaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Tables\Columns\TextColumn;

class KontrakSewaResource extends Resource
{
    protected static ?string $model = KontrakSewa::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    // Memberikan label yang lebih rapi di navigasi
    protected static ?string $navigationLabel = 'Kontrak Sewa';
    protected static ?string $modelLabel = 'Kontrak Sewa';
    protected static ?string $pluralModelLabel = 'Kontrak Sewa';
    protected static ?string $navigationGroup = 'Operasional';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Penghuni')
                        ->schema([
                            TextInput::make('kode_kontrak')
                                ->label('Kode Kontrak')
                                ->default(fn () => KontrakSewa::getKodeKontrak())
                                ->required()
                                ->readonly(),

                            Select::make('penghuni_id')
                                ->label('Penghuni')
                                ->relationship('penghuni', 'nama_penghuni')
                                ->searchable()
                                ->preload()
                                ->required(),
                        ])->columns(2),

                    Step::make('Kamar')
                        ->schema([
                            Select::make('kamar_id')
                                ->label('Kamar Kosong')
                                ->options(function (?KontrakSewa $record) {
                                    return Kamar::query()
                                        ->where('status_kamar', 'kosong')
                                        ->when(
                                            $record?->kamar_id,
                                            fn ($query) => $query->orWhere('id', $record->kamar_id)
                                        )
                                        ->orderBy('kode_kamar')
                                        ->pluck('kode_kamar', 'id');
                                })
                                ->searchable()
                                ->required(),
                        ]),

                    Step::make('Pembayaran')
                        ->schema([
                            Select::make('id_metode')
                                ->label('Metode Pembayaran')
                                ->options(
                                    MetodePembayaran::where('status', 'aktif')
                                        ->orderBy('nama_metode')
                                        ->pluck('nama_metode', 'id_metode')
                                )
                                ->searchable()
                                ->required(fn (string $operation): bool => $operation === 'create')
                                ->visible(fn (string $operation): bool => $operation === 'create'),

                            TextInput::make('harga_sewa')
                                ->label('Harga Sewa')
                                ->required()
                                ->numeric()
                                ->minValue(0)
                                ->prefix('Rp'),

                            DatePicker::make('tanggal_masuk')
                                ->label('Tanggal Masuk')
                                ->required()
                                ->native(false)
                                ->displayFormat('d/m/Y'),

                            DatePicker::make('tanggal_keluar')
                                ->label('Tanggal Keluar')
                                ->native(false)
                                ->displayFormat('d/m/Y')
                                ->nullable(),

                            Select::make('status_kontrak')
                                ->label('Status Kontrak')
                                ->options([
                                    'aktif' => 'Aktif',
                                    'selesai' => 'Selesai',
                                ])
                                ->default('aktif')
                                ->required(),
                        ])->columns(2),
                ])->skippable(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_kontrak')
                    ->label('Kode')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('penghuni.nama_penghuni')
                    ->label('Penghuni')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kamar.kode_kamar')
                    ->label('Kamar')
                    ->sortable(),

                TextColumn::make('tanggal_masuk')
                    ->date()
                    ->sortable(),

                TextColumn::make('tanggal_keluar')
                    ->date()
                    ->sortable(),

                TextColumn::make('harga_sewa')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),

                // Menggunakan Badge agar status terlihat lebih menarik
                TextColumn::make('status_kontrak')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'selesai' => 'gray',
                        default => 'warning',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_kontrak')
                    ->options([
                        'aktif' => 'Aktif',
                        'selesai' => 'Selesai',
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