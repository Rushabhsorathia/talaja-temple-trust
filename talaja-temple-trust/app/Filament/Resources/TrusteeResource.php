<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrusteeResource\Pages;
use App\Filament\Resources\TrusteeResource\RelationManagers;
use App\Models\Trustee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TrusteeResource extends Resource
{
    protected static ?string $model = Trustee::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Content';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('designation')->required(),
            Forms\Components\TextInput::make('designation_gu')->label('Designation (Gujarati)'),
            Forms\Components\Textarea::make('bio')->rows(3),
            Forms\Components\FileUpload::make('photo_path')->image()->directory('trustees'),
            Forms\Components\Toggle::make('is_active')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo_path')->circular(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('designation'),
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
            'index' => Pages\ListTrustees::route('/'),
            'create' => Pages\CreateTrustee::route('/create'),
            'edit' => Pages\EditTrustee::route('/{record}/edit'),
        ];
    }
}
