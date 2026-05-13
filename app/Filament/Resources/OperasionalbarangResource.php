<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OperasionalbarangResource\Pages;
use App\Models\Operasionalbarang;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

// Tambahan untuk tombol unduh pdf
use Filament\Tables\Actions\Action; 
use Barryvdh\DomPDF\Facade\Pdf; 
use Illuminate\Support\Facades\Storage;

class OperasionalbarangResource extends Resource
{
    protected static ?string $model = Operasionalbarang::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    
    protected static ?string $navigationLabel = 'Operasional Barang';

    protected static ?string $modelLabel = 'Operasional Barang';

    protected static ?string $pluralModelLabel = 'Operasional Barang';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Identifikasi')
                        ->description('Kode dan Waktu Operasional')
                        ->icon('heroicon-m-identification')
                        ->schema([
                            TextInput::make('kode_op_barang')
                                ->label('Kode Operasional')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->placeholder('Contoh: OPB001'),

                            DatePicker::make('tanggal')
                                ->label('Tanggal Kegiatan')
                                ->default(now())
                                ->required(),
                        ])->columns(2),

                    Wizard\Step::make('Lokasi & Barang')
                        ->description('Pilih Kamar dan Barang')
                        ->icon('heroicon-m-home-modern')
                        ->schema([
                            Select::make('kamar_id')
                                ->relationship('kamar', 'nama_kamar')
                                ->label('Pilih Kamar')
                                ->searchable()
                                ->preload()
                                ->required(),

                            Select::make('barang_id')
                                ->relationship('barang', 'nama_barang')
                                ->label('Pilih Barang')
                                ->searchable()
                                ->preload()
                                ->required(),
                        ])->columns(2),

                    Wizard\Step::make('Detail Kegiatan')
                        ->description('Rincian Biaya dan Keterangan')
                        ->icon('heroicon-m-clipboard-document-list')
                        ->schema([
                            TextInput::make('kegiatan')
                                ->label('Nama Kegiatan')
                                ->placeholder('Contoh: Service AC')
                                ->required(),

                            TextInput::make('biaya')
                                ->numeric()
                                ->prefix('Rp')
                                ->required(),

                            Textarea::make('keterangan')
                                ->label('Keterangan Tambahan')
                                ->placeholder('Detail perbaikan atau penggantian...')
                                ->columnSpanFull(),
                        ])->columns(2),
                ])
                ->columnSpanFull()
                ->skippable()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_op_barang')
                    ->label('Kode')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                
                TextColumn::make('kamar.nama_kamar')
                    ->label('Kamar'),

                TextColumn::make('barang.nama_barang')
                    ->label('Barang'),

                TextColumn::make('kegiatan')
                    ->label('Kegiatan'),

                TextColumn::make('biaya')
                    ->money('IDR')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            // ✅ Bagian Action (Tombol di baris data)
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            // ✅ Bagian Header Action (Tombol di atas tabel)
            ->headerActions([
                Action::make('downloadPdf')
                    ->label('Unduh PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function () {
                        // Ambil data Operasionalbarang, bukan Penjualan
                        $data = Operasionalbarang::with(['kamar', 'barang'])->get();

                        // Pastikan kamu sudah membuat file view: resources/views/pdf/operasional.blade.php
                        $pdf = Pdf::loadView('pdf.operasional-barang', ['data' => $data]);

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'laporan-operasional-barang.pdf'
                        );
                    }),
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
            'index' => Pages\ListOperasionalbarangs::route('/'),
            'create' => Pages\CreateOperasionalbarang::route('/create'),
            'edit' => Pages\EditOperasionalbarang::route('/{record}/edit'),
        ];
    }
}