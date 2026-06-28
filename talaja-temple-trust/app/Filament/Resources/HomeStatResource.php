<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomeStatResource\Pages;
use App\Filament\Resources\HomeStatResource\RelationManagers;
use App\Models\HomeStat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HomeStatResource extends Resource
{
    protected static ?string $model = HomeStat::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Homepage';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('value')->required(),
            Forms\Components\TextInput::make('label')->required(),
            Forms\Components\Select::make('icon')->options(['users'=>'Users','clock'=>'Clock','soup'=>'Soup','wifi'=>'Wifi','heart'=>'Heart','globe'=>'Globe']),
            Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
            Forms\Components\Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
                Tables\Columns\TextColumn::make('value')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('label'),
                Tables\Columns\TextColumn::make('icon')->badge(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])->filters([
                //
            ])
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
            'index' => Pages\ListHomeStats::route('/'),
            'create' => Pages\CreateHomeStat::route('/create'),
            'edit' => Pages\EditHomeStat::route('/{record}/edit'),
        ];
    }
}
