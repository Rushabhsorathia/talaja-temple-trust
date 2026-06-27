<?php

namespace App\Filament\Widgets;

use App\Models\Donation;
use App\Models\Order;
use App\Models\RoomBooking;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $today = now()->startOfDay();

        $donationsToday = Donation::where('status', 'success')->whereDate('paid_at', $today)->sum('amount');
        $donationsCount = Donation::where('status', 'success')->count();
        $bookingsToday = RoomBooking::whereDate('created_at', $today)->count();
        $ordersPending = Order::where('fulfilment_status', 'new')->count();
        $revenueMonth = Donation::where('status', 'success')->whereMonth('paid_at', now()->month)->sum('amount');

        return [
            Stat::make('Donations Today', '₹'.number_format((float) $donationsToday, 0))
                ->description($donationsCount.' total donations')
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart($this->dailyDonationSeries())
                ->color('success')->icon('heroicon-o-banknotes'),
            Stat::make('Bookings Today', $bookingsToday)
                ->description('New room bookings')
                ->descriptionIcon('heroicon-m-home-modern')
                ->chart($this->dailyBookingSeries())
                ->color('warning')->icon('heroicon-o-home-modern'),
            Stat::make('Orders Pending', $ordersPending)
                ->description('Awaiting fulfilment')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('danger')->icon('heroicon-o-shopping-bag'),
            Stat::make('Revenue This Month', '₹'.number_format((float) $revenueMonth, 0))
                ->description('From successful donations')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary')->icon('heroicon-o-arrow-trending-up'),
        ];
    }

    /** Last 7 days of successful donation totals, for the stat sparkline. */
    protected function dailyDonationSeries(): array
    {
        return collect(range(6, 0))
            ->map(fn ($d) => (float) Donation::where('status', 'success')
                ->whereDate('paid_at', Carbon::today()->subDays($d))->sum('amount'))
            ->all();
    }

    /** Last 7 days of room bookings, for the stat sparkline. */
    protected function dailyBookingSeries(): array
    {
        return collect(range(6, 0))
            ->map(fn ($d) => RoomBooking::whereDate('created_at', Carbon::today()->subDays($d))->count())
            ->all();
    }
}
