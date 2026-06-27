<?php

namespace App\Filament\Widgets;

use App\Models\Donation;
use App\Models\Order;
use App\Models\RoomBooking;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
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
                ->color('success')->icon('heroicon-o-banknotes'),
            Stat::make('Bookings Today', $bookingsToday)
                ->description('New room bookings')
                ->color('warning')->icon('heroicon-o-home-modern'),
            Stat::make('Orders Pending', $ordersPending)
                ->description('Awaiting fulfilment')
                ->color('danger')->icon('heroicon-o-shopping-bag'),
            Stat::make('Revenue This Month', '₹'.number_format((float) $revenueMonth, 0))
                ->description('From successful donations')
                ->color('primary')->icon('heroicon-o-arrow-trending-up'),
        ];
    }
}
