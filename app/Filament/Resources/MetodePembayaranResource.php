<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MetodePembayaranResource\Pages;
use App\Models\MetodePembayaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class MetodePembayaranResource extends Resource
{
    protected static ?string $model = MetodePembayaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Metode Pembayaran';

    protected static ?string $navigationGroup = 'Keuangan';

    protected static ?string $modelLabel = 'Metode Pembayaran';

    protected static ?string $pluralModelLabel = 'Metode Pembayaran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // 🏷️ Nama Metode
                TextInput::make('nama_metode')
                    ->required()
                    ->label('Nama Metode')
                    ->placeholder('Contoh: Transfer Bank, Cash, QRIS'),

                // 📦 Jenis Metode
                Select::make('jenis_metode')
                    ->options([
                        'cash' => 'Cash',
                        'transfer' => 'Transfer Bank',
                        'e-wallet' => 'E-Wallet',
                        'qris' => 'QRIS',
                        'lainnya' => 'Lainnya',
                    ])
                    ->required()
                    ->label('Jenis Metode'),

                // 🏦 Nama Bank
                TextInput::make('nama_bank')
                    ->label('Nama Bank')
                    ->placeholder('Contoh: BCA, BNI, Mandiri')
                    ->nullable(),

                // 💳 No Rekening
                TextInput::make('no_rekening')
                    ->label('No. Rekening')
                    ->placeholder('Masukkan nomor rekening')
                    ->nullable(),

                // 👤 Atas Nama
                TextInput::make('atas_nama')
                    ->label('Atas Nama')
                    ->placeholder('Nama pemilik rekening')
                    ->nullable(),

                // ✅ Status
                Select::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'nonaktif' => 'Nonaktif',
                    ])
                    ->default('aktif')
                    ->required()
                    ->label('Status'),

                // 📝 Keterangan
                Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->placeholder('Keterangan tambahan (opsional)')
                    ->columnSpanFull()
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_metode')
                    ->label('Nama Metode')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('jenis_metode')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'cash' => 'success',
                        'transfer' => 'info',
                        'e-wallet' => 'warning',
                        'qris' => 'primary',
                        default => 'gray',
                    }),

                TextColumn::make('nama_bank')
                    ->label('Bank')
                    ->placeholder('-'),

                TextColumn::make('no_rekening')
                    ->label('No. Rekening')
                    ->placeholder('-'),

                TextColumn::make('atas_nama')
                    ->label('Atas Nama')
                    ->placeholder('-'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'nonaktif' => 'danger',
                    }),
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMetodePembayarans::route('/'),
            'create' => Pages\CreateMetodePembayaran::route('/create'),
            'edit' => Pages\EditMetodePembayaran::route('/{record}/edit'),
        ];
    }
}