<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MeetingHallResource\Pages;
use App\Filament\Resources\MeetingHallResource\RelationManagers;
use App\Models\MeetingHall;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MeetingHallResource extends Resource
{
    protected static ?string $model = MeetingHall::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationGroup = 'Accommodation';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('temple_id')->relationship('temple','name'),
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('name_gu')->label('Name (Gujarati)'),
            Forms\Components\TextInput::make('capacity')->numeric()->required(),
            Forms\Components\TextInput::make('tariff')->numeric()->required(),
            Forms\Components\Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('capacity'),
                Tables\Columns\TextColumn::make('tariff')->money('INR')->sortable(),
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
            'index' => Pages\ListMeetingHalls::route('/'),
            'create' => Pages\CreateMeetingHall::route('/create'),
            'edit' => Pages\EditMeetingHall::route('/{record}/edit'),
        ];
    }
}
