<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KontrakSewaResource\Pages;
use App\Models\KontrakSewa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;

class KontrakSewaResource extends Resource
{
    protected static ?string $model = KontrakSewa::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    protected static ?string $navigationLabel = 'Data Kontrak Sewa';

    protected static ?string $pluralModelLabel = 'Kontrak Sewa';

    // tambahan buat grup masterdata
    protected static ?string $navigationGroup = 'Master data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Kontrak')
                    ->schema([
                        // Relasi ke Penghuni menggunakan kolom id_penghuni
                        TextInput::make('kode_kontrak')
                            ->label('Kode Kontrak')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->placeholder('Contoh: KTR-001'),

                        Select::make('id_penghuni')
                            ->label('Nama Penghuni')
                            ->relationship('penghuni', 'nama')
                            ->searchable()
                            ->preload()
                            ->required(),

                        // Relasi ke Kamar menggunakan kolom id_kamar
                        Select::make('id_kamars')
                            ->label('Kode Kamar')
                            ->relationship('kamars', 'kode_kamar')
                            ->searchable()
                            ->preload()
                            ->required(),

                        DatePicker::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->required()
                            ->default(now()),

                        DatePicker::make('tanggal_selesai')
                            ->label('Tanggal Selesai')
                            ->helperText('Bisa dikosongkan jika belum ditentukan.'),

                        Select::make('status_kontrak')
                            ->label('Status Kontrak')
                            ->options([
                                'Aktif' => 'Aktif',
                                'Selesai' => 'Selesai',
                            ])
                            ->default('Aktif')
                            ->required(),

                        Textarea::make('keterangan')
                            ->label('Catatan Tambahan')
                            ->placeholder('Misal: Sewa kos 1 tahun')
                            ->columnSpanFull(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_kontrak')
                    ->label('Kode Kontrak')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('penghuni.nama')
                    ->label('Penghuni')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('kamars.kode_kamar')
                    ->label('Kode Kamar')
                    ->badge()
                    ->color('info'),

                TextColumn::make('tanggal_mulai')
                    ->label('Mulai')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('tanggal_selesai')
                    ->label('Selesai')
                    ->date('d M Y')
                    ->placeholder('-'),

                TextColumn::make('status_kontrak')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Aktif' => 'success',
                        'Selesai' => 'gray',
                        default => 'gray',
                    }),
                
                TextColumn::make('keterangan')
                    ->label('Keterangan')

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_kontrak')
                    ->options([
                        'Aktif' => 'Aktif',
                        'Selesai' => 'Selesai',
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKontrakSewas::route('/'),
            'create' => Pages\CreateKontrakSewa::route('/create'),
            'edit' => Pages\EditKontrakSewa::route('/{record}/edit'),
        ];
    }
}