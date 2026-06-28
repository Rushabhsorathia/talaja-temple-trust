<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomBookingResource\Pages;
use App\Models\RoomBooking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Database\Eloquent\Builder;

class RoomBookingResource extends Resource
{
    protected static ?string $model = RoomBooking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Accommodation';
    protected static ?string $navigationLabel = 'Room Bookings';
    protected static ?string $recordTitleAttribute = 'booking_no';
    protected static ?int $navigationSort = 30;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Booking')->schema([
                Forms\Components\TextInput::make('booking_no')->required()->unique(ignoreRecord: true)
                    ->helperText('Auto-generated if blank.'),
                Forms\Components\Select::make('room_id')->relationship('room', 'number')
                    ->required()->searchable()->preload()->native(false),
                Forms\Components\Select::make('user_id')->relationship('user', 'name')
                    ->label('Devotee')->searchable()->preload()->native(false),
                Forms\Components\Select::make('status')->options([
                    'pending' => 'Pending', 'confirmed' => 'Confirmed',
                    'checked_in' => 'Checked In', 'checked_out' => 'Checked Out',
                    'cancelled' => 'Cancelled',
                ])->required()->default('pending')->native(false),
                Forms\Components\Select::make('payment_mode')->options([
                    'online' => 'Online', 'pay_at_temple' => 'Pay at temple',
                ])->native(false),
            ])->columns(2),
            Forms\Components\Section::make('Guest')->schema([
                Forms\Components\TextInput::make('guest_name')->required(),
                Forms\Components\TextInput::make('guest_email')->email(),
                Forms\Components\TextInput::make('guest_mobile')->required()->tel(),
                Forms\Components\TextInput::make('guests')->numeric()->default(1),
            ])->columns(2),
            Forms\Components\Section::make('Stay')->schema([
                Forms\Components\DatePicker::make('check_in')->required()->native(false),
                Forms\Components\DatePicker::make('check_out')->required()->native(false)->after('check_in'),
                Forms\Components\TextInput::make('amount')->numeric()->prefix('₹'),
                Forms\Components\TextInput::make('gateway_transaction_id'),
                Forms\Components\Textarea::make('note')->rows(2)->columnSpanFull(),
            ])->columns(2),
            Forms\Components\Section::make('Check-in / out times')->schema([
                Forms\Components\DateTimePicker::make('checked_in_at'),
                Forms\Components\DateTimePicker::make('checked_out_at'),
            ])->columns(2)->collapsed(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('check_in', 'desc')
            ->deferFilters()
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending' => 'Pending', 'confirmed' => 'Confirmed',
                    'checked_in' => 'Checked In', 'checked_out' => 'Checked Out', 'cancelled' => 'Cancelled',
                ])->multiple(),
                Tables\Filters\SelectFilter::make('room_id')->label('Room')
                    ->relationship('room', 'number')->searchable()->preload(),
                Tables\Filters\Filter::make('check_in')
                    ->label('Stay between')
                    ->form([
                        Forms\Components\DatePicker::make('from')->native(false),
                        Forms\Components\DatePicker::make('to')->native(false),
                    ])
                    ->columns(2)
                    ->query(function (Builder $q, array $data) {
                        return $q->when($data['from'] ?? null, fn ($q, $d) => $q->whereDate('check_in', '>=', $d))
                                 ->when($data['to'] ?? null, fn ($q, $d) => $q->whereDate('check_in', '<=', $d));
                    }),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            ->headerActions([
                Tables\Actions\Action::make('viewPublic')
                    ->label('View Bookings page')->icon('heroicon-o-arrow-top-right-on-square')
                    ->url('/bookings', shouldOpenInNewTab: true)->color('gray'),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('booking_no')->searchable()->sortable()->weight('bold')->copyable(),
                Tables\Columns\TextColumn::make('guest_name')->searchable()->sortable()->wrap()
                    ->description(fn (RoomBooking $r) => $r->guest_mobile),
                Tables\Columns\TextColumn::make('room.number')->label('Room')->badge()->color('primary'),
                Tables\Columns\TextColumn::make('check_in')->date('d-m-Y')->sortable(),
                Tables\Columns\TextColumn::make('check_out')->date('d-m-Y'),
                Tables\Columns\TextColumn::make('guests')->suffix(' pax')->alignCenter()->toggleable(),
                Tables\Columns\TextColumn::make('amount')->money('INR')->sortable()->alignRight()
                    ->summarize([Tables\Columns\Summarizers\Sum::make()->money('INR')->label('Total revenue')]),
                Tables\Columns\TextColumn::make('status')->badge()->colors([
                    'warning' => 'pending', 'success' => 'confirmed',
                    'info' => 'checked_in', 'gray' => 'checked_out', 'danger' => 'cancelled',
                ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('checkIn')->label('Check in')->icon('heroicon-o-arrow-right-end-on-rectangle')
                        ->visible(fn (RoomBooking $r) => in_array($r->status, ['pending', 'confirmed']))
                        ->action(fn (RoomBooking $r) => $r->update(['status' => 'checked_in', 'checked_in_at' => now()]))
                        ->requiresConfirmation(),
                    Tables\Actions\Action::make('checkOut')->label('Check out')->icon('heroicon-o-arrow-left-start-on-rectangle')
                        ->visible(fn (RoomBooking $r) => $r->status === 'checked_in')
                        ->action(fn (RoomBooking $r) => $r->update(['status' => 'checked_out', 'checked_out_at' => now()]))
                        ->requiresConfirmation(),
                    Tables\Actions\Action::make('cancel')->label('Cancel')->icon('heroicon-o-x-circle')
                        ->color('danger')->visible(fn (RoomBooking $r) => ! in_array($r->status, ['checked_out', 'cancelled']))
                        ->action(fn (RoomBooking $r) => $r->update(['status' => 'cancelled']))
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
            ->emptyStateHeading('No bookings yet')
            ->emptyStateDescription('Room bookings made on the public site will appear here.')
            ->emptyStateIcon('heroicon-o-calendar-days')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('New booking')->icon('heroicon-o-plus'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['booking_no', 'guest_name', 'guest_email', 'guest_mobile'];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRoomBookings::route('/'),
            'create' => Pages\CreateRoomBooking::route('/create'),
            'edit'   => Pages\EditRoomBooking::route('/{record}/edit'),
        ];
    }
}