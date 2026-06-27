<?php

namespace App\Filament\Widgets;

use App\Models\Donation;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class DonationChart extends ChartWidget
{
    protected static ?string $heading = 'Donations (last 14 days)';

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $days = collect(range(13, 0))->map(fn ($d) => Carbon::today()->subDays($d));

        $amounts = $days->map(fn ($d) => (float) Donation::where('status', 'success')
            ->whereDate('paid_at', $d)->sum('amount'))->toArray();

        return [
            'datasets' => [[
                'label' => 'Donations (₹)',
                'data' => $amounts,
                'borderColor' => '#f06106',
                'backgroundColor' => 'rgba(240,97,6,0.1)',
                'fill' => true,
            ]],
            'labels' => $days->map(fn ($d) => $d->format('d M'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
