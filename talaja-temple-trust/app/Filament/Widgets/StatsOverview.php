<?php

namespace App\Filament\Widgets;

use App\Models\Donation;
use App\Models\Order;
use App\Models\RoomBooking;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $today = now()->startOfDay();
        $monthStart = now()->startOfMonth();

        $donationsToday = (float) Donation::where('status', 'success')->whereDate('paid_at', $today)->sum('amount');
        $revenueMonth = (float) Donation::where('status', 'success')->where('paid_at', '>=', $monthStart)->sum('amount');
        $activeBookings = RoomBooking::whereNotIn('status', ['cancelled', 'checked_out'])->count();
        $pendingOrders = Order::where('fulfilment_status', 'new')->count();

        return [
            Stat::make('Donations Today', '₹'.number_format($donationsToday, 0))
                ->description(Donation::where('status', 'success')->count().' total received')
                ->icon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('Revenue This Month', '₹'.number_format($revenueMonth, 0))
                ->description('Successful donations')
                ->icon('heroicon-o-arrow-trending-up')
                ->color('primary'),

            Stat::make('Active Bookings', $activeBookings)
                ->description('Rooms currently booked')
                ->icon('heroicon-o-home-modern')
                ->color('warning'),

            Stat::make('Pending Orders', $pendingOrders)
                ->description('Awaiting fulfilment')
                ->icon('heroicon-o-shopping-bag')
                ->color('danger'),
        ];
    }
}
