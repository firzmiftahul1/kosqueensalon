<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransaksiKontrakSewaResource\Pages;
use App\Models\TransaksiKontrakSewa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

class TransaksiKontrakSewaResource extends Resource
{
    protected static ?string $model = TransaksiKontrakSewa::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $pluralModelLabel = 'Transaksi Kontrak Sewa';
    
    protected static ?string $navigationLabel = 'Transaksi Kontrak Sewa';
    
    // Kelompokkan dalam navigasi Transaksi sesuai standar modul
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    // Step 1: Identitas Transaksi (Sesuai tahap 'Pesanan' di modul)
                    Wizard\Step::make('Informasi Transaksi')
                        ->icon('heroicon-m-document-text')
                        ->schema([
                            Section::make('Faktur Sewa')
                                ->schema([
                                    TextInput::make('kode_transaksi_kontraksewa')
                                        ->required()
                                        ->unique(ignoreRecord: true)
                                        ->label('Kode Transaksi'),
                                    Select::make('id_kontrak_sewa')
                                        ->relationship('kontrak_sewa', 'id')
                                        ->required()
                                        ->searchable()
                                        ->label('Kontrak Sewa'),
                                ])->columns(2),
                        ]),

                    // Step 2: Detail Penghuni & Kamar
                    Wizard\Step::make('Penghuni & Kamar')
                        ->icon('heroicon-m-user')
                        ->schema([
                            Section::make('Alokasi Kamar')
                                ->schema([
                                    Select::make('id_penghuni')
                                        ->relationship('penghuni', 'nama')
                                        ->required()
                                        ->searchable()
                                        ->label('Penghuni'),
                                    Select::make('id_kamars')
                                        ->relationship('kamars', 'kode_kamar')
                                        ->required()
                                        ->searchable()
                                        ->label('Kamar'),
                                ])->columns(2),
                        ]),

                    // Step 3: Pembayaran (Sesuai tahap 'Pembayaran' di modul)
                    Wizard\Step::make('Pembayaran')
                        ->icon('heroicon-m-banknotes')
                        ->schema([
                            Section::make('Detail Pembayaran')
                                ->schema([
                                    TextInput::make('nominal')
                                        ->numeric()
                                        ->prefix('Rp')
                                        ->required()
                                        ->label('Nominal Pembayaran'),
                                    Textarea::make('keterangan')
                                        ->required()
                                        ->columnSpanFull(),
                                ]),
                        ]),
                ])->columnSpanFull(), // Agar tampilan wizard lebar penuh
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_transaksi_kontraksewa')
                    ->searchable()
                    ->sortable()
                    ->label('Kode'),
                TextColumn::make('penghuni.nama')
                    ->sortable()
                    ->label('Penghuni'),
                TextColumn::make('kamars.kode_kamar')
                    ->label('Kamar'),
                TextColumn::make('nominal')
                    ->money('idr') // Format Rupiah
                    ->sortable(),
                TextColumn::make('keterangan')
                    ->label('Keterangan'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Tgl Transaksi')
                    ->sortable(),
            ])
            ->filters([
                //
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransaksikontraksewa::route('/'),
            'create' => Pages\CreateTransaksikontraksewa::route('/create'),
            'edit' => Pages\EditTransaksikontraksewa::route('/{record}/edit'),
        ];
    }
}