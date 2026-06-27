# Database ER Diagram — Talaja Temple Trust

```
USERS ──< DONATIONS >── DONATION_CATEGORIES
  │            │
  │            └──< DONATION_RECEIPTS
  │
  ├──< ROOM_BOOKINGS >── ROOMS >── ROOM_TYPES >── TEMPLES
  ├──< HALL_BOOKINGS >── MEETING_HALLS >── TEMPLES
  └──< ORDERS ──< ORDER_ITEMS >── PRODUCTS

TEMPLES ──< TEMPLE_TRANSLATIONS (en/gu)
        ──< TEMPLE_TIMINGS (darshan/aarti/pooja)
        ──< FESTIVALS
        ──< TRUSTEES

HOUSEKEEPING_LOGS >── ROOMS
RECEIPTS (polymorphic receiptable: donation/booking/order)
PAYMENTS, BANK_STATEMENTS (polymorphic reconcilable)

FEEDBACKS
NOTIFICATION_TEMPLATES ──< NOTIFICATION_LOGS (polymorphic notifiable)

CMS: CMS_PAGES, BANNERS, NEWS, GALLERIES, VIDEOS, PUBLICATIONS, FAQS
LIVE_DARSHAN_CONFIG, SETTINGS

Audit (spatie/activitylog): ACTIVITY_LOG
Authz (spatie/permission): ROLES, PERMISSIONS, MODEL_HAS_ROLES...
Media (spatie/medialibrary): MEDIA
```

## Key relationships
- `users 1—N donations` (donor_id), `donations 1—N donation_receipts`
- `donation_categories 1—N donations`
- `room_types 1—N rooms 1—N room_bookings`, `room_bookings N—1 users`
- `meeting_halls 1—N hall_bookings`
- `temple 1—N {timings, festivals, trustees, translations}`
- `receipts` morph to `donation|room_booking|order` (receiptable)
- `bank_statements` morph to reconcilable transactions
- `notification_logs` morph to `donation|user|order` (notifiable)

## Soft deletes & audit
- Soft-delete: `users`, `temples`, `donations`, `room_bookings`, `news`, `products`, `orders`
- All admin mutations logged via spatie/activitylog (`activity_log` table)

## Translation pattern
CMS models hold parallel `*_gu` columns; `localized('field')` accessor returns active-locale value with EN fallback.
