<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Models\Banner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Site Content';
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?int $navigationSort = 60;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Banner')->schema([
                Forms\Components\TextInput::make('title')->helperText('Internal label (optional).'),
                Forms\Components\FileUpload::make('image_path')->image()->required()
                    ->imagePreviewHeight('180')->directory('banners')
                    ->panelAspectRatio('21:9')->panelLayout('integrated'),
                Forms\Components\TextInput::make('link')->helperText('Optional URL the banner links to.'),
            ])->columns(2),
            Forms\Components\Section::make('Schedule & visibility')->schema([
                Forms\Components\DateTimePicker::make('publish_at')->helperText('When the banner goes live (optional).'),
                Forms\Components\DateTimePicker::make('unpublish_at')->helperText('When it is hidden (optional).'),
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
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')->square()->height(50)->label(''),
                Tables\Columns\TextColumn::make('title')->searchable()->placeholder('—'),
                Tables\Columns\TextColumn::make('link')->limit(30)->toggleable(),
                Tables\Columns\TextColumn::make('sort_order')->sortable()->alignCenter(),
                Tables\Columns\TextColumn::make('publish_at')->dateTime('d-m-Y H:i')->placeholder('—')->toggleable(),
                Tables\Columns\TextColumn::make('unpublish_at')->dateTime('d-m-Y H:i')->placeholder('—')->toggleable(),
                Tables\Columns\ToggleColumn::make('is_active'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->emptyStateHeading('No banners yet')
            ->emptyStateDescription('Add promotional or announcement banners for the site.')
            ->emptyStateIcon('heroicon-o-rectangle-stack')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Add banner')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'link'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit'   => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}