<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeedbackResource\Pages;
use App\Filament\Resources\FeedbackResource\RelationManagers;
use App\Models\Feedback;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FeedbackResource extends Resource
{
    protected static ?string $model = Feedback::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Communication';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')->options(['suggestion'=>'Suggestion','feedback'=>'Feedback','complaint'=>'Complaint'])->required(),
            Forms\Components\TextInput::make('name'),
            Forms\Components\TextInput::make('email')->email(),
            Forms\Components\TextInput::make('mobile'),
            Forms\Components\TextInput::make('category'),
            Forms\Components\TextInput::make('rating')->numeric()->minValue(1)->maxValue(5),
            Forms\Components\Textarea::make('message')->required()->rows(4),
            Forms\Components\Select::make('status')->options(['open'=>'Open','in_progress'=>'In Progress','closed'=>'Closed','spam'=>'Spam'])->default('open'),
            Forms\Components\Textarea::make('admin_reply')->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')->badge(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('category')->badge(),
                Tables\Columns\TextColumn::make('rating'),
                Tables\Columns\TextColumn::make('status')->badge()->colors(['warning'=>'open','primary'=>'in_progress','success'=>'closed','danger'=>'spam']),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d-m-Y')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
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
            'index' => Pages\ListFeedback::route('/'),
            'create' => Pages\CreateFeedback::route('/create'),
            'edit' => Pages\EditFeedback::route('/{record}/edit'),
        ];
    }
}
