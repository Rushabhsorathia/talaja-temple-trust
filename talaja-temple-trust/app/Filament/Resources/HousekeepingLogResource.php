<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HousekeepingLogResource\Pages;
use App\Filament\Resources\HousekeepingLogResource\RelationManagers;
use App\Models\HousekeepingLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HousekeepingLogResource extends Resource
{
    protected static ?string $model = HousekeepingLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationGroup = 'Accommodation';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('room_id')->relationship('room','number')->required(),
            Forms\Components\Select::make('status')->options(['clean'=>'Clean','dirty'=>'Dirty','inspected'=>'Inspected'])->required(),
            Forms\Components\Textarea::make('note')->rows(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
                Tables\Columns\TextColumn::make('room.number')->label('Room'),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d-m-Y'),
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
            'index' => Pages\ListHousekeepingLogs::route('/'),
            'create' => Pages\CreateHousekeepingLog::route('/create'),
            'edit' => Pages\EditHousekeepingLog::route('/{record}/edit'),
        ];
    }
}
