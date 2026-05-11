<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengeluaranPenghuniResource\Pages;
use App\Models\PengeluaranPenghuni;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Barryvdh\DomPDF\Facade\Pdf;

class PengeluaranPenghuniResource extends Resource
{
    protected static ?string $model = PengeluaranPenghuni::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Pengeluaran Penghuni';

    protected static ?string $modelLabel = 'Pengeluaran Penghuni';

    protected static ?string $pluralModelLabel = 'Pengeluaran Penghuni';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('penghuni_id')
                    ->relationship('penghuni', 'nama')
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('nama_pengeluaran')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('keterangan')
                    ->rows(3),

                Forms\Components\TextInput::make('nominal')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                Forms\Components\DatePicker::make('tanggal_pengeluaran')
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
                    ->searchable(),

                Tables\Columns\TextColumn::make('nominal')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_pengeluaran')
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
                            'pengeluaran-penghuni.pdf'
                        );
                    }),
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