<?php

namespace App\Filament\Widgets;

use App\Models\RoomBooking;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentBookings extends BaseWidget
{
    protected static ?string $heading = 'Recent Bookings';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(RoomBooking::query()->latest()->limit(6))
            ->columns([
                TextColumn::make('guest_name')->label('Guest')->limit(18),
                TextColumn::make('amount')->money('INR')->weight('bold'),
                TextColumn::make('status')->badge()->colors([
                    'warning' => 'pending',
                    'success' => 'confirmed',
                    'info' => 'checked_in',
                    'gray' => 'checked_out',
                    'danger' => 'cancelled',
                ]),
            ])
            ->paginated(false);
    }
}
