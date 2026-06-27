# Project Plan: Talaja Temple Trust Management Web Portal

**Reference:** https://www.khodiyarmandirtrust.com/
**Source Spec:** `Talaja Temple Features.docx` (Annexure – List of Indicative Features of the Proposed Temple Trust Management Web Portal)

---

## 1. Executive Summary

Build a secure, scalable, responsive, web-based **Temple Trust Management Web Portal** for Talaja Temple Trust. The portal combines:

1. A **public-facing website** (similar in look & feel to khodiyarmandirtrust.com) for devotees.
2. A **back-office admin panel** for trustees, officers and staff to run operations.
3. An **API/integration layer** for payments, SMS, email, QR and YouTube Live.

The deliverable is a multilingual (English + Gujarati) portal that handles donations, accommodation bookings, financial management, content, live darshan, audits, reports and integrations.

---

## 2. Goals & Non-Functional Requirements

| Area | Requirement |
|------|-------------|
| **Security** | Role-based access, MFA/OTP, password policy, session management, login monitoring |
| **Scalability** | Horizontal scaling, multi-temple ready (talaja first, expandable to sister temples) |
| **Performance** | Page load < 3s on 3G, Lighthouse score > 85 |
| **Responsive** | Mobile / tablet / desktop browsers, WCAG 2.1 AA accessibility |
| **Multilingual** | English + Gujarati (i18n via Laravel / Next.js i18n) |
| **Audit** | User activity, transaction, configuration audit with exportable reports |
| **Backup** | Daily automated DB + media backup (offsite + local) |
| **Compliance** | 80G receipt generation, IT Act & DPDP compliance |

---

## 3. Site Map (mirroring reference site + features)

### 3.1 Public Portal
- Home (hero, live darshan teaser, advisory, about, services, news, facilities, footer)
- About Us
  - The Trust
  - History
  - Trustees
- Temple Info
  - Temple Profile
  - Timings (Darshan, Aarti, Pooja schedule)
  - Festival Calendar
  - Location & Maps
- Facilities & Offerings
- News & Updates
- Live Darshan (YouTube Live embed)
- Gallery (Photo / Video)
- Shop (public listing of trust publications/souvenirs; cart + checkout)
- Donate (General / 80G / QR / Bank transfer)
- Contact Us
- Search (global, advanced, filtered)
- FAQs
- Downloads / Publications
- Suggestion & Feedback
- Multilingual switcher (EN / GU)

### 3.2 Devotee Self-Service
- Register / Login (OTP)
- My Donations (history + receipt download)
- My Bookings (rooms / meeting hall)
- My Profile

### 3.3 Admin / Staff Portal
- Dashboard & Analytics (role-personalised)
- Temple Information Management
- Donation Management
- Accommodation Management (rooms, hall, check-in/out, housekeeping)
- Financial Management (receipts, payments, bank reconciliation)
- Suggestion & Feedback
- Notice & Communications (SMS/Email/WhatsApp)
- Content Management (CMS) – homepage, banners, news, gallery, videos, publications, downloads, FAQs, SEO
- User & Security Management
- Audit Trail
- Reports & MIS
- Integrations Configuration
- Backup & Restore
- Live Darshan Configuration

---

## 4. Feature Breakdown (mapped from spec)

### 4.1 Dashboard & Analytics
- Personalised dashboards per role (Trustee / Admin / Officer / Staff).
- Daily summary tiles: donations, visitors, bookings, events, financials.
- Temple-wise KPIs.
- Revenue dashboards with drill-down.
- Graphical MIS + KPI charts (Chart.js / ApexCharts / Recharts).

### 4.2 Temple Profile & Information Management
- CRUD for: profile, history, trust info, timings, darshan, aarti, pooja schedule, festival calendar, contact, location, gallery, announcements, news & circulars.
- Multilingual fields.

### 4.3 Online Donation Management
- General donation (any amount / predefined slabs).
- 80G receipt (auto PDF) when checkbox enabled.
- QR-based donation (UPI dynamic QR per donor amount).
- Online payment integration (Razorpay / Stripe / PayU).
- Receipt auto-email + SMS + WhatsApp.
- Donation history & receipt download for donors.

