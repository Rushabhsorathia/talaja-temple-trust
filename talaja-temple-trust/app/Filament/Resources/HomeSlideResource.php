<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomeSlideResource\Pages;
use App\Models\HomeSlide;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;

class HomeSlideResource extends Resource
{
    protected static ?string $model = HomeSlide::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'Site Content';
    protected static ?string $navigationLabel = 'Hero Slides';
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?int $navigationSort = 30;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Slide')->schema([
                Forms\Components\FileUpload::make('image_path')->image()
                    ->imagePreviewHeight('220')->directory('slides')
                    ->panelAspectRatio('21:9')->panelLayout('integrated')
                    ->helperText('Wide hero image. Recommended 1920×800px.'),
                Forms\Components\TextInput::make('title')->required(),
                Forms\Components\TextInput::make('subtitle'),
                Forms\Components\TextInput::make('tag')->helperText('e.g. || Jay Mataji ||'),
            ])->columns(2),
            Forms\Components\Section::make('Button & display')->schema([
                Forms\Components\TextInput::make('button_label')->helperText('e.g. Donate Now'),
                Forms\Components\TextInput::make('button_href')->helperText('e.g. /donate'),
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
            ->filters([Tables\Filters\TernaryFilter::make('is_active')->label('Active')],
                layout: FiltersLayout::AboveContent)
            ->headerActions([
                Tables\Actions\Action::make('viewPublic')
                    ->label('View Home page')->icon('heroicon-o-arrow-top-right-on-square')
                    ->url('/', shouldOpenInNewTab: true)->color('gray'),
            ])
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')->square()->height(60)->label(''),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable()->weight('bold')->wrap()
                    ->description(fn (HomeSlide $r) => $r->subtitle),
                Tables\Columns\TextColumn::make('tag')->badge()->color('warning'),
                Tables\Columns\TextColumn::make('button_label')->placeholder('—')->toggleable(),
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
            ->emptyStateHeading('No hero slides yet')
            ->emptyStateDescription('Slides rotate in the homepage carousel.')
            ->emptyStateIcon('heroicon-o-photo')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Add slide')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'subtitle', 'tag'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListHomeSlides::route('/'),
            'create' => Pages\CreateHomeSlide::route('/create'),
            'edit'   => Pages\EditHomeSlide::route('/{record}/edit'),
        ];
    }
}