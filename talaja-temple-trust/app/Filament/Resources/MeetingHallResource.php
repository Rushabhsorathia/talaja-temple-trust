<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MeetingHallResource\Pages;
use App\Models\MeetingHall;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;

class MeetingHallResource extends Resource
{
    protected static ?string $model = MeetingHall::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationGroup = 'Accommodation';
    protected static ?string $navigationLabel = 'Meeting Halls';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 40;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Hall')->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('name_gu')->label('Name (Gujarati)'),
                Forms\Components\Select::make('temple_id')->relationship('temple', 'name')->native(false),
            ])->columns(3),
            Forms\Components\Section::make('Pricing & capacity')->schema([
                Forms\Components\TextInput::make('capacity')->numeric()->required()->suffix('pax'),
                Forms\Components\TextInput::make('tariff')->numeric()->required()->prefix('₹'),
                Forms\Components\TagsInput::make('amenities')->placeholder('AC, Sound, Projector')->suggestions([
                    'AC', 'Sound System', 'Projector', 'Stage', 'Parking', 'Catering',
                ]),
            ])->columns(3),
            Forms\Components\Section::make('Display')->schema([
                Forms\Components\Toggle::make('is_active')->default(true)->inline(false),
            ])->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->deferFilters()
            ->filters([Tables\Filters\TernaryFilter::make('is_active')->label('Active')],
                layout: FiltersLayout::AboveContent)
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable()->weight('bold')->wrap(),
                Tables\Columns\TextColumn::make('capacity')->suffix(' pax')->sortable()->alignCenter(),
                Tables\Columns\TextColumn::make('tariff')->money('INR')->sortable()->alignRight(),
                Tables\Columns\TextColumn::make('bookings_count')->counts('bookings')->label('Bookings')->alignCenter(),
                Tables\Columns\ToggleColumn::make('is_active'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->emptyStateHeading('No halls yet')
            ->emptyStateDescription('Add meeting halls and event spaces available for booking.')
            ->emptyStateIcon('heroicon-o-building-office-2')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Add hall')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListMeetingHalls::route('/'),
            'create' => Pages\CreateMeetingHall::route('/create'),
            'edit'   => Pages\EditMeetingHall::route('/{record}/edit'),
        ];
    }
}