### 4.4 Accommodation Management
- Room & Meeting Hall inventory (CRUD).
- Availability calendar.
- Online booking with online payment or pay-at-temple.
- Check-in / Check-out workflow.
- Housekeeping status (dirty / clean / inspected).
- Occupancy reports.

### 4.5 Financial Management
- Receipt management (auto + manual).
- Payment management (vendors, salaries, misc).
- Bank reconciliation (CSV import + manual match).
- Day book, ledger, P&L, balance sheet views.

### 4.6 Suggestion & Feedback Management
- Public submission form (anon or with user).
- Admin triage (open / in-progress / closed / spam).
- Reply via email/SMS.

### 4.7 Notice & Communication Management
- SMS gateway (MSG91 / Twilio).
- Email (SMTP / SendGrid / SES).
- WhatsApp Business API.
- Templated messages + bulk send + schedule.

### 4.8 Search Facility
- Basic / Advanced / Global search.
- Multi-parameter filter (date, type, status).
- Export to CSV / Excel / PDF.

### 4.9 User & Security Management
- Role-based access (RBAC): Super Admin, Trustee, Admin, Officer, Staff, Devotee.
- User CRUD, password policy (length, expiry, history).
- MFA/OTP via email/SMS.
- Login monitoring (success/fail, IP, device).
- Session management (timeout, concurrent sessions).

### 4.10 Audit Trail
- User activity log (page, action, IP, timestamp).
- Transaction audit (immutable log table).
- Configuration audit (who changed what).
- Export to PDF/CSV.

### 4.11 Reports & MIS
- Donation reports (daily, period, donor-wise, category-wise).
- Daily collection report.
- Booking reports (room, hall, occupancy).
- Financial reports (receipts, payments, reconciliation).
- Audit reports.
- Scheduled email reports.

### 4.12 Integration with External Systems
- Payment Gateway (Razorpay primary).
- SMS Gateway (MSG91 / Twilio).
- Email (SMTP / SendGrid).
- QR Code services (dynamic UPI).

### 4.13 Content Management System (CMS)
- Homepage builder (banners, sections).
- Banner management with scheduling.
- News / circulars with publish/unpublish.
- Photo & video gallery.
- Publications & downloads (PDF).
- FAQs.
- Contact pages.
- SEO (meta title, description, OG, sitemap.xml, robots.txt).

### 4.14 Mobile Responsive Portal
- Responsive UI (Tailwind CSS / Bootstrap 5).
- Mobile browser + tablet tested.
- Accessibility (WCAG 2.1 AA).
- Multilingual (EN/GU).

### 4.15 API & Integration Framework
- REST APIs (Laravel API / Node Express).
- JWT / Sanctum auth.
- Third-party integration endpoints.
- Webhooks (payment status, etc.).

### 4.16 Backup
- Daily DB dump + media sync to S3 / local NAS.
- Retention 30 daily, 12 monthly.
- Restore drill monthly.

### 4.17 Live Darshan Streaming
- YouTube Live embed (responsive iframe).
- Admin config for stream URL + schedule.
- "LIVE" badge when stream active.

---

## 5. Proposed Architecture

### 5.1 Tech Stack Options

**Option A – Laravel + Blade (Recommended for fast delivery)**
- Backend: Laravel 11 (PHP 8.3)
- DB: MySQL 8
- Frontend: Blade + Tailwind CSS + Alpine.js + Livewire
- Admin: Filament PHP (rapid admin panel)
- Queue: Redis + Horizon
- Storage: S3-compatible (AWS S3 / DigitalOcean Spaces)

**Option B – Next.js + Node API (headless)**
- Frontend: Next.js 14 (App Router, RSC)
- Backend: Node.js (Express/NestJS) or Laravel API
- DB: PostgreSQL
- Admin: Refine / React Admin
- Cache: Redis

**Option C – WordPress + Plugins (fastest, less custom)**
- WP core + ACF + WooCommerce (donations/shop) + WPML (multilingual) + custom plugins for bookings/finance.

