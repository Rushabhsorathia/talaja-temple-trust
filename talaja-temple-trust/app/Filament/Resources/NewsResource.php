<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsResource\Pages;
use App\Filament\Resources\NewsResource\RelationManagers;
use App\Models\News;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationGroup = 'Content';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Content')->schema([
            Forms\Components\TextInput::make('title')->required()->live(onBlur: true),
            Forms\Components\TextInput::make('title_gu')->label('Title (Gujarati)'),
            Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
            Forms\Components\Select::make('category')->options(['event'=>'Event','announcement'=>'Announcement','festival'=>'Festival','general'=>'General']),
            Forms\Components\TagsInput::make('tags'),
            Forms\Components\RichEditor::make('excerpt')->columnSpanFull(),
            Forms\Components\RichEditor::make('content')->required()->columnSpanFull(),
            Forms\Components\RichEditor::make('content_gu')->label('Content (Gujarati)')->columnSpanFull(),
            Forms\Components\FileUpload::make('image_path')->image()->directory('news'),
        ])->columns(2),
        Forms\Components\Section::make('Publishing & SEO')->schema([
            Forms\Components\Toggle::make('is_published')->default(true),
            Forms\Components\DateTimePicker::make('published_at')->default(now()),
            Forms\Components\TextInput::make('meta_title'),
            Forms\Components\Textarea::make('meta_description')->rows(2),
        ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')->square(),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable()->limit(40),
                Tables\Columns\TextColumn::make('category')->badge(),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
                Tables\Columns\TextColumn::make('published_at')->dateTime('d-m-Y')->sortable(),
            ])
            ->defaultSort('published_at', 'desc')
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
            'index' => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'edit' => Pages\EditNews::route('/{record}/edit'),
        ];
    }
}
