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

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Donation::query()->where('status', 'success')->latest()->limit(10))
            ->columns([
                TextColumn::make('receipt_no')->searchable(),
                TextColumn::make('donor_name')->default('-'),
                TextColumn::make('amount')->money('INR'),
                TextColumn::make('payment_mode')->badge(),
                TextColumn::make('paid_at')->dateTime('d-m-Y H:i'),
            ])
            ->paginated(false);
    }
}
