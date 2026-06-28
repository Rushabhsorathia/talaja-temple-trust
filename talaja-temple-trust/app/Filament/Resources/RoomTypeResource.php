<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomTypeResource\Pages;
use App\Models\RoomType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RoomTypeResource extends Resource
{
    protected static ?string $model = RoomType::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationGroup = 'Accommodation';
    protected static ?string $navigationLabel = 'Room Types';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Room type')->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('name_gu')->label('Name (Gujarati)'),
                Forms\Components\Select::make('temple_id')->relationship('temple', 'name')->native(false),
            ])->columns(3),
            Forms\Components\Section::make('Pricing & capacity')->schema([
                Forms\Components\TextInput::make('tariff')->numeric()->required()->prefix('₹')->helperText('Per night.'),
                Forms\Components\TextInput::make('capacity')->numeric()->default(2)->suffix('guests'),
                Forms\Components\TagsInput::make('amenities')->placeholder('AC, TV, Wi-Fi')->suggestions([
                    'AC', 'Non-AC', 'Wi-Fi', 'TV', 'Geyser', 'Parking', 'Breakfast', 'Hot Water', 'Attached Bathroom',
                ]),
            ])->columns(3),
            Forms\Components\Section::make('Description & display')->schema([
                Forms\Components\Textarea::make('description')->rows(2)->columnSpanFull(),
                Forms\Components\Toggle::make('is_active')->default(true)->inline(false),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('tariff', 'asc')
            ->deferFilters()
            ->filters([Tables\Filters\TernaryFilter::make('is_active')->label('Active')])
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable()->weight('bold')->wrap()
                    ->description(fn (RoomType $r) => $r->name_gu),
                Tables\Columns\TextColumn::make('tariff')->money('INR')->sortable()->alignRight(),
                Tables\Columns\TextColumn::make('capacity')->suffix(' guests')->alignCenter(),
                Tables\Columns\TextColumn::make('rooms_count')->counts('rooms')->label('Rooms')->alignCenter()->badge()->color('info'),
                Tables\Columns\ToggleColumn::make('is_active'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->emptyStateHeading('No room types yet')
            ->emptyStateDescription('Add room categories (Standard, Deluxe, Dormitory, ...) with tariff and capacity.')
            ->emptyStateIcon('heroicon-o-squares-2x2')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Add room type')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'description'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRoomTypes::route('/'),
            'create' => Pages\CreateRoomType::route('/create'),
            'edit'   => Pages\EditRoomType::route('/{record}/edit'),
        ];
    }
}