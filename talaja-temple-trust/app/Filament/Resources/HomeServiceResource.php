<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomeServiceResource\Pages;
use App\Models\HomeService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;

class HomeServiceResource extends Resource
{
    protected static ?string $model = HomeService::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationGroup = 'Site Content';
    protected static ?string $navigationLabel = 'Service Cards';
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?int $navigationSort = 31;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Service card')->schema([
                Forms\Components\TextInput::make('title')->required(),
                Forms\Components\Textarea::make('description')->rows(2)->columnSpanFull(),
            ])->columns(2),
            Forms\Components\Section::make('Link & icon')->schema([
                Forms\Components\Select::make('icon')->options([
                    'video' => 'Video', 'heart' => 'Heart', 'bed' => 'Bed',
                    'bag' => 'Bag', 'user' => 'User', 'globe' => 'Globe',
                ])->required()->native(false),
                Forms\Components\TextInput::make('href')->helperText('e.g. /donate'),
                Forms\Components\TextInput::make('badge')->helperText('Optional — e.g. "Live".'),
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
                Tables\Columns\TextColumn::make('title')->searchable()->sortable()->weight('bold')->wrap()
                    ->description(fn (HomeService $r) => $r->description),
                Tables\Columns\TextColumn::make('icon')->badge()->color('gray'),
                Tables\Columns\TextColumn::make('href')->placeholder('—'),
                Tables\Columns\TextColumn::make('badge')->badge()->color('danger')->placeholder('—'),
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
            ->emptyStateHeading('No service cards yet')
            ->emptyStateDescription('Service cards appear in the homepage grid.')
            ->emptyStateIcon('heroicon-o-squares-2x2')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Add card')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'description', 'href'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListHomeServices::route('/'),
            'create' => Pages\CreateHomeService::route('/create'),
            'edit'   => Pages\EditHomeService::route('/{record}/edit'),
        ];
    }
}