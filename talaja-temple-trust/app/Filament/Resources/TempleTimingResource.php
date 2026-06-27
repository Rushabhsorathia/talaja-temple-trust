<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TempleTimingResource\Pages;
use App\Filament\Resources\TempleTimingResource\RelationManagers;
use App\Models\TempleTiming;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TempleTimingResource extends Resource
{
    protected static ?string $model = TempleTiming::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Configuration';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('temple_id')->relationship('temple','name')->required(),
            Forms\Components\Select::make('type')->options(['darshan'=>'Darshan','aarti'=>'Aarti','pooja'=>'Pooja'])->required(),
            Forms\Components\TextInput::make('title')->required(),
            Forms\Components\TextInput::make('title_gu')->label('Title (Gujarati)'),
            Forms\Components\TimePicker::make('start_time'),
            Forms\Components\TimePicker::make('end_time'),
            Forms\Components\TextInput::make('day_of_week'),
            Forms\Components\TextInput::make('fee')->numeric()->default(0),
            Forms\Components\Toggle::make('is_active')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')->badge(),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('start_time'),
                Tables\Columns\TextColumn::make('fee')->money('INR'),
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
            'index' => Pages\ListTempleTimings::route('/'),
            'create' => Pages\CreateTempleTiming::route('/create'),
            'edit' => Pages\EditTempleTiming::route('/{record}/edit'),
        ];
    }
}
