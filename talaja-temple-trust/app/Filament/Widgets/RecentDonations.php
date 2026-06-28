<?php

namespace App\Filament\Widgets;

use App\Models\Donation;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentDonations extends BaseWidget
{
    protected static ?string $heading = 'Recent Donations';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(Donation::query()->where('status', 'success')->latest()->limit(6))
            ->columns([
                TextColumn::make('donor_name')->label('Donor')->default('-')->limit(18),
                TextColumn::make('amount')->money('INR')->weight('bold'),
                TextColumn::make('paid_at')->dateTime('d M')->color('gray'),
            ])
            ->paginated(false);
    }
}
