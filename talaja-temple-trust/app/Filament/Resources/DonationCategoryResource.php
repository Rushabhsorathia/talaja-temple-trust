<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DonationCategoryResource\Pages;
use App\Models\DonationCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;

class DonationCategoryResource extends Resource
{
    protected static ?string $model = DonationCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';
    protected static ?string $navigationGroup = 'Donations';
    protected static ?string $navigationLabel = 'Categories';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Category')->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('name_gu')->label('Name (Gujarati)'),
                Forms\Components\Textarea::make('description')->rows(2)->columnSpanFull(),
            ])->columns(2),
            Forms\Components\Section::make('Settings')->schema([
                Forms\Components\Toggle::make('is_80g_eligible')->default(true)->inline(false)
                    ->helperText('Donations in this category are eligible for 80-G tax receipt.'),
                Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                Forms\Components\Toggle::make('is_active')->default(true)->inline(false),
            ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->deferFilters()
            ->filters([
                Tables\Filters\TernaryFilter::make('is_80g_eligible')->label('80-G eligible'),
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(2)
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable()->weight('bold')->wrap()
                    ->description(fn (DonationCategory $r) => $r->name_gu),
                Tables\Columns\TextColumn::make('description')->limit(50)->wrap()->toggleable(),
                Tables\Columns\IconColumn::make('is_80g_eligible')->boolean()->label('80-G'),
                Tables\Columns\TextColumn::make('donations_count')->counts('donations')->label('Donations')->alignCenter()->badge()->color('info'),
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
            ->emptyStateHeading('No categories yet')
            ->emptyStateDescription('Create donation categories shown on the public Donate page.')
            ->emptyStateIcon('heroicon-o-rectangle-group')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Add category')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'name_gu', 'description'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListDonationCategories::route('/'),
            'create' => Pages\CreateDonationCategory::route('/create'),
            'edit'   => Pages\EditDonationCategory::route('/{record}/edit'),
        ];
    }
}