# Talaja Temple Trust — Portal

Secure, scalable, multilingual (EN/GU) **Temple Trust Management Web Portal** built with Laravel + Vue (Inertia) + Filament.

> Reference design: https://www.khodiyarmandirtrust.com/
> Spec: `../Talaja-Temple-Trust-Plan.md` & `../milestones/`

## Tech Stack
- **Backend:** Laravel 11 (PHP 8.3), Filament v3 (admin), Sanctum (API auth)
- **Frontend:** Vue 3 via Inertia.js, Pinia, Tailwind CSS, Vite
- **Packages:** spatie/laravel-permission, spatie/laravel-medialibrary, spatie/laravel-activitylog, spatie/laravel-translatable, predis
- **DB:** MySQL 8 (prod) / SQLite (local dev) · Redis (prod cache/queue)
- **Integrations:** Razorpay, MSG91, SendGrid/SES, WhatsApp Cloud API, YouTube Live

## Local Setup

```bash
# 1. PHP dependencies
composer install

# 2. Environment
cp .env.example .env
php artisan key:generate

# 3. Database (SQLite works out of the box for dev)
touch database/database.sqlite
php artisan migrate --seed

# 4. Frontend dependencies & dev server
npm install
npm run dev      # in one terminal
php artisan serve # in another
```

- App: http://localhost:8000
- Admin panel: http://localhost:8000/admin
  - Email: `admin@talajatemple.org`
  - Password: `password`  ⚠️ change in production

## Production Switch
Edit `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=...
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
FILESYSTEM_DISK=s3
```

## Design Tokens
Temple palette & typography live in `tailwind.config.js`:
- Colors: `saffron`, `maroon`, `cream`, `gold`
- Fonts: `font-sans` (Inter), `font-serif` (Cinzel), `font-gujarati` (Noto Sans Gujarati)
- Components: `.btn-temple`, `.card-temple`, `.section-title`

## Scripts
| Command | Purpose |
|---------|---------|
| `npm run dev` | Vite dev server (HMR) |
| `npm run build` | Production build (incl. SSR) |
| `php artisan serve` | PHP dev server |
| `php artisan migrate:fresh --seed` | Reset DB with seed data |
| `vendor/bin/pint` | PHP formatting |
| `php artisan test` | Run test suite |

## Structure
```
app/
  Filament/Resources      # admin CRUD modules (M4)
  Http/Controllers        # public + devotee controllers
  Models/                 # Eloquent models
database/migrations/      # full domain schema (M1-T5)
lang/{en,gu}/             # translations (EN/GU)
resources/js/
  Layouts/AppLayout.vue   # public header/footer/utility bar
  Pages/                  # Inertia Vue pages
tailwind.config.js        # design tokens
.github/workflows/ci.yml  # CI pipeline
```

## Milestone Status
- ✅ M1-T5 Database schema
- ✅ M1-T7 Tech stack setup
- ⏳ M1-T1..T4 Discovery & design (pending stakeholder input)
