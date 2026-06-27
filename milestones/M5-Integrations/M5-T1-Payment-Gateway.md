# M5-T1 — Payment Gateway Integration (Razorpay)

- **Type:** Story
- **Priority:** Highest
- **Story Points:** 5
- **Milestone:** M5 – Integrations
- **Stack:** Laravel + Razorpay SDK

## User Story
> As a donor, I want to pay securely via UPI/card/netbanking.

## Acceptance Criteria
- [ ] Razorpay checkout for donations, bookings, shop.
- [ ] Webhook handler (verified signature) for status sync.
- [ ] Idempotent payment processing.
- [ ] Refund flow.
- [ ] Reconciliation export.
- [ ] Sandbox + production key switching via env.

## Definition of Done
Payment success/failure reliably reflected in system.
