<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Shop';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Product')->schema([
                Forms\Components\TextInput::make('name')->required()->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state))),
                Forms\Components\TextInput::make('name_gu')->label('Name (Gujarati)'),
                Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true)
                    ->helperText('URL key, e.g. prasadam-pack-500g.'),
                Forms\Components\Select::make('category')->options([
                    'Prasad' => 'Prasad', 'Books' => 'Books',
                    'Souvenirs' => 'Souvenirs', 'Pooja Items' => 'Pooja Items',
                    'Stationery' => 'Stationery', 'Other' => 'Other',
                ])->default('Prasad')->native(false)->createOptionUsing(fn ($v) => $v),
            ])->columns(2),
            Forms\Components\Section::make('Description')->schema([
                Forms\Components\Textarea::make('description')->rows(2)->columnSpanFull(),
                Forms\Components\Textarea::make('description_gu')->label('Description (Gujarati)')->rows(2)->columnSpanFull(),
            ]),
            Forms\Components\Section::make('Pricing & stock')->schema([
                Forms\Components\TextInput::make('price')->numeric()->required()->prefix('₹'),
                Forms\Components\TextInput::make('compare_at_price')->numeric()->prefix('₹')
                    ->helperText('Strike-through price for offers.'),
                Forms\Components\TextInput::make('stock')->numeric()->default(0)->helperText('In-stock units.'),
            ])->columns(3),
            Forms\Components\Section::make('Image & display')->schema([
                Forms\Components\FileUpload::make('image_path')->image()
                    ->imagePreviewHeight('180')->directory('products')
                    ->panelAspectRatio('1:1')->panelLayout('integrated'),
                Forms\Components\Toggle::make('is_active')->default(true)->inline(false),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->deferFilters()
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options(fn () => Product::query()->whereNotNull('category')->distinct()->pluck('category', 'category')->sort())
                    ->multiple(),
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
                Tables\Filters\Filter::make('low_stock')->label('Low stock (≤ 10)')->toggle()
                    ->query(fn ($q) => $q->where('stock', '<=', 10)),
                Tables\Filters\Filter::make('out_of_stock')->label('Out of stock')->toggle()
                    ->query(fn ($q) => $q->where('stock', '<=', 0)),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(4)
            ->headerActions([
                Tables\Actions\Action::make('viewPublic')
                    ->label('View Shop')->icon('heroicon-o-arrow-top-right-on-square')
                    ->url('/shop', shouldOpenInNewTab: true)->color('gray'),
            ])
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')->square()->label(''),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable()->weight('bold')->wrap()
                    ->description(fn (Product $r) => $r->slug),
                Tables\Columns\TextColumn::make('category')->badge()->color('primary'),
                Tables\Columns\TextColumn::make('price')->money('INR')->sortable()->alignRight()
                    ->summarize([Tables\Columns\Summarizers\Sum::make()->money('INR')->label('Stock value')]),
                Tables\Columns\TextColumn::make('compare_at_price')->money('INR')->toggleable()->placeholder('—')->alignRight(),
                Tables\Columns\TextColumn::make('stock')->sortable()->alignCenter()
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        (int) $state === 0 => 'danger',
                        (int) $state < 10 => 'warning',
                        default => 'success',
                    })
                    ->summarize([Tables\Columns\Summarizers\Sum::make()->label('Total stock')]),
                Tables\Columns\ToggleColumn::make('is_active'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('activate')->label('Activate')
                    ->icon('heroicon-o-check')->color('success')
                    ->action(fn ($a) => $a->getRecords()->each->update(['is_active' => true])),
            ])])
            ->emptyStateHeading('No products yet')
            ->emptyStateDescription('Add prasadam, books, souvenirs and other shop items here.')
            ->emptyStateIcon('heroicon-o-shopping-bag')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Add first product')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'slug', 'description', 'category'];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('stock', '<=', 0)->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit'   => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}