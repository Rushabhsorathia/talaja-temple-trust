<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomeServiceResource\Pages;
use App\Filament\Resources\HomeServiceResource\RelationManagers;
use App\Models\HomeService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HomeServiceResource extends Resource
{
    protected static ?string $model = HomeService::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationGroup = 'Homepage';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('icon')->options(['video'=>'Video','heart'=>'Heart','bed'=>'Bed','bag'=>'Bag','user'=>'User','globe'=>'Globe'])->required(),
            Forms\Components\TextInput::make('title')->required(),
            Forms\Components\Textarea::make('description')->rows(2),
            Forms\Components\TextInput::make('href'),
            Forms\Components\TextInput::make('badge'),
            Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
            Forms\Components\Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('icon')->badge(),
                Tables\Columns\TextColumn::make('badge')->badge(),
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
            'index' => Pages\ListHomeServices::route('/'),
            'create' => Pages\CreateHomeService::route('/create'),
            'edit' => Pages\EditHomeService::route('/{record}/edit'),
        ];
    }
}
