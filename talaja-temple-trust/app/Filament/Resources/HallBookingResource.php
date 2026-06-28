<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HallBookingResource\Pages;
use App\Models\HallBooking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;

class HallBookingResource extends Resource
{
    protected static ?string $model = HallBooking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Accommodation';
    protected static ?string $navigationLabel = 'Hall Bookings';
    protected static ?string $recordTitleAttribute = 'booking_no';
    protected static ?int $navigationSort = 41;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Booking')->schema([
                Forms\Components\TextInput::make('booking_no')->required()->unique(ignoreRecord: true),
                Forms\Components\Select::make('meeting_hall_id')->relationship('hall', 'name')
                    ->required()->searchable()->preload()->native(false),
                Forms\Components\Select::make('user_id')->relationship('user', 'name')
                    ->label('Devotee')->searchable()->preload()->native(false),
                Forms\Components\Select::make('status')->options([
                    'pending' => 'Pending', 'confirmed' => 'Confirmed', 'cancelled' => 'Cancelled',
                ])->default('pending')->required()->native(false),
            ])->columns(2),
            Forms\Components\Section::make('Event')->schema([
                Forms\Components\TextInput::make('guest_name')->required(),
                Forms\Components\TextInput::make('guest_mobile')->required()->tel(),
                Forms\Components\DatePicker::make('event_date')->required()->native(false),
                Forms\Components\TimePicker::make('start_time')->required(),
                Forms\Components\TimePicker::make('end_time')->required()->after('start_time'),
                Forms\Components\TextInput::make('attendees')->numeric()->default(1),
                Forms\Components\TextInput::make('amount')->numeric()->prefix('₹'),
                Forms\Components\Textarea::make('note')->rows(2)->columnSpanFull(),
            ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('event_date', 'desc')
            ->deferFilters()
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending' => 'Pending', 'confirmed' => 'Confirmed', 'cancelled' => 'Cancelled',
                ])->multiple(),
                Tables\Filters\SelectFilter::make('meeting_hall_id')->label('Hall')
                    ->relationship('hall', 'name')->searchable()->preload(),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(2)
            ->columns([
                Tables\Columns\TextColumn::make('booking_no')->searchable()->sortable()->weight('bold')->copyable(),
                Tables\Columns\TextColumn::make('hall.name')->label('Hall')->badge()->color('primary')->searchable(),
                Tables\Columns\TextColumn::make('guest_name')->searchable()->wrap()
                    ->description(fn (HallBooking $r) => $r->guest_mobile),
                Tables\Columns\TextColumn::make('event_date')->date('d-m-Y')->sortable(),
                Tables\Columns\TextColumn::make('start_time')->time('H:i')->alignCenter(),
                Tables\Columns\TextColumn::make('attendees')->suffix(' pax')->alignCenter(),
                Tables\Columns\TextColumn::make('amount')->money('INR')->sortable()->alignRight()
                    ->summarize([Tables\Columns\Summarizers\Sum::make()->money('INR')->label('Total revenue')]),
                Tables\Columns\TextColumn::make('status')->badge()->colors([
                    'warning' => 'pending', 'success' => 'confirmed', 'danger' => 'cancelled',
                ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('confirm')->label('Confirm')->icon('heroicon-o-check')
                        ->visible(fn (HallBooking $r) => $r->status === 'pending')
                        ->action(fn (HallBooking $r) => $r->update(['status' => 'confirmed']))
                        ->requiresConfirmation(),
                    Tables\Actions\Action::make('cancel')->label('Cancel')->icon('heroicon-o-x-circle')
                        ->color('danger')->visible(fn (HallBooking $r) => $r->status !== 'cancelled')
                        ->action(fn (HallBooking $r) => $r->update(['status' => 'cancelled']))
                        ->requiresConfirmation(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('confirm')->label('Confirm selected')
                    ->icon('heroicon-o-check')->color('success')
                    ->action(fn ($a) => $a->getRecords()->each->update(['status' => 'confirmed'])),
            ])])
            ->emptyStateHeading('No hall bookings yet')
            ->emptyStateDescription('Event hall bookings will appear here.')
            ->emptyStateIcon('heroicon-o-calendar')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('New booking')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['booking_no', 'guest_name', 'guest_mobile'];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() ?: null;
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListHallBookings::route('/'),
            'create' => Pages\CreateHallBooking::route('/create'),
            'edit'   => Pages\EditHallBooking::route('/{record}/edit'),
        ];
    }
}