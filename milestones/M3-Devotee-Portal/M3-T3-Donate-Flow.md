# M3-T3 — Online Donation Flow (General + Slabs)

- **Type:** Story
- **Priority:** Highest
- **Story Points:** 8
- **Milestone:** M3 – Devotee Portal
- **Stack:** Laravel + Vue + Razorpay

## User Story
> As a devotee, I want to make an online donation (custom amount or preset slab) securely.

## Acceptance Criteria
- [ ] Donation categories (general, anna, nitya pooja, etc.).
- [ ] Preset slabs + custom amount.
- [ ] One-time & recurring options.
- [ ] Razorpay checkout (UPI/card/netbanking).
- [ ] Guest donation allowed (no login required).
- [ ] 80G eligibility toggle (links to M3-T4).
- [ ] On success: store record, generate receipt, email+SMS+WhatsApp.
- [ ] Failure handling & retry.

## Tasks
- `donations` table, `DonationController`, Razorpay webhook handler.
- Vue `Donate.vue` wizard.

## Definition of Done
Successful sandbox donation produces record + receipt + notifications.
