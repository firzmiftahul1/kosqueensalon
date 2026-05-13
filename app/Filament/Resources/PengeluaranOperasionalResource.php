<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengeluaranOperasionalResource\Pages;
use App\Models\PengeluaranOperasional;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;

class PengeluaranOperasionalResource extends Resource
{
    protected static ?string $model = PengeluaranOperasional::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Pengeluaran Operasional';

    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode_pengeluaran')
                    ->label('Kode Pengeluaran')
                    ->default(fn () => PengeluaranOperasional::getKodePengeluaran())
                    ->disabled()
                    ->dehydrated()
                    ->required(),

                Select::make('kode_supplier')
                    ->label('Supplier')
                    ->relationship('supplier', 'nama_supplier')
                    ->searchable()
                    ->preload()
                    ->required(),

                DatePicker::make('tanggal')
                    ->label('Tanggal')
                    ->required(),

                TextInput::make('jenis_pengeluaran')
                    ->label('Jenis Pengeluaran')
                    ->placeholder('Contoh: Pembayaran listrik')
                    ->required(),

                TextInput::make('jumlah')
                    ->label('Jumlah')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->placeholder('Contoh: Tagihan listrik bulan Mei')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Action::make('unduh_pdf')
                    ->label('Unduh Laporan PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function () {
                        $pengeluarans = PengeluaranOperasional::with('supplier')->get();

                        $pdf = Pdf::loadView('pdf.laporan-pengeluaran-operasional', [
                            'pengeluarans' => $pengeluarans,
                        ]);

                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->stream();
                        }, 'laporan-pengeluaran-operasional.pdf');
                    }),
            ])
            ->columns([
                TextColumn::make('kode_pengeluaran')
                    ->label('Kode')
                    ->searchable(),

                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),

                TextColumn::make('supplier.nama_supplier')
                    ->label('Supplier')
                    ->searchable(),

                TextColumn::make('jenis_pengeluaran')
                    ->label('Jenis Pengeluaran')
                    ->searchable(),

                TextColumn::make('jumlah')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(30),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengeluaranOperasionals::route('/'),
            'create' => Pages\CreatePengeluaranOperasional::route('/create'),
            'edit' => Pages\EditPengeluaranOperasional::route('/{record}/edit'),
        ];
    }
}