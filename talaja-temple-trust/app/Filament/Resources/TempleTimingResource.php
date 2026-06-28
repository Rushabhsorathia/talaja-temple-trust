<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TempleTimingResource\Pages;
use App\Models\TempleTiming;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;

class TempleTimingResource extends Resource
{
    protected static ?string $model = TempleTiming::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Configuration';
    protected static ?string $navigationLabel = 'Darshan Timings';
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Timing')->schema([
                Forms\Components\Select::make('temple_id')->relationship('temple', 'name')
                    ->required()->native(false),
                Forms\Components\Select::make('type')->options([
                    'darshan' => 'Darshan', 'aarti' => 'Aarti', 'pooja' => 'Pooja',
                ])->required()->native(false),
                Forms\Components\TextInput::make('title')->required(),
                Forms\Components\TextInput::make('title_gu')->label('Title (Gujarati)'),
            ])->columns(2),
            Forms\Components\Section::make('Schedule')->schema([
                Forms\Components\TimePicker::make('start_time')->native(false),
                Forms\Components\TimePicker::make('end_time')->native(false),
                Forms\Components\Select::make('day_of_week')->options([
                    'Daily' => 'Daily', 'Weekdays' => 'Weekdays', 'Weekends' => 'Weekends',
                    'Purnima' => 'Purnima', 'Amavasya' => 'Amavasya',
                    'Monday' => 'Monday', 'Tuesday' => 'Tuesday', 'Wednesday' => 'Wednesday',
                    'Thursday' => 'Thursday', 'Friday' => 'Friday', 'Saturday' => 'Saturday', 'Sunday' => 'Sunday',
                    'On Request' => 'On Request',
                ])->native(false)->createOptionUsing(fn ($v) => $v),
                Forms\Components\TextInput::make('fee')->numeric()->default(0)->prefix('₹'),
            ])->columns(2),
            Forms\Components\Section::make('Display')->schema([
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
                Tables\Filters\SelectFilter::make('type')->options([
                    'darshan' => 'Darshan', 'aarti' => 'Aarti', 'pooja' => 'Pooja',
                ])->multiple(),
                Tables\Filters\SelectFilter::make('temple_id')->relationship('temple', 'name')->searchable()->preload(),
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            ->columns([
                Tables\Columns\TextColumn::make('type')->badge()->color(fn ($s) => match ($s) {
                    'darshan' => 'primary', 'aarti' => 'warning', 'pooja' => 'success', default => 'gray',
                }),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable()->weight('bold')->wrap(),
                Tables\Columns\TextColumn::make('start_time')->time('H:i')->placeholder('—')->alignCenter(),
                Tables\Columns\TextColumn::make('end_time')->time('H:i')->placeholder('—')->alignCenter(),
                Tables\Columns\TextColumn::make('day_of_week')->badge()->color('gray')->placeholder('—'),
                Tables\Columns\TextColumn::make('fee')->money('INR')->alignRight(),
                Tables\Columns\ToggleColumn::make('is_active'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->emptyStateHeading('No timings yet')
            ->emptyStateDescription('Add darshan, aarti and pooja timings shown on the public Temple Info page.')
            ->emptyStateIcon('heroicon-o-clock')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Add timing')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'title_gu'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTempleTimings::route('/'),
            'create' => Pages\CreateTempleTiming::route('/create'),
            'edit'   => Pages\EditTempleTiming::route('/{record}/edit'),
        ];
    }
}