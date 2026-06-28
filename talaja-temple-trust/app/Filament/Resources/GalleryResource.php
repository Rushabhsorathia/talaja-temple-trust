<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryResource\Pages;
use App\Models\Gallery;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;

class GalleryResource extends Resource
{
    protected static ?string $model = Gallery::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'Site Content';
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?int $navigationSort = 50;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Photo')->schema([
                Forms\Components\FileUpload::make('image_path')->image()->required()
                    ->imagePreviewHeight('220')->directory('gallery')
                    ->panelAspectRatio('4:3')->panelLayout('integrated')
                    ->helperText('Recommended ≥ 1200px wide. Used in masonry gallery and homepage preview.'),
                Forms\Components\TextInput::make('title')->required(),
                Forms\Components\TextInput::make('title_gu')->label('Title (Gujarati)'),
            ])->columns(2),
            Forms\Components\Section::make('Organisation')->schema([
                Forms\Components\TextInput::make('alt_text')->helperText('Accessibility text — describes the image.'),
                Forms\Components\Select::make('category')->options([
                    'Temple' => 'Temple', 'Festivals' => 'Festivals',
                    'Events' => 'Events', 'Community' => 'Community',
                ])->default('Temple')->native(false)->createOptionUsing(fn ($v) => $v),
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
                Tables\Filters\SelectFilter::make('category')
                    ->options(fn () => Gallery::query()->whereNotNull('category')->distinct()->pluck('category', 'category')->sort())
                    ->multiple(),
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(2)
            ->headerActions([
                Tables\Actions\Action::make('viewPublic')
                    ->label('View Photo Gallery')->icon('heroicon-o-arrow-top-right-on-square')
                    ->url('/photo-gallery', shouldOpenInNewTab: true)->color('gray'),
            ])
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')->square()->height(60)->label(''),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable()->weight('bold')->wrap()
                    ->description(fn (Gallery $r) => $r->alt_text),
                Tables\Columns\TextColumn::make('category')->badge()->color('primary'),
                Tables\Columns\TextColumn::make('sort_order')->sortable()->alignCenter(),
                Tables\Columns\ToggleColumn::make('is_active'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->emptyStateHeading('No photos yet')
            ->emptyStateDescription('Upload temple, festival and event photos to populate the public gallery.')
            ->emptyStateIcon('heroicon-o-photo')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Upload first photo')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([12, 24, 48, 96])
            ->defaultPaginationPageOption(24);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'category', 'alt_text'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListGalleries::route('/'),
            'create' => Pages\CreateGallery::route('/create'),
            'edit'   => Pages\EditGallery::route('/{record}/edit'),
        ];
    }
}