> **Recommendation:** Option A (Laravel + Filament) — best balance of speed, cost, maintainability and feature coverage for a temple trust.

### 5.2 High-Level Architecture
```
            ┌──────────────────────┐
            │   Cloudflare CDN     │
            └──────────┬───────────┘
                       │
            ┌──────────▼───────────┐
            │  Web Server (Nginx)  │
            └──┬───────────────┬───┘
               │               │
       ┌───────▼─────┐   ┌─────▼────────┐
       │ Public Site │   │ Admin Portal │
       └───────┬─────┘   └─────┬────────┘
               │               │
            ┌──▼───────────────▼──┐
            │  Laravel Application │
            └──┬────┬────┬────┬───┘
               │    │    │    │
        ┌──────▼┐ ┌─▼─┐ ┌▼──┐ ┌▼─────────┐
        │ MySQL │ │Redis│ │S3 │ │ External │
        │       │ │Queue│ │   │ │ APIs     │
        └────────┘ └────┘ └───┘ │ (Rzp/SMS/│
                                 │  Email)  │
                                 └──────────┘
```

---

## 6. Database Schema (high level)

Core tables:
- `users`, `roles`, `permissions`, `role_user`, `permission_role`
- `temples`, `temple_translations`, `timings`, `aartis`, `poojas`, `festivals`
- `donations`, `donation_receipts`, `donation_categories`
- `rooms`, `room_types`, `room_bookings`, `meeting_halls`, `hall_bookings`
- `housekeeping_logs`
- `receipts`, `payments`, `bank_statements`, `reconciliations`
- `feedbacks`, `suggestions`
- `notifications`, `sms_logs`, `email_logs`, `whatsapp_logs`
- `cms_pages`, `banners`, `news`, `galleries`, `videos`, `publications`, `faqs`
- `audit_logs`, `login_audits`, `config_audits`
- `products` (shop), `orders`, `order_items`, `carts`
- `live_darshan_config`
- `backups`

---

## 7. UI/UX Direction (mirroring reference)

- **Color palette:** saffron / deep red / cream / gold accents (traditional temple feel).
- **Typography:** Elegant serif (e.g. Cinzel / Tiro Devanagari Gujarati) for headings; clean sans-serif (Inter / Noto Sans) for body.
- **Layout:**
  - Top utility bar (language switcher EN/GU, contact, social).
  - Header with logo + nav + Donate button.
  - Hero slider / video background with bell + mute toggle (like reference).
  - Advisory banner (modal + inline).
  - "About the Trust" section.
  - "Online Services & Offerings" cards (Live Darshan, Donate, Booking, Shop).
  - News & Updates grid.
  - Facilities & Offerings grid (Dharamshala, Vishram Gruh, Annashetra, Havan Khand, etc.).
  - Quick CTA cards (Visit Temple, Gallery, History, Trustees).
  - Footer with info, reach-us, social.
- **Donate modal:** bank details + QR scan.
- **Live badge** + responsive iframe for darshan.
- **Mobile-first** responsive design.

---

## 8. Security & Compliance

