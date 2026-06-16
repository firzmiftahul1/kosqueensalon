<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JournalResource\Pages;
use App\Models\Journal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;

class JournalResource extends Resource
{
    protected static ?string $modelLabel = 'Journal';
protected static ?string $pluralModelLabel = 'Journal';
protected static ?string $navigationLabel = 'Journal';
protected static ?string $navigationIcon = 'heroicon-o-book-open'; // Logo buku jurnal

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            // Menggunakan Wizard agar form terbagi jadi beberapa langkah
            Forms\Components\Wizard::make([
                
                // STEP 1: Informasi Dasar (Kapan & No Referensi)
                Forms\Components\Wizard\Step::make('Informasi Jurnal')
                    ->description('Tentukan tanggal dan nomor bukti')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\DatePicker::make('transaction_date')
                                ->label('Tanggal Transaksi')
                                ->default(now())
                                ->required(),
                            Forms\Components\TextInput::make('reference_no')
                                ->label('No. Referensi / Bukti')
                                ->placeholder('Contoh: BM-001 atau RJ-001')
                                ->required(),
                        ]),
                    ]),

                // STEP 2: Akun & Nominal
Forms\Components\Wizard\Step::make('Detail Akun')
    ->description('Pilih akun dan tentukan posisi Debit/Kredit')
    ->icon('heroicon-o-building-library')
    ->schema([
        Forms\Components\Select::make('coa_id')
            ->label('Pilih Akun (COA)')
            ->options(\App\Models\Coa::all()->mapWithKeys(function ($coa) {
                return [$coa->id => $coa->kode_coa . ' - ' . $coa->nama_coa];
            }))
            ->searchable()
            ->required(),
        
        Forms\Components\Grid::make(2)->schema([
            Forms\Components\TextInput::make('debit')
                ->label('Nominal Debit')
                ->numeric()
                ->prefix('Rp')
                // Tambahkan ini agar tidak null
                ->default(0)
                ->required(), 
            
            Forms\Components\TextInput::make('credit')
                ->label('Nominal Kredit')
                ->numeric()
                ->prefix('Rp')
                // Tambahkan ini agar tidak null
                ->default(0)
                ->required(),
        ]),
    
                        
                        // Informasi tambahan biar user tidak lupa standar akuntansi
                        Forms\Components\Placeholder::make('note')
                            ->label('Catatan')
                            ->content('Pastikan posisi Debit/Kredit sudah sesuai dengan jenis transaksinya.'),
                    ]),

                // STEP 3: Keterangan (Narasi Transaksi)
                Forms\Components\Wizard\Step::make('Konfirmasi')
                    ->description('Berikan keterangan lengkap transaksi')
                    ->icon('heroicon-o-check-circle')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Keterangan / Memo')
                            ->placeholder('Contoh: Setoran modal awal pemilik atau Retur pembelian barang X')
                            ->rows(3)
                            ->required(),
                    ]),
            ])->columnSpanFull()
        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('transaction_date')
                ->label('Tanggal')
                ->date()
                ->sortable(),
            Tables\Columns\TextColumn::make('reference_no')
                ->label('No. Ref'),
            Tables\Columns\TextColumn::make('description')
                ->label('Keterangan')
                ->searchable(),
            Tables\Columns\TextColumn::make('debit')
                ->label('Debit')
                ->money('IDR')
                ->color('success'),
            Tables\Columns\TextColumn::make('credit')
                ->label('Kredit')
                ->money('IDR')
                ->color('danger'),
        ])
        ->filters([
                //
            ])
            ->headerActions([
                // Tombol ini untuk cetak SEMUA data (tanpa centang)
                Action::make('downloadAllPdf')
                    ->label('Unduh Semua PDF')
                    ->icon('heroicon-o-printer')
                    ->color('gray')
                    ->action(function () {
                        $jurnal = Journal::with('coa')->orderBy('transaction_date', 'asc')->get();
                        $pdf = Pdf::loadView('pdf.jurnal', ['jurnal' => $jurnal]);
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'laporan-jurnal-semua.pdf'
                        );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    // INI YANG KAMU MAU: Tombol cetak yang dicentang saja
                    BulkAction::make('downloadSelectedPdf')
                        ->label('Unduh PDF (Terpilih)')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('success')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            // $records otomatis berisi data yang kamu centang saja
                            $pdf = Pdf::loadView('pdf.jurnal', ['jurnal' => $records]);
                            
                            return response()->streamDownload(
                                fn () => print($pdf->output()),
                                'laporan-jurnal-terpilih.pdf'
                            );
                        }),
                ]),
            ]);
    }

    // === TAMBAHKAN KODE INI UNTUK DAFTARKAN WIDGET KE RESOURCE ===
    public static function getWidgets(): array
    {
        return [
            JournalResource\Widgets\JurnalUmumAnalyticChart::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJournals::route('/'),
            'create' => Pages\CreateJournal::route('/create'),
            'edit' => Pages\EditJournal::route('/{record}/edit'),
        ];
    }
}