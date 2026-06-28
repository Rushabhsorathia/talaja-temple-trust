<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FacilityResource\Pages;
use App\Models\Facility;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;

class FacilityResource extends Resource
{
    protected static ?string $model = Facility::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationGroup = 'Site Content';
    protected static ?string $navigationLabel = 'Facilities';
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?int $navigationSort = 33;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Facility')->schema([
                Forms\Components\TextInput::make('title')->required(),
                Forms\Components\Textarea::make('description')->rows(2)->columnSpanFull(),
            ])->columns(2),
            Forms\Components\Section::make('Icon & image')->schema([
                Forms\Components\Select::make('icon')->options([
                    'bed' => 'Bed', 'home' => 'Home', 'soup' => 'Soup',
                    'flame' => 'Flame', 'cross' => 'Medical', 'trees' => 'Trees',
                ])->required()->native(false),
                Forms\Components\FileUpload::make('image_path')->image()
                    ->imagePreviewHeight('160')->directory('facilities')
                    ->panelAspectRatio('16:9')->panelLayout('integrated'),
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
            ->filters([Tables\Filters\TernaryFilter::make('is_active')->label('Active')],
                layout: FiltersLayout::AboveContent)
            ->headerActions([
                Tables\Actions\Action::make('viewPublic')
                    ->label('View Facilities page')->icon('heroicon-o-arrow-top-right-on-square')
                    ->url('/community-welfare', shouldOpenInNewTab: true)->color('gray'),
            ])
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')->square()->height(50)->label(''),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable()->weight('bold')->wrap()
                    ->description(fn (Facility $r) => \Illuminate\Support\Str::limit($r->description, 60)),
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
            ->emptyStateHeading('No facilities yet')
            ->emptyStateDescription('Facilities appear on the public Community Welfare page.')
            ->emptyStateIcon('heroicon-o-building-office-2')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Add facility')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'description'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListFacilities::route('/'),
            'create' => Pages\CreateFacility::route('/create'),
            'edit'   => Pages\EditFacility::route('/{record}/edit'),
        ];
    }
}