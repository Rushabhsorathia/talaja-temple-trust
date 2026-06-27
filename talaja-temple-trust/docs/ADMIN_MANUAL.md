# Admin Manual — Talaja Temple Trust Portal

Access: **http://localhost:8001/admin** (production: https://talajatemple.org/admin)
Login: `admin@talajatemple.org` / `password` (change immediately).

## Dashboard
- KPI tiles: donations today, bookings, pending orders, monthly revenue.
- 14-day donation trend chart, recent donations table.

## Managing content (group: Content)
| Menu | What you can do |
|------|-----------------|
| News | Add/edit/publish news (EN+GU), set category, image, publish date, SEO. |
| CMS Pages | Static pages (slug-driven), rich content, SEO. |
| Banners | Hero/banner images with scheduling. |
| Gallery | Upload photos (category, alt text EN/GU). |
| Videos | YouTube/Vimeo embeds. |
| Publications | PDF downloads. |
| FAQs | Q&A grouped by category. |
| Trustees | Name, designation, photo, bio (EN/GU). |

## Donations (group: Donations)
- **Donations:** view all, edit status, mark 80G, re-issue receipts, manual offline entries.
- **Donation Categories:** toggle 80G eligibility, reorder.

## Accommodation (group: Accommodation)
- **Room Bookings:** open a booking → **Check In / Check Out / Cancel** header actions.
  - Checkout auto-sets room to "dirty" + logs housekeeping.
- **Rooms:** inventory, housekeeping status (clean/dirty/inspected).
- **Room Types:** tariff, capacity, amenities.
- **Housekeeping Logs:** status change trail.

## Finance (group: Finance)
- **Receipts / Payments:** record income & expenses.
- **Bank Statements:** import (CSV), match to transactions, set reconciliation status.

## Shop (group: Shop)
- **Orders:** update fulfilment status (new→packed→shipped→delivered), add tracking no.
- **Products:** price, stock, category.

## Communication (group: Communication)
- **Notification Templates:** define SMS/email/WhatsApp with `{variables}`.
- **Notification Logs:** delivery trail.
- **Feedback:** triage suggestions/complaints, set status, reply.

## Reports (group: Reports)
- **MIS Reports:** pick report type + date range → summary KPIs → **Export CSV**.

## Configuration (group: Configuration)
- **Temples, Timings, Festivals, Live Darshan (stream URL + live toggle), Settings.**

## User management
- Create users, assign roles (Super Admin/Trustee/Admin/Officer/Staff). Admin panel access restricted to admin/staff/trustee types.

## Tips
- Always fill EN + Gujarati fields for public content.
- Use the **Reissue/void** flow (not delete) for donation receipts to keep audit trail.
- After major content changes, the home page cache refreshes every 15 min (or run `php artisan cache:clear`).
