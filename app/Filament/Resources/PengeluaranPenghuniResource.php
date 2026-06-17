<?php

namespace App\Filament\Resources;

use App\Filament\Exports\PengeluaranPenghuniExporter;
use App\Filament\Resources\PengeluaranPenghuniResource\Pages;
use App\Mail\Tesmail;
use App\Models\PengeluaranPenghuni;
use App\Services\FonnteService;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;

class PengeluaranPenghuniResource extends Resource
{
    protected static ?string $model = PengeluaranPenghuni::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Pembayaran Penghuni';

    protected static ?string $modelLabel = 'Pembayaran Penghuni';

    protected static ?string $pluralModelLabel = 'Pembayaran Penghuni';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('penghuni_id')
                    ->relationship('penghuni', 'nama')
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('nama_pengeluaran')
                    ->label('Nama Pembayaran')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('keterangan')
                    ->rows(3),

                Forms\Components\TextInput::make('nominal')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                Forms\Components\DatePicker::make('tanggal_pengeluaran')
                    ->label('Tanggal Pembayaran')
                    ->required(),

                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'dibayar' => 'Dibayar',
                        'ditolak' => 'Ditolak',
                    ])
                    ->default('pending')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('penghuni.nama')
                    ->label('Nama Penghuni')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama_pengeluaran')
                    ->label('Nama Pembayaran')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nominal')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_pengeluaran')
                    ->label('Tanggal Pembayaran')
                    ->date()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'dibayar',
                        'danger' => 'ditolak',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])

            ->headerActions([
                Tables\Actions\Action::make('downloadPdf')
                    ->label('Unduh PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function () {
                        $data = PengeluaranPenghuni::with('penghuni')->get();

                        $pdf = Pdf::loadView(
                            'pdf.pengeluaran-penghuni',
                            ['data' => $data]
                        );

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'pembayaran-penghuni.pdf'
                        );
                    }),
                ExportAction::make()->exporter(PengeluaranPenghuniExporter::class),
                Tables\Actions\Action::make('kirimEmail')
                    ->label('Kirim Email')
                    ->icon('heroicon-o-envelope')
                    ->color('info')
                    ->action(function () {
                        Mail::to('test@example.com')->send(new Tesmail());
                    })
                    ->successNotificationTitle('Email berhasil dikirim!'),
            ])

            ->filters([
                //
            ])

            ->actions([
                Tables\Actions\EditAction::make()
                    ->after(function ($record) {
                        if ($record->status === 'dibayar') {
                            $nomor = $record->penghuni->no_hp;
                            $nama = $record->penghuni->nama;
                            $nominal = number_format($record->nominal, 0, ',', '.');
                            $tanggal = $record->tanggal_pengeluaran;
                            $jenis = $record->nama_pengeluaran;

                            $pesan = "Halo *{$nama}*! 👋\n\n";
                            $pesan .= "Pembayaran kamu sudah *dikonfirmasi* ✅\n\n";
                            $pesan .= "Detail Pembayaran:\n";
                            $pesan .= "- Jenis: *{$jenis}*\n";
                            $pesan .= "- Nominal: *Rp {$nominal}*\n";
                            $pesan .= "- Tanggal: *{$tanggal}*\n\n";
                            $pesan .= "Terima kasih! 🙏\n";
                            $pesan .= "_Kost Queens Salon_";

                            FonnteService::send($nomor, $pesan);
                        }
                    }),
                Tables\Actions\DeleteAction::make(),
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()->exporter(PengeluaranPenghuniExporter::class),
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
            'index' => Pages\ListPengeluaranPenghunis::route('/'),
            'create' => Pages\CreatePengeluaranPenghuni::route('/create'),
            'edit' => Pages\EditPengeluaranPenghuni::route('/{record}/edit'),
        ];
    }
}