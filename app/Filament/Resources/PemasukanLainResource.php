<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PemasukanLainResource\Pages;
use App\Models\PemasukanLain;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

//tambahan untuk tombol unduh pdf
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PemasukanLainResource extends Resource
{
    protected static ?string $model = PemasukanLain::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Pemasukan Lain';

    protected static ?string $pluralModelLabel = 'Pemasukan Lain';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\DatePicker::make('tanggal')
                    ->required(),

                Forms\Components\Select::make('penghuni_id')
                    ->label('Penghuni')
                    ->relationship('penghuni', 'nama_penghuni')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('kamar_id')
                    ->label('Kamar')
                    ->relationship('kamar', 'nama_kamar')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('jenis')
                    ->label('Jenis Tagihan')
                    ->options([
                        'Denda telat bayar' => 'Denda telat bayar',
                        'Pembayaran AC' => 'Pembayaran AC',
                        'Kunci hilang' => 'Denda kunci hilang',
                        'Kerusakan Fasilitas' => 'Denda kerusakan fasilitas',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('jumlah')
                    ->numeric()
                    ->default(1)
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        $set('total', $get('jumlah') * $get('harga'));
                    })
                    ->required(),

                Forms\Components\TextInput::make('harga')
                    ->numeric()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        $set('total', $get('jumlah') * $get('harga'));
                    })
                    ->required(),

                Forms\Components\TextInput::make('total')
                    ->numeric()
                    ->disabled()
                    ->dehydrated()
                    ->required(),

                Forms\Components\Textarea::make('keterangan')
                    ->rows(3),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('tanggal')
                    ->date(),

                Tables\Columns\TextColumn::make('penghuni.nama_penghuni')
                    ->label('Penghuni')
                    ->searchable(),

                Tables\Columns\TextColumn::make('kamar.nama_kamar')
                    ->label('Kamar'),

                Tables\Columns\TextColumn::make('jenis')
                    ->label('Jenis Tagihan'),

                Tables\Columns\TextColumn::make('total')
                    ->money('IDR', true),

            ])
            ->headerActions([
            Tables\Actions\Action::make('export_pdf')
            ->label('Export PDF')
            ->icon('heroicon-o-document')
            ->action(function () {
                $data = \App\Models\PemasukanLain::with(['penghuni', 'kamar'])->get();

                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.pemasukan-lain', [
                    'data' => $data
            ]);

            return response()->streamDownload(
                fn () => print($pdf->output()),
                'laporan-pemasukan-lain.pdf'
            );
        })
])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPemasukanLains::route('/'),
            'create' => Pages\CreatePemasukanLain::route('/create'),
            'edit' => Pages\EditPemasukanLain::route('/{record}/edit'),
        ];
    }
}