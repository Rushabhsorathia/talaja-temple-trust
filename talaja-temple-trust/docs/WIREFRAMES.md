# Wireframe & Page Specs — Talaja Temple Trust

ASCII wireframes per breakpoint (mobile-first). Each section maps to a Vue page under `resources/js/Pages/`.

## Global Layout (`AppLayout.vue`)
```
┌─────────────────────────────────────────────┐
│ utility bar: phone · email │ EN | ગુ         │ maroon-900
├─────────────────────────────────────────────┤
│ [logo]  Name        Home About Facilities    │ sticky
│         tagline      Gallery News LIVE Donate│
├─────────────────────────────────────────────┤
│                                             │
│                 <slot />                    │
│                                             │
├─────────────────────────────────────────────┤
│ Reach Us │ Information │ Get Connected       │ maroon-950
│ © year                                       │
└─────────────────────────────────────────────┘
Mobile: hamburger drawer replaces nav.
```

## Home (`Home.vue`)
1. Hero (70vh): gradient bg, `Jay Mataji`, title, tagline, [Donate][Live Darshan].
2. About teaser (2-col): text + 2 image tiles, [Read More].
3. Services (4-col grid): Live Darshan / Donate / Bookings / Shop cards.
4. News (3-col): latest 6, [View All].
5. Trustees (4-col): photo + name + designation on maroon band.

## About / History / Trustees
- Page hero (gradient) → centered max-w-3px rich content.
- Trustees: 3-col grid of circular photo + designation + bio.

## Temple Info (`TempleInfo.vue`)
- Hero.
- Timings: 3 cards grouped by type (darshan/aarti/pooja), table inside.
- Festivals: 3-col cards (title, date range, desc).
- Map: full-width iframe.

## Photo Gallery (`PhotoGallery.vue`)
- Category chips (All + each category).
- 4-col masonry grid (square tiles), lightbox on click.
- Pagination.

## Video Gallery (`VideoGallery.vue`)
- 3-col responsive 16:9 iframes.

## News list (`News/Index.vue`) + detail (`News/Show.vue`)
- List: category chips → 3-col cards (image, date, title, excerpt), pagination.
- Detail: full-bleed hero image with title overlay → prose content → related 3-col.

## Donate (`Donate/Index.vue` → `Checkout.vue` → `Success.vue`)
- Amount slabs (chips) + custom input.
- Category select, 80G toggle (reveals PAN), donor fields, anonymous.
- → Checkout: Razorpay modal (or dev spinner) → verify → Success (✓ + receipt download).

## Bookings (`Bookings/Index.vue`)
- Tabs: Rooms | Meeting Halls.
- Rooms: availability checker (check-in/out) → booking form (type, dates, guests, payment mode).
- Halls: single form (hall, date, time, attendees).

## Shop (`Shop/Index.vue` → `Cart.vue` → `Orders.vue`)
- 4-col product cards (Add to Cart).
- Cart: items + checkout form (name/mobile/address) → total → Place Order.
- Orders: list with line items, payment/fulfilment status.

## Contact (`Contact.vue`)
- 2-col: reach-us (address, phone, email, map) + feedback form (type/name/email/mobile/message).

## Auth (`Auth/OtpLogin.vue`)
- Mobile input → [Send OTP] → name + OTP → [Verify & Login].

## Responsive breakpoints
- `sm` 640, `md` 768, `lg` 1024, `xl` 1280. Max content width `max-w-7xl` (1280px).
