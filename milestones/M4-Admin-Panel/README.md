# Milestone 4 – Admin Panel (Filament) & Operations

**Duration:** Weeks 5–7
**Stack:** Laravel 11 + Filament PHP + Vue (where custom)
**Goal:** Full back-office for trustees/admin/staff to run temple operations.
**Exit Criteria:** All operational modules live; role-based access enforced; audit trail active.

| Ticket | Title | Story Points |
|--------|-------|--------------|
| [M4-T1](M4-T1-Admin-Auth-RBAC.md) | Admin auth, roles, permissions, MFA | 5 |
| [M4-T2](M4-T2-Dashboard-Analytics.md) | Role-based dashboard & analytics | 8 |
| [M4-T3](M4-T3-Donation-Admin.md) | Donation management & reconciliation | 5 |
| [M4-T4](M4-T4-Accommodation-Admin.md) | Accommodation ops (check-in/out, housekeeping) | 8 |
| [M4-T5](M4-T5-Financial-Management.md) | Receipts, payments, bank reconciliation | 8 |
| [M4-T6](M4-T6-CMS-Admin.md) | CMS admin (all content, banners, media) | 5 |
| [M4-T7](M4-T7-Feedback-Triage.md) | Suggestion/feedback triage | 3 |
| [M4-T8](M4-T8-Communications.md) | Notice/SMS/Email/WhatsApp manager | 5 |
| [M4-T9](M4-T9-Live-Darshan-Config.md) | Live darshan admin config | 2 |
| [M4-T10](M4-T10-Audit-Trail.md) | Audit trail (activity, transaction, config) | 5 |
| [M4-T11](M4-T11-User-Security-Mgmt.md) | User & security management | 5 |
| [M4-T12](M4-T12-Shop-Admin.md) | Shop/order fulfilment admin | 5 |

## Build status (✅ implemented in `../talaja-temple-trust/`)
- **T1 Auth/RBAC:** Filament panel + spatie/laravel-permission (Super Admin/Trustee/Admin/Officer/Staff/Devotee roles), seeded permissions; admin login gated by `User::canAccessPanel()`.
- **T2 Dashboard:** `StatsOverview`, `DonationChart` (14-day), `RecentDonations` widgets.
- **T3 Donations:** `DonationResource` (CRUD + status/80G gateway reconcile).
- **T4 Accommodation:** `RoomBookingResource` w/ Check-in / Check-out / Cancel actions (auto housekeeping), `RoomResource`, `HousekeepingLogResource`.
- **T5 Finance:** `ReceiptResource`, `PaymentResource`, `BankStatementResource` (reconciliation status).
- **T6 CMS:** already done in M2.
- **T7 Feedback:** `FeedbackResource` triage.
- **T8 Communications:** `NotificationTemplateResource` + `NotificationLogResource` + `NotificationService` (SMS/email/WhatsApp).
- **T9 Live Darshan:** `LiveDarshanConfigResource`.
- **T10 Audit:** spatie/activitylog wired on User.
- **T11 Users:** Filament default + role assignment (via spatie).
- **T12 Shop:** `OrderResource` fulfilment + `ProductResource`.
