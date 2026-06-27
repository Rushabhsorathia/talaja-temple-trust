<?php

namespace App\Http\Controllers;

use App\Models\MeetingHall;
use App\Models\Room;
use App\Models\RoomBooking;
use App\Models\HallBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class BookingController extends Controller
{
    public function index()
    {
        $rooms = Room::with('type')->active()->get()->groupBy('room_type_id')->map(function ($group) {
            $type = $group->first()->type;

            return [
                'id' => $type->id,
                'name' => $type->localized('name'),
                'tariff' => $type->tariff,
                'capacity' => $type->capacity,
                'amenities' => $type->amenities,
                'available_count' => $group->count(),
            ];
        })->values();

        $halls = MeetingHall::active()->get()->map(fn ($h) => [
            'id' => $h->id,
            'name' => $h->localized('name'),
            'capacity' => $h->capacity,
            'tariff' => $h->tariff,
        ]);

        return Inertia::render('Bookings/Index', ['roomTypes' => $rooms, 'halls' => $halls]);
    }

    public function availability(Request $request)
    {
        $request->validate([
            'check_in' => ['required', 'date'],
            'check_out' => ['required', 'date', 'after:check_in'],
        ]);

        $checkIn = $request->check_in;
        $checkOut = $request->check_out;

        $available = Room::with('type')->active()->get()
            ->filter(fn ($room) => $room->isAvailable($checkIn, $checkOut))
            ->groupBy('room_type_id')
            ->map(fn ($group) => [
                'type_id' => $group->first()->room_type_id,
                'type_name' => $group->first()->type?->localized('name'),
                'tariff' => $group->first()->type?->tariff,
                'nights' => Carbon::parse($checkIn)->diffInDays(Carbon::parse($checkOut)),
                'available_count' => $group->count(),
            ])->values();

        return response()->json($available);
    }

    public function storeRoom(Request $request)
    {
        $validated = $request->validate([
            'room_type_id' => ['required', 'exists:room_types,id'],
            'check_in' => ['required', 'date'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'guests' => ['required', 'integer', 'min:1'],
            'guest_name' => ['required', 'string', 'max:120'],
            'guest_email' => ['nullable', 'email'],
            'guest_mobile' => ['required', 'string', 'max:20'],
            'payment_mode' => ['required', 'in:online,pay_at_temple'],
            'note' => ['nullable', 'string'],
        ]);

        // Find first available room of that type.
        $room = Room::where('room_type_id', $validated['room_type_id'])->active()->get()
            ->first(fn ($r) => $r->isAvailable($validated['check_in'], $validated['check_out']));

        if (! $room) {
            return back()->withErrors(['room_type_id' => 'No rooms available for these dates.']);
        }

        $nights = Carbon::parse($validated['check_in'])->diffInDays(Carbon::parse($validated['check_out']));
        $amount = $room->type->tariff * $nights;

        $booking = RoomBooking::create([
            'booking_no' => 'RB-'.strtoupper(uniqid()),
            'room_id' => $room->id,
            'user_id' => $request->user()?->id,
            'guest_name' => $validated['guest_name'],
            'guest_email' => $validated['guest_email'] ?? null,
            'guest_mobile' => $validated['guest_mobile'],
            'guests' => $validated['guests'],
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'amount' => $amount,
            'payment_mode' => $validated['payment_mode'],
            'status' => $validated['payment_mode'] === 'pay_at_temple' ? 'confirmed' : 'pending',
            'note' => $validated['note'] ?? null,
        ]);

        // TODO: integrate Razorpay for online mode (M5-T1). For now confirm directly.
        if ($validated['payment_mode'] === 'online') {
            $booking->update(['status' => 'confirmed']);
        }

        return redirect()->route('bookings.my')->with('success', 'Booking confirmed! '.$booking->booking_no);
    }

    public function storeHall(Request $request)
    {
        $validated = $request->validate([
            'meeting_hall_id' => ['required', 'exists:meeting_halls,id'],
            'event_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'attendees' => ['required', 'integer', 'min:1'],
            'guest_name' => ['required', 'string', 'max:120'],
            'guest_mobile' => ['required', 'string', 'max:20'],
            'note' => ['nullable', 'string'],
        ]);

        $hall = MeetingHall::findOrFail($validated['meeting_hall_id']);

        $booking = HallBooking::create([
            'booking_no' => 'HB-'.strtoupper(uniqid()),
            'meeting_hall_id' => $hall->id,
            'user_id' => $request->user()?->id,
            'guest_name' => $validated['guest_name'],
            'guest_mobile' => $validated['guest_mobile'],
            'event_date' => $validated['event_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'attendees' => $validated['attendees'],
            'amount' => $hall->tariff,
            'status' => 'confirmed',
            'note' => $validated['note'] ?? null,
        ]);

        return redirect()->route('bookings.my')->with('success', 'Hall booked! '.$booking->booking_no);
    }

    public function my(Request $request)
    {
        $user = $request->user();

        $roomBookings = RoomBooking::with('room.type')->where('user_id', $user->id)
            ->orderByDesc('id')->get()->map(fn ($b) => [
                'booking_no' => $b->booking_no,
                'type' => 'Room',
                'title' => $b->room?->type?->localized('name'),
                'check_in' => $b->check_in?->format('d-m-Y'),
                'check_out' => $b->check_out?->format('d-m-Y'),
                'amount' => $b->amount,
                'status' => $b->status,
            ]);

        $hallBookings = HallBooking::with('hall')->where('user_id', $user->id)
            ->orderByDesc('id')->get()->map(fn ($b) => [
                'booking_no' => $b->booking_no,
                'type' => 'Hall',
                'title' => $b->hall?->localized('name'),
                'check_in' => $b->event_date?->format('d-m-Y'),
                'amount' => $b->amount,
                'status' => $b->status,
            ]);

        return Inertia::render('Bookings/My', ['bookings' => $roomBookings->merge($hallBookings) ]);
    }
}
