<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Order;
use App\Models\RoomBooking;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        // Admin / staff / trustee users go straight to the admin panel.
        if (in_array($user->type, ['admin', 'staff', 'trustee'])) {
            return redirect('/admin');
        }

        // Devotee account dashboard.
        $donations = Donation::where('donor_id', $user->id)->where('status', 'success');
        $bookings = RoomBooking::where('user_id', $user->id);
        $orders = Order::where('user_id', $user->id);

        $recentDonations = Donation::with('category')->where('donor_id', $user->id)
            ->latest()->limit(5)->get()->map(fn ($d) => [
                'receipt_no' => $d->receipt_no,
                'amount' => $d->amount,
                'category' => $d->category?->name,
                'paid_at' => $d->paid_at?->format('d-m-Y'),
                'status' => $d->status,
            ]);

        return Inertia::render('Dashboard', [
            'stats' => [
                'totalDonated' => (float) $donations->sum('amount'),
                'donationCount' => $donations->count(),
                'bookings' => $bookings->count(),
                'activeBookings' => $bookings->whereNotIn('status', ['cancelled', 'checked_out'])->count(),
                'orders' => $orders->count(),
            ],
            'recentDonations' => $recentDonations,
        ]);
    }
}
