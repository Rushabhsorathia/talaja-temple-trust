# Milestone 3 – Devotee Portal (Auth, Donations, Booking, Shop)

**Duration:** Weeks 3–4
**Stack:** Laravel 11 + Vue 3 (Inertia) + Razorpay sandbox
**Goal:** Logged-in devotee flows: registration, donations (with 80G), room/hall booking, shop cart.
**Exit Criteria:** End-to-end devotee journeys tested on sandbox payments.

| Ticket | Title | Story Points | Status |
|--------|-------|--------------|--------|
| [M3-T1](M3-T1-Auth-OTP.md) | Devotee register/login (OTP + password) | 5 | ✅ Done |
| [M3-T2](M3-T2-Profile.md) | Devotee profile & account | 3 | ✅ Done |
| [M3-T3](M3-T3-Donate-Flow.md) | Online donation flow (general + slabs) | 8 | ✅ Done |
| [M3-T4](M3-T4-80G-Receipt.md) | 80G receipt PDF generation | 5 | ✅ Done |
| [M3-T5](M3-T5-QR-Donation.md) | QR-based UPI donation | 3 | ✅ Done |
| [M3-T6](M3-T6-Room-Booking.md) | Room & meeting hall booking | 8 | ✅ Done |
| [M3-T7](M3-T7-My-Donations-Bookings.md) | My Donations & My Bookings | 3 | ✅ Done |
| [M3-T8](M3-T8-Shop-Cart-Checkout.md) | Shop: cart, checkout, order history | 8 | ✅ Done |

## Build status
All 8 tickets complete in `../talaja-temple-trust/`. OTP auth, donations with Razorpay (dev fallback), 80G PDF receipts, UPI QR, room/hall booking with availability, session cart + checkout, order history. Payments auto-simulate when `RAZORPAY_KEY`/`RAZORPAY_SECRET` are unset.
