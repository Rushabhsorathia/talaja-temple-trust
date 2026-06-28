<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VideoResource\Pages;
use App\Models\Video;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;

class VideoResource extends Resource
{
    protected static ?string $model = Video::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';
    protected static ?string $navigationGroup = 'Site Content';
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?int $navigationSort = 51;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Video')->schema([
                Forms\Components\TextInput::make('title')->required(),
                Forms\Components\TextInput::make('title_gu')->label('Title (Gujarati)'),
                Forms\Components\Select::make('source')->options(['youtube' => 'YouTube', 'vimeo' => 'Vimeo'])
                    ->default('youtube')->native(false)->live(),
                Forms\Components\TextInput::make('source_id')->label('Video ID')->required()
                    ->helperText(fn (Forms\Get $get) => $get('source') === 'youtube'
                        ? 'The part after v= in the YouTube URL (e.g. dQw4w9WgXcQ).'
                        : 'The numeric Vimeo video ID.'),
            ])->columns(2),
            Forms\Components\Section::make('Organisation')->schema([
                Forms\Components\TextInput::make('category'),
                Forms\Components\FileUpload::make('thumbnail_path')->image()
                    ->imagePreviewHeight('120')->directory('video-thumbnails')
                    ->helperText('Auto-fetched from source if left blank.'),
                Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                Forms\Components\Toggle::make('is_active')->default(true)->inline(false),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->deferFilters()
            ->filters([
                Tables\Filters\SelectFilter::make('source')->options(['youtube' => 'YouTube', 'vimeo' => 'Vimeo'])->multiple(),
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(2)
            ->headerActions([
                Tables\Actions\Action::make('viewPublic')
                    ->label('View Video Gallery')->icon('heroicon-o-arrow-top-right-on-square')
                    ->url('/video-gallery', shouldOpenInNewTab: true)->color('gray'),
            ])
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail_path')->square()->height(60)
                    ->defaultImageUrl(fn (Video $r) => "https://img.youtube.com/vi/{$r->source_id}/default.jpg")
                    ->label(''),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable()->weight('bold')->wrap(),
                Tables\Columns\TextColumn::make('source')->badge()->color('danger'),
                Tables\Columns\TextColumn::make('source_id')->label('ID')->badge()->color('gray')->copyable(),
                Tables\Columns\TextColumn::make('category')->badge()->placeholder('—'),
                Tables\Columns\ToggleColumn::make('is_active'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('open')->label('Watch')->icon('heroicon-o-play')
                        ->url(fn (Video $r) => $r->source === 'youtube' ? "https://youtu.be/{$r->source_id}" : "https://vimeo.com/{$r->source_id}", shouldOpenInNewTab: true),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->emptyStateHeading('No videos yet')
            ->emptyStateDescription('Add YouTube or Vimeo videos to populate the public video gallery.')
            ->emptyStateIcon('heroicon-o-video-camera')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Add first video')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'source_id', 'category'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListVideos::route('/'),
            'create' => Pages\CreateVideo::route('/create'),
            'edit'   => Pages\EditVideo::route('/{record}/edit'),
        ];
    }
}