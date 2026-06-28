<?php

namespace App\Filament\Widgets;

use App\Models\Donation;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class DonationChart extends ChartWidget
{
    protected static ?string $heading = 'Donations — last 30 days';

    protected static ?string $maxHeight = '280px';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $days = collect(range(29, 0))->map(fn ($d) => Carbon::today()->subDays($d));

        $amounts = $days->map(fn ($d) => (float) Donation::where('status', 'success')
            ->whereDate('paid_at', $d)->sum('amount'))->all();

        $counts = $days->map(fn ($d) => Donation::where('status', 'success')
            ->whereDate('paid_at', $d)->count())->all();

        return [
            'datasets' => [
                [
                    'label' => 'Amount (₹)',
                    'data' => $amounts,
                    'borderColor' => '#f06106',
                    'backgroundColor' => 'rgba(240, 97, 6, 0.08)',
                    'fill' => true,
                    'tension' => 0.35,
                    'yAxisID' => 'y',
                ],
                [
                    'label' => 'Count',
                    'data' => $counts,
                    'borderColor' => '#94a3b8',
                    'backgroundColor' => 'rgba(148, 163, 184, 0.1)',
                    'fill' => false,
                    'tension' => 0.35,
                    'borderDash' => [4, 4],
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => $days->map(fn ($d) => $d->format('d M'))->all(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => ['position' => 'left', 'beginAtZero' => true],
                'y1' => ['position' => 'right', 'beginAtZero' => true, 'grid' => ['display' => false]],
            ],
            'plugins' => [
                'legend' => ['display' => true, 'position' => 'bottom'],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
