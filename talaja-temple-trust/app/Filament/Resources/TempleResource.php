<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TempleResource\Pages;
use App\Models\Temple;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;

class TempleResource extends Resource
{
    protected static ?string $model = Temple::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    protected static ?string $navigationGroup = 'Configuration';
    protected static ?string $navigationLabel = 'Temple Profile';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Identity')->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true)
                    ->helperText('Used in URLs (e.g. talaja-temple).'),
                Forms\Components\Toggle::make('is_primary')->inline(false)
                    ->helperText('The active temple shown on the public site.'),
                Forms\Components\Toggle::make('is_active')->default(true)->inline(false),
            ])->columns(2),
            Forms\Components\Section::make('Contact')->schema([
                Forms\Components\Textarea::make('address')->rows(2)->columnSpanFull(),
                Forms\Components\TextInput::make('phone')->tel(),
                Forms\Components\TextInput::make('email')->email(),
                Forms\Components\Textarea::make('map_embed')->rows(2)->columnSpanFull()
                    ->helperText('Google Maps embed URL.'),
            ])->columns(2),
            Forms\Components\Section::make('Branding')->schema([
                Forms\Components\FileUpload::make('logo_path')->image()
                    ->imagePreviewHeight('100')->directory('temple')
                    ->circleCrop()->panelLayout('integrated'),
            ])->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->deferFilters()
            ->filters([
                Tables\Filters\TernaryFilter::make('is_primary')->label('Primary'),
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(2)
            ->columns([
                Tables\Columns\ImageColumn::make('logo_path')->circular()->height(40)->label(''),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable()->weight('bold')->wrap()
                    ->description(fn (Temple $r) => $r->address),
                Tables\Columns\TextColumn::make('slug')->badge()->color('gray')->copyable(),
                Tables\Columns\TextColumn::make('phone')->placeholder('—'),
                Tables\Columns\TextColumn::make('email')->placeholder('—')->toggleable(),
                Tables\Columns\IconColumn::make('is_primary')->boolean(),
                Tables\Columns\ToggleColumn::make('is_active'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->emptyStateHeading('No temple yet')
            ->emptyStateDescription('Add the primary temple shown across the public site.')
            ->emptyStateIcon('heroicon-o-building-library')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Add temple')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'slug', 'phone', 'email', 'address'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTemples::route('/'),
            'create' => Pages\CreateTemple::route('/create'),
            'edit'   => Pages\EditTemple::route('/{record}/edit'),
        ];
    }
}