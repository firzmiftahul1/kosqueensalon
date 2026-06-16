<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseResource\Pages;
use App\Filament\Resources\PurchaseResource\RelationManagers;
use App\Models\Purchase;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PurchaseResource extends Resource
{
    protected static ?string $modelLabel = 'Purchase';
protected static ?string $pluralModelLabel = 'Purchase';
protected static ?string $navigationLabel = 'Purchase';
protected static ?string $navigationIcon = 'heroicon-o-shopping-cart'; // Logo belanja

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Card::make()
                ->schema([
                    Forms\Components\DatePicker::make('purchase_date')
                        ->label('Tanggal Pembelian')
                        ->default(now())
                        ->required(),
                    
                    Forms\Components\TextInput::make('item_name')
                        ->label('Nama Barang')
                        ->placeholder('Contoh: Kasur, Lemari')
                        ->required(),

                    Forms\Components\TextInput::make('amount')
                        ->label('Total Harga')
                        ->numeric()
                        ->prefix('Rp')
                        ->required(),

                    Forms\Components\Textarea::make('description')
                        ->label('Keterangan')
                        ->placeholder('Bisa dikosongkan jika tidak ada')
                        ->rows(3),

                    // Tambahkan dropdown COA jika kamu ingin menghubungkan ke tabel Akun
                    Forms\Components\Select::make('coa_id')
                        ->label('Pilih Akun (COA)')
                        ->relationship('coa', 'nama_coa') // Pastikan ada relasi di model
                        ->searchable()
                        ->preload(),
                ])
        ]);
}

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('purchase_date')
                ->label('Tanggal Beli')
                ->date(),
            Tables\Columns\TextColumn::make('item_name')
                ->label('Nama Barang')
                ->searchable(),
            Tables\Columns\TextColumn::make('amount')
                ->label('Total Harga')
                ->money('IDR'),
            Tables\Columns\TextColumn::make('description')
                ->label('Keterangan'),
        ])
        ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPurchases::route('/'),
            'create' => Pages\CreatePurchase::route('/create'),
            'edit' => Pages\EditPurchase::route('/{record}/edit'),
        ];
    }
}
