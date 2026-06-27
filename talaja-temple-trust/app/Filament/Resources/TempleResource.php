<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TempleResource\Pages;
use App\Filament\Resources\TempleResource\RelationManagers;
use App\Models\Temple;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TempleResource extends Resource
{
    protected static ?string $model = Temple::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    protected static ?string $navigationGroup = 'Configuration';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
            Forms\Components\Toggle::make('is_primary'),
            Forms\Components\Toggle::make('is_active')->default(true),
            Forms\Components\Textarea::make('address')->rows(2),
            Forms\Components\TextInput::make('phone'),
            Forms\Components\TextInput::make('email')->email(),
            Forms\Components\Textarea::make('map_embed')->rows(2),
            Forms\Components\FileUpload::make('logo_path')->image()->directory('temple'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\IconColumn::make('is_primary')->boolean(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            
            ->filters([
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
            'index' => Pages\ListTemples::route('/'),
            'create' => Pages\CreateTemple::route('/create'),
            'edit' => Pages\EditTemple::route('/{record}/edit'),
        ];
    }
}
