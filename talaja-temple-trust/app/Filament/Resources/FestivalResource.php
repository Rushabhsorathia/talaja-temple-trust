<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FestivalResource\Pages;
use App\Models\Festival;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Database\Eloquent\Builder;

class FestivalResource extends Resource
{
    protected static ?string $model = Festival::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Site Content';
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?int $navigationSort = 53;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Festival')->schema([
                Forms\Components\TextInput::make('title')->required(),
                Forms\Components\TextInput::make('title_gu')->label('Title (Gujarati)'),
                Forms\Components\Select::make('temple_id')->relationship('temple', 'name')->native(false),
            ])->columns(3),
            Forms\Components\Section::make('Dates')->schema([
                Forms\Components\DatePicker::make('start_date')->required()->native(false),
                Forms\Components\DatePicker::make('end_date')->native(false)->helperText('Leave blank for single-day festivals.'),
            ])->columns(2),
            Forms\Components\Section::make('Details')->schema([
                Forms\Components\Textarea::make('description')->rows(3)->columnSpanFull(),
                Forms\Components\FileUpload::make('image_path')->image()
                    ->imagePreviewHeight('160')->directory('festivals')
                    ->panelAspectRatio('16:9')->panelLayout('integrated'),
                Forms\Components\Toggle::make('is_active')->default(true)->inline(false),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('start_date', 'asc')
            ->deferFilters()
            ->filters([
                Tables\Filters\Filter::make('upcoming')->label('Upcoming only')->toggle()
                    ->query(fn (Builder $q) => $q->where('start_date', '>=', now())),
                Tables\Filters\Filter::make('past')->label('Past only')->toggle()
                    ->query(fn (Builder $q) => $q->where('start_date', '<', now())),
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            ->headerActions([
                Tables\Actions\Action::make('viewPublic')
                    ->label('View Temple Info')->icon('heroicon-o-arrow-top-right-on-square')
                    ->url('/temple-info', shouldOpenInNewTab: true)->color('gray'),
            ])
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')->square()->height(50)->label(''),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable()->weight('bold')->wrap(),
                Tables\Columns\TextColumn::make('start_date')->date('d-m-Y')->sortable()
                    ->description(fn (Festival $r) => $r->end_date ? '→ '.$r->end_date->format('d-m-Y') : 'Single day'),
                Tables\Columns\TextColumn::make('temple.name')->label('Temple')->toggleable()->placeholder('—'),
                Tables\Columns\ToggleColumn::make('is_active'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->emptyStateHeading('No festivals yet')
            ->emptyStateDescription('Add upcoming and past festivals to populate the calendar.')
            ->emptyStateIcon('heroicon-o-calendar-days')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Add festival')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'description'];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('start_date', '>=', now())->count() ?: null;
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListFestivals::route('/'),
            'create' => Pages\CreateFestival::route('/create'),
            'edit'   => Pages\EditFestival::route('/{record}/edit'),
        ];
    }
}