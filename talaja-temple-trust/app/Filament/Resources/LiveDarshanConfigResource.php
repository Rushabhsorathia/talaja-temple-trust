<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LiveDarshanConfigResource\Pages;
use App\Filament\Resources\LiveDarshanConfigResource\RelationManagers;
use App\Models\LiveDarshanConfig;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LiveDarshanConfigResource extends Resource
{
    protected static ?string $model = LiveDarshanConfig::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';
    protected static ?string $navigationGroup = 'Configuration';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('stream_url')->required(),
            Forms\Components\Toggle::make('is_live')->default(false),
            Forms\Components\TextInput::make('poster_path'),
            Forms\Components\TimePicker::make('start_time'),
            Forms\Components\TimePicker::make('end_time'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
                Tables\Columns\TextColumn::make('stream_url')->limit(50),
                Tables\Columns\IconColumn::make('is_live')->boolean(),
                Tables\Columns\TextColumn::make('start_time'),
                Tables\Columns\TextColumn::make('end_time'),
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
            'index' => Pages\ListLiveDarshanConfigs::route('/'),
            'create' => Pages\CreateLiveDarshanConfig::route('/create'),
            'edit' => Pages\EditLiveDarshanConfig::route('/{record}/edit'),
        ];
    }
}
