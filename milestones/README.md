# Talaja Temple Trust Portal — Jira Milestones & Tickets

**Stack:** Laravel 11 (PHP 8.3) + Vue 3 (Inertia) + Tailwind CSS + Filament PHP admin
**DB:** MySQL 8 + Redis | **Storage:** S3 | **Payments:** Razorpay
**Total Duration:** ~12 weeks

## Milestones

| # | Milestone | Weeks | Tickets | Total SP |
|---|-----------|-------|---------|----------|
| M1 | [Discovery & Design](M1-Discovery-Design/README.md) | 1 | 7 | 32 |
| M2 | [Public CMS Site](M2-Public-CMS-Site/README.md) | 2–3 | 12 | 59 |
| M3 | [Devotee Portal](M3-Devotee-Portal/README.md) | 3–4 | 8 | 43 |
| M4 | [Admin Panel](M4-Admin-Panel/README.md) | 5–7 | 12 | 64 |
| M5 | [Integrations & API](M5-Integrations/README.md) | 7–8 | 7 | 28 |
| M6 | [Reports & MIS](M6-Reports-MIS/README.md) | 9 | 7 | 25 |
| M7 | [QA, Hardening & Backup](M7-QA-Hardening/README.md) | 10 | 7 | 34 |
| M8 | [UAT & Go-Live](M8-UAT-GoLive/README.md) | 11–12 | 7 | 26 |
| | **TOTAL** | | **67 tickets** | **~311 SP** |

## How to use
- Each `M#/README.md` lists that milestone's tickets as a table with story points.
- Each ticket `.md` uses standard Jira story format: User Story, Acceptance Criteria, Tasks, Definition of Done.
- Import into Jira by creating Epics = Milestones, Stories = ticket files, mapping fields directly.

## Stack Summary
- **Backend:** Laravel 11, Filament PHP (admin), Sanctum (API auth), Spatie permission/media/activitylog.
- **Frontend:** Vue 3 via Inertia, Pinia, Tailwind, ApexCharts.
- **Integrations:** Razorpay, MSG91/Twilio SMS, SendGrid/SES email, WhatsApp Cloud API, YouTube Live, dynamic UPI QR.
- **Infra:** Cloudflare, Nginx, Redis, S3, GitHub Actions CI/CD, Sentry, Horizon.
