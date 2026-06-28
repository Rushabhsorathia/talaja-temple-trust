<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomResource\Pages;
use App\Models\Room;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    protected static ?string $navigationGroup = 'Accommodation';
    protected static ?string $navigationLabel = 'Rooms';
    protected static ?string $recordTitleAttribute = 'number';
    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Room')->schema([
                Forms\Components\Select::make('room_type_id')->relationship('type', 'name')
                    ->required()->searchable()->preload()->native(false),
                Forms\Components\TextInput::make('number')->required()->placeholder('e.g. 101, D-1, S-1'),
                Forms\Components\TextInput::make('floor')->placeholder('Ground / First / Second'),
                Forms\Components\Select::make('housekeeping_status')->options([
                    'clean' => 'Clean', 'dirty' => 'Dirty', 'inspected' => 'Inspected',
                ])->default('clean')->required()->native(false),
                Forms\Components\Toggle::make('is_active')->default(true)->inline(false),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('number')
            ->deferFilters()
            ->filters([
                Tables\Filters\SelectFilter::make('room_type_id')->label('Type')
                    ->relationship('type', 'name')->searchable()->preload(),
                Tables\Filters\SelectFilter::make('housekeeping_status')->options([
                    'clean' => 'Clean', 'dirty' => 'Dirty', 'inspected' => 'Inspected',
                ])->multiple(),
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            ->columns([
                Tables\Columns\TextColumn::make('number')->searchable()->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('type.name')->label('Type')->badge()->color('primary'),
                Tables\Columns\TextColumn::make('type.tariff')->label('Tariff')->money('INR')->toggleable()->alignRight(),
                Tables\Columns\TextColumn::make('floor')->toggleable()->placeholder('—'),
                Tables\Columns\TextColumn::make('housekeeping_status')->badge()->colors([
                    'success' => 'clean', 'danger' => 'dirty', 'info' => 'inspected',
                ]),
                Tables\Columns\ToggleColumn::make('is_active'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('markClean')->label('Mark clean')->icon('heroicon-o-sparkles')
                        ->action(fn (Room $r) => $r->update(['housekeeping_status' => 'clean']))
                        ->visible(fn (Room $r) => $r->housekeeping_status !== 'clean'),
                    Tables\Actions\Action::make('markDirty')->label('Mark dirty')->icon('heroicon-o-exclamation-triangle')
                        ->action(fn (Room $r) => $r->update(['housekeeping_status' => 'dirty']))
                        ->visible(fn (Room $r) => $r->housekeeping_status !== 'dirty')
                        ->color('warning'),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('markClean')->label('Mark all clean')
                    ->icon('heroicon-o-sparkles')
                    ->action(fn ($a) => $a->getRecords()->each->update(['housekeeping_status' => 'clean'])),
            ])])
            ->emptyStateHeading('No rooms yet')
            ->emptyStateDescription('Add rooms after creating room types.')
            ->emptyStateIcon('heroicon-o-home-modern')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Add room')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['number', 'floor'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'edit'   => Pages\EditRoom::route('/{record}/edit'),
        ];
    }
}