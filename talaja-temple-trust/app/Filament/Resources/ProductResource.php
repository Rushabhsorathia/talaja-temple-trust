<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Shop';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('name_gu')->label('Name (Gujarati)'),
            Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord:true),
            Forms\Components\Textarea::make('description')->rows(2),
            Forms\Components\TextInput::make('price')->numeric()->required(),
            Forms\Components\TextInput::make('compare_at_price')->numeric(),
            Forms\Components\TextInput::make('stock')->numeric()->default(0),
            Forms\Components\TextInput::make('category'),
            Forms\Components\FileUpload::make('image_path')->image()->directory('products'),
            Forms\Components\Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('category')->badge(),
                Tables\Columns\TextColumn::make('price')->money('INR')->sortable(),
                Tables\Columns\TextColumn::make('stock')->sortable(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->filters([Tables\Filters\Filter::make('placeholder')])
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
