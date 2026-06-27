# Milestone 6 – Reports & MIS

**Duration:** Week 9
**Stack:** Laravel + Filament + ApexCharts + PDF/Excel export
**Goal:** Comprehensive reports for trustees, finance, audit.
**Exit Criteria:** All reports generate accurately with export options + scheduled email.

| Ticket | Title | Story Points |
|--------|-------|--------------|
| [M6-T1](M6-T1-Donation-Reports.md) | Donation reports (daily/period/donor/category) | 5 |
| [M6-T2](M6-T2-Daily-Collection.md) | Daily collection report | 3 |
| [M6-T3](M6-T3-Booking-Reports.md) | Booking & occupancy reports | 3 |
| [M6-T4](M6-T4-Financial-Reports.md) | Financial reports (receipts/payments/recon) | 5 |
| [M6-T5](M6-T5-Audit-Reports.md) | Audit reports + export | 3 |
| [M6-T6](M6-T6-Scheduled-Email.md) | Scheduled email reports | 3 |
| [M6-T7](M6-T7-Export-Framework.md) | Unified export (CSV/Excel/PDF) | 3 |

## Build status (✅ implemented)
- Single **MIS Reports** Filament page (`App\Filament\Pages\Reports`) covering T1–T5: donation, daily collection, booking/occupancy, financial (receipts/payments), reconciliation, shop — with date-range selector, summary KPIs, and **CSV export** per report type.
- Scheduled email reports + unified export framework can layer on the existing CSV export.
