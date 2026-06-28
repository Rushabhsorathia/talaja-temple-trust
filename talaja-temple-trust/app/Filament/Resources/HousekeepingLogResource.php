<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HousekeepingLogResource\Pages;
use App\Models\HousekeepingLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;

class HousekeepingLogResource extends Resource
{
    protected static ?string $model = HousekeepingLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationGroup = 'Accommodation';
    protected static ?string $recordTitleAttribute = 'room.number';
    protected static ?int $navigationSort = 50;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Log entry')->schema([
                Forms\Components\Select::make('room_id')->relationship('room', 'number')
                    ->required()->searchable()->preload()->native(false),
                Forms\Components\Select::make('user_id')->relationship('user', 'name')
                    ->label('Staff')->searchable()->preload()->native(false),
                Forms\Components\Select::make('status')->options([
                    'clean' => 'Clean', 'dirty' => 'Dirty', 'inspected' => 'Inspected',
                ])->required()->native(false),
                Forms\Components\Textarea::make('note')->rows(2)->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->deferFilters()
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'clean' => 'Clean', 'dirty' => 'Dirty', 'inspected' => 'Inspected',
                ])->multiple(),
                Tables\Filters\SelectFilter::make('room_id')->relationship('room', 'number')->searchable()->preload(),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(2)
            ->columns([
                Tables\Columns\TextColumn::make('room.number')->label('Room')->searchable()->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('status')->badge()->colors([
                    'success' => 'clean', 'danger' => 'dirty', 'info' => 'inspected',
                ]),
                Tables\Columns\TextColumn::make('user.name')->label('By')->placeholder('—'),
                Tables\Columns\TextColumn::make('note')->limit(50)->wrap()->placeholder('—')->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d-m-Y H:i')->sortable(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])])
            ->emptyStateHeading('No housekeeping logs yet')
            ->emptyStateDescription('Cleaning logs will appear here when staff log room status.')
            ->emptyStateIcon('heroicon-o-sparkles')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Log entry')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['room.number', 'note'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListHousekeepingLogs::route('/'),
            'create' => Pages\CreateHousekeepingLog::route('/create'),
            'edit'   => Pages\EditHousekeepingLog::route('/{record}/edit'),
        ];
    }
}