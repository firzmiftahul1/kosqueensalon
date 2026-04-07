<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransaksiPembayaranResource\Pages;
use App\Models\TransaksiPembayaran;
use App\Models\MetodePembayaran;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;

use Filament\Tables\Columns\TextColumn;

class TransaksiPembayaranResource extends Resource
{
    protected static ?string $model = TransaksiPembayaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Transaksi Pembayaran';

    protected static ?string $navigationGroup = 'Keuangan';

    protected static ?string $modelLabel = 'Transaksi Pembayaran';

    protected static ?string $pluralModelLabel = 'Transaksi Pembayaran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // 📅 Tanggal Transaksi
                DatePicker::make('tanggal')
                    ->required()
                    ->label('Tanggal Transaksi')
                    ->default(now()),

                // 💰 Jenis Transaksi
                Select::make('jenis_transaksi')
                    ->options([
                        'pemasukan' => 'Pemasukan',
                        'pengeluaran' => 'Pengeluaran',
                    ])
                    ->required()
                    ->label('Jenis Transaksi'),

                // 💳 Metode Pembayaran
                Select::make('id_metode')
                    ->label('Metode Pembayaran')
                    ->options(MetodePembayaran::where('status', 'aktif')->pluck('nama_metode', 'id_metode'))
                    ->required()
                    ->searchable(),

                // 🏢 Supplier (opsional, untuk pengeluaran)
                Select::make('id_supplier')
                    ->label('Supplier')
                    ->options(Supplier::pluck('nama_supplier', 'id'))
                    ->searchable()
                    ->nullable()
                    ->placeholder('Pilih supplier (opsional)'),

                // 💵 Total Bayar
                TextInput::make('total_bayar')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Total Bayar')
                    ->placeholder('0'),

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
                TextColumn::make('id_transaksi')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('jenis_transaksi')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pemasukan' => 'success',
                        'pengeluaran' => 'danger',
                    }),

                TextColumn::make('metodePembayaran.nama_metode')
                    ->label('Metode Bayar')
                    ->placeholder('-'),

                TextColumn::make('supplier.nama_supplier')
                    ->label('Supplier')
                    ->placeholder('-'),

                TextColumn::make('total_bayar')
                    ->label('Total Bayar')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(30)
                    ->placeholder('-'),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('tanggal', 'desc')
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
            'index' => Pages\ListTransaksiPembayarans::route('/'),
            'create' => Pages\CreateTransaksiPembayaran::route('/create'),
            'edit' => Pages\EditTransaksiPembayaran::route('/{record}/edit'),
        ];
    }
}