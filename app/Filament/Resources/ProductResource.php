<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextArea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Data Produk';
    protected static ?string $label = 'Produk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->maxLength(255)
                    ->required()
                    ->label('Nama Produk')
                    ->placeholder('Contoh: Produk A')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn($state, callable $set) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->readOnly()
                    ->maxLength(255)
                    ->required()
                    ->label('Slug')
                    ->unique()
                    ->placeholder('Contoh: produk-a'),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->label('Harga')
                    ->placeholder('Contoh: 100000'),
                TextInput::make('size')
                    ->required()
                    ->maxLength(255)
                    ->label('Ukuran')
                    ->placeholder('Contoh: 1x1x1'),
                TextInput::make('weight')
                    ->required()
                    ->numeric()
                    ->label('Berat')
                    ->placeholder('Contoh: 100'),
                TextInput::make('stock')
                    ->required()
                    ->numeric()
                    ->label('Stok')
                    ->placeholder('Contoh: 10'),
                FileUpload::make('photo')
                    ->required()
                    ->label('Foto')
                    ->columnSpan(2)
                    ->directory('products-photos')
                    ->image()
                    ->imageEditor()
                    ->imageCropAspectRatio('1:1')
                    ->maxSize(1024),
                TextArea::make('description')
                    ->required()
                    ->label('Deskripsi')
                    ->placeholder('Contoh: Produk A')
                    ->rows(10)
                    ->autosize()
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')
                    ->label('Foto'),
                TextColumn::make('name')
                    ->label('Nama Produk'),
                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR'),
                TextColumn::make('size')
                    ->label('Ukuran'),
                TextColumn::make('weight')
                    ->label('Berat')
                    ->suffix('kg'),
                TextColumn::make('stock')
                    ->label('Stok')
                    ->suffix('pcs'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
