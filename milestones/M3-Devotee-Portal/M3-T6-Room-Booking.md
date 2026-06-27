# M3-T6 — Room & Meeting Hall Booking

- **Type:** Story
- **Priority:** Highest
- **Story Points:** 8
- **Milestone:** M3 – Devotee Portal
- **Stack:** Laravel + Vue

## User Story
> As a devotee, I want to book a room or meeting hall online.

## Acceptance Criteria
- [ ] Availability calendar (real-time, per room/hall type).
- [ ] Booking wizard: dates, guests, room type, add-ons.
- [ ] Price calculation (tariff rules, GST).
- [ ] Pay online or pay-at-temple option.
- [ ] Booking confirmation email+SMS with voucher.
- [ ] Cancellation/refund policy.
- [ ] Conflict prevention (double-booking guard).
- [ ] Housekeeping auto-flag on checkout.

## Tasks
- `rooms`, `room_bookings`, `meeting_halls`, `hall_bookings`.
- Vue calendar component.

## Definition of Done
End-to-end booking works with availability enforcement.
