<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

// tambahan
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

use Filament\Tables\Columns\TextColumn;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationLabel = 'Supplier';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // 🔢 Kode Supplier (Auto Generate)
                TextInput::make('kode_supplier')
                    ->default(fn () => Supplier::getKodeSupplier())
                    ->label('Kode Supplier')
                    ->required()
                    ->readonly(),

                // 🏷️ Nama Supplier
                TextInput::make('nama_supplier')
                    ->required()
                    ->placeholder('Masukkan nama supplier'),

                // 📦 Kategori Supplier
                Select::make('kategori')
                    ->options([
                        'air' => 'Air',
                        'listrik' => 'Listrik',
                        'internet' => 'Internet',
                        'kebersihan' => 'Kebersihan',
                        'maintenance' => 'Maintenance',
                        'lainnya' => 'Lainnya',
                    ])
                    ->required(),

                // 👤 Contact Person
                TextInput::make('contact_person')
                    ->placeholder('Nama yang bisa dihubungi'),

                // 📞 Nomor Telepon
                TextInput::make('nomor_telepon')
                    ->tel()
                    ->placeholder('Contoh: 08123456789'),

                // 📍 Alamat
                TextInput::make('alamat')
                    ->placeholder('Masukkan alamat supplier')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_supplier')
                    ->searchable(),

                TextColumn::make('nama_supplier')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kategori')
                    ->badge(),

                TextColumn::make('contact_person'),

                TextColumn::make('nomor_telepon'),

                TextColumn::make('alamat')
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}