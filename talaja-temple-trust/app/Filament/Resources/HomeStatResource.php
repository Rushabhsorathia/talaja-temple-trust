<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomeStatResource\Pages;
use App\Models\HomeStat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;

class HomeStatResource extends Resource
{
    protected static ?string $model = HomeStat::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Site Content';
    protected static ?string $navigationLabel = 'Stats Cards';
    protected static ?string $recordTitleAttribute = 'label';
    protected static ?int $navigationSort = 32;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Stat')->schema([
                Forms\Components\TextInput::make('value')->required()->helperText('e.g. 5L+, 100+, 24/7'),
                Forms\Components\TextInput::make('label')->required()->helperText('e.g. Devotees Served'),
                Forms\Components\Select::make('icon')->options([
                    'users' => 'Users', 'clock' => 'Clock', 'soup' => 'Soup',
                    'wifi' => 'Wifi', 'heart' => 'Heart', 'globe' => 'Globe',
                ])->default('users')->native(false),
            ])->columns(3),
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
            ->filters([Tables\Filters\TernaryFilter::make('is_active')->label('Active')],
                layout: FiltersLayout::AboveContent)
            ->headerActions([
                Tables\Actions\Action::make('viewPublic')
                    ->label('View Home page')->icon('heroicon-o-arrow-top-right-on-square')
                    ->url('/', shouldOpenInNewTab: true)->color('gray'),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('value')->searchable()->sortable()->weight('bold')->size('lg'),
                Tables\Columns\TextColumn::make('label')->weight('medium')->wrap(),
                Tables\Columns\TextColumn::make('icon')->badge()->color('gray'),
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
            ->emptyStateHeading('No stats yet')
            ->emptyStateDescription('Stats appear in the homepage stat row.')
            ->emptyStateIcon('heroicon-o-chart-bar')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Add stat')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['value', 'label'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListHomeStats::route('/'),
            'create' => Pages\CreateHomeStat::route('/create'),
            'edit'   => Pages\EditHomeStat::route('/{record}/edit'),
        ];
    }
}