- HTTPS everywhere (Let's Encrypt / Cloudflare).
- HSTS, CSP, X-Frame-Options, X-Content-Type-Options.
- CSRF protection (Laravel default).
- Input sanitisation, SQL injection guard (Eloquent).
- Password hashing (bcrypt/argon2).
- MFA via TOTP / OTP for admin & donors.
- WAF (Cloudflare).
- Rate limiting on public endpoints (donate, login, OTP).
- Daily encrypted backups; quarterly DR drill.
- Privacy policy + consent for cookies, donations.

---

## 9. Integrations

| Purpose | Provider (suggested) |
|---------|----------------------|
| Payments | Razorpay (UPI / Card / Netbanking) |
| 80G receipt | In-house PDF + email |
| SMS | MSG91 / Twilio |
| Email | SendGrid / Amazon SES |
| WhatsApp | WhatsApp Business API (via Wati / Interakt) |
| QR | Dynamic UPI QR via Razorpay |
| Live Darshan | YouTube Live |
| Maps | Google Maps embed |
| Analytics | Google Analytics 4 + Plausible |
| Translation | In-house EN/GU DB columns |

---

## 10. Project Phases & Timeline (12–14 weeks)

| Phase | Weeks | Deliverables |
|-------|-------|--------------|
| **P0 – Discovery & Design** | 1 | Requirements sign-off, wireframes, brand guide, DB design |
| **P1 – Public Site (CMS)** | 2–3 | Home, About, History, Trustees, News, Gallery, Contact, Multilingual, SEO |
| **P2 – Devotee Portal** | 3–4 | Auth (OTP), Profile, Donations (with 80G), Bookings, Shop |
| **P3 – Admin Panel (Filament)** | 5–7 | All CRUD modules, dashboards, CMS, audit, user & security |
| **P4 – Integrations** | 7–8 | Payments, SMS, Email, WhatsApp, QR, YouTube Live |
| **P5 – Reports & MIS** | 9 | Donation, Booking, Financial, Audit reports, scheduled email |
| **P6 – QA & Hardening** | 10 | Accessibility, security audit, performance, browser matrix |
| **P7 – UAT & Training** | 11 | Trustee/admin training, UAT fixes |
| **P8 – Go-Live & Handover** | 12 | Production deploy, DNS, SSL, monitoring, handover docs |

---

## 11. Team & Roles

- 1 Project Manager
- 1 UI/UX Designer
- 2 Full-Stack Developers (Laravel + frontend)
- 1 QA Engineer
- 1 DevOps (part-time)
- 1 Content / Gujarati translator

---

## 12. Cost Estimate (Indicative, INR)

| Item | Cost (₹) |
|------|----------|
| Design (UI/UX + brand) | 80,000 – 1,20,000 |
| Development (Laravel + Filament) | 4,50,000 – 6,50,000 |
| Integrations (Razorpay/SMS/Email/WhatsApp) | 50,000 – 1,00,000 |
| QA + Security audit | 50,000 – 80,000 |
| Hosting + Domain + SSL (year 1) | 25,000 – 50,000 |
| Maintenance (annual, post go-live) | 15% of dev cost |
| **Total (one-time)** | **~6.5 L – 10 L** |

(Timeline can compress or expand cost accordingly.)

---

## 13. Risks & Mitigations

| Risk | Mitigation |
|------|-----------|
| Scope creep | Strict change-control; phase-locked feature freeze |
| Payment compliance | Use PCI-DSS compliant gateway (Razorpay); never store card data |
| Multilingual content delay | Translate in parallel with development; placeholder strategy |
| Donor data privacy | Encryption at rest, role-restricted access, retention policy |
| Volunteer turnover | Code documentation + Loom walkthroughs |
| Live stream outage | YouTube Live + status check + fallback poster |

---

## 14. Acceptance Criteria (Go-Live)

- All 17 feature groups in the annexure functional.
- Lighthouse Performance ≥ 85, Accessibility ≥ 95.
- WCAG 2.1 AA audit pass.
- Successful UAT by 3 trustees.
- 1 month of parallel dry-run with old system.
- Disaster recovery drill successful.
- Documentation (admin manual, user manual, API docs) delivered.

---

## 15. Post-Launch

- 3 months hyper-care support.
- Monthly security + backup review.
- Quarterly feature enhancements (review).
- Annual penetration test.

---

## 16. Open Questions (to confirm with Trust)

1. Single-temple or multi-temple from day one?
2. Preferred payment gateway (Razorpay / others)?
3. WhatsApp Business API approved already?
4. Existing domain & hosting? Or procure new?
5. Existing donor database to migrate?
6. Bank account for reconciliation – number of accounts?
7. Languages required at launch: English + Gujarati only?
8. Branding assets (logo, photos, videos) – in-house or to be created?
9. Hardware for office (admin) – laptops, internet?
10. SOPs / approval workflows needed (e.g. >₹X donation needs trustee approval)?

---

## 17. Next Steps

1. Stakeholder review of this plan.
2. Confirm scope, timeline & budget.
3. Sign-off on tech stack (Laravel recommended).
4. Provision repos, hosting, domains, payment sandbox accounts.
5. Begin Phase 0 – wireframes & design system.
