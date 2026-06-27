<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DonationCategoryResource\Pages;
use App\Filament\Resources\DonationCategoryResource\RelationManagers;
use App\Models\DonationCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DonationCategoryResource extends Resource
{
    protected static ?string $model = DonationCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';
    protected static ?string $navigationGroup = 'Donations';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('name_gu')->label('Name (Gujarati)'),
            Forms\Components\Textarea::make('description')->rows(2),
            Forms\Components\Toggle::make('is_80g_eligible')->default(true),
            Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
            Forms\Components\Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('description')->limit(40),
                Tables\Columns\IconColumn::make('is_80g_eligible')->boolean(),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
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
            'index' => Pages\ListDonationCategories::route('/'),
            'create' => Pages\CreateDonationCategory::route('/create'),
            'edit' => Pages\EditDonationCategory::route('/{record}/edit'),
        ];
    }
}
