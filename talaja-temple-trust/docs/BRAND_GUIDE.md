# Brand Identity & Design System — Talaja Temple Trust

## 1. Logo
- **Primary mark:** Om/trishul motif within a circular mandala, saffron gradient.
- **Treatment:** Circular avatar (96×96 header, 48×48 footer).
- **Favicon:** simplified 32×32 trishul on cream.
- Clear space = 1× mark diameter. Min size 24px.

## 2. Color Palette

| Token | Hex | Usage |
|-------|-----|-------|
| `saffron-500` | `#ff7d10` | Primary actions, accents |
| `saffron-600` | `#f06106` | Buttons, links hover |
| `saffron-700` | `#c74808` | Pressed states |
| `maroon-900` | `#6d2525` | Headings, footer bg |
| `maroon-950` | `#3b1010` | Footer/utility |
| `cream` | `#fffaf2` | Page background |
| `gold` | `#c9a227` | Decorative dividers, badges |
| `green-600` | `#16a34a` | Success |
| `red-600` | `#dc2626` | Danger/LIVE indicator |
| `amber-500` | `#f59e0b` | Warning/pending |

Contrast: all body text on `cream`/white meets WCAG AA (4.5:1). Saffron-600 on white = 4.6:1 (passes AA for large text; use maroon for small body).

## 3. Typography
- **Headings:** `Cinzel` (serif, 500/700) — temple gravitas.
- **Body:** `Inter` (sans, 400/500/600).
- **Gujarati:** `Noto Sans Gujarati` (400/500) — applied via `.font-gujarati` / locale switch.
- Scale: h1 3rem / h2 2rem / h3 1.5rem / body 1rem / small 0.875rem.

## 4. Components
- `.btn-temple` — pill, saffron-600, white text, temple shadow.
- `.card-temple` — white, rounded-2xl, saffron border, temple shadow.
- `.section-title` / `.section-subtitle` — centered serif headings.
- Badges: rounded-full, tinted bg + matching text per status.
- Hero: full-bleed saffron gradient with dark overlay + centered content.

## 5. Imagery
- Photography of temple, lake, rituals, festivals.
- Treatment: warm saturation, soft vignette.
- All images carry EN/GU `alt_text` (accessibility + SEO).
- WebP preferred, lazy-loaded.

## 6. Iconography
- Heroicons (outline) for UI; emoji for service cards on home (placeholder → replace with SVG).

## 7. Voice & Tone
Devout, warm, respectful, clear. Gujarati translations reviewed by native speaker before publish.
