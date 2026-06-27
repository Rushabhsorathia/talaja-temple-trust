# Production Deployment Guide — Talaja Temple Trust Portal

## 1. Pre-deployment Checklist

### Code & Assets
- [ ] `composer install --no-dev --optimize-autoloader`
- [ ] `npm ci && npm run build`
- [ ] `php artisan config:cache && route:cache && view:cache && event:cache`
- [ ] Run `php artisan test` — all green (except known Breeze ProfileTest)
- [ ] `php artisan storage:link` executed

### Environment (.env) — production values
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://talajatemple.org
APP_TIMEZONE=Asia/Kolkata

DB_CONNECTION=mysql
DB_HOST=<rds/host>
DB_DATABASE=talaja_temple
DB_USERNAME=<user>
DB_PASSWORD=<strong>

CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
FILESYSTEM_DISK=s3

# Integrations
RAZORPAY_KEY=<live key>
RAZORPAY_SECRET=<live secret>
RAZORPAY_WEBHOOK_SECRET=<webhook secret>
MSG91_AUTH_KEY=<key>
WHATSAPP_ACCESS_TOKEN=<token>
WHATSAPP_PHONE_NUMBER_ID=<id>
AWS_BUCKET=<s3 bucket>
AWS_ACCESS_KEY_ID=<key>
AWS_SECRET_ACCESS_KEY=<secret>
```

### Integrations to configure
| Service | Action |
|---------|--------|
| Razorpay | Switch to live keys; register webhook URL `https://talajatemple.org/api/webhooks/razorpay` |
| MSG91 | Register DLT templates; set `MSG91_AUTH_KEY` |
| Email | Configure SMTP/SendGrid SES; verify sending domain (SPF/DKIM/DMARC) |
| WhatsApp | Approve templates via Meta Business |
| S3 | Create bucket + IAM user; lifecycle rule for `backups/` |

## 2. Server Setup (Nginx + PHP-FPM + Redis)

```bash
# PHP 8.3, Redis, MySQL 8, Node 20, Composer
sudo apt install nginx php8.3-fpm php8.3-{mbstring,mysql,redis,gd,intl,zip,bcmath} redis-server mysql-server

# Clone + install
git clone <repo> /var/www/talaja && cd /var/www/talaja
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan key:generate
php artisan migrate --force
php artisan storage:link
```

**Nginx vhost** (root → `/var/www/talaja/public`):
```nginx
server {
    listen 80;
    server_name talajatemple.org www.talajatemple.org;
    root /var/www/talaja/public;
    index index.php;
    client_max_body_size 20M;

    location / { try_files $uri $uri/ /index.php?$query_string; }
    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
    location ~* \.(css|js|jpg|png|svg|woff2?)$ { expires 30d; add_header Cache-Control "public"; }
}
```

Add SSL via Certbot: `sudo certbot --nginx -d talajatemple.org -d www.talajatemple.org`.
Put Cloudflare in front for CDN + WAF.

## 3. Process Managers (queue worker + scheduler + backup)

**pm2** (`ecosystem.config.cjs`):
```js
{ name: 'talaja-queue', script: 'artisan', args: 'queue:work redis --tries=3', interpreter: 'php' }
```
**systemd** alternative:
```bash
sudo crontab -e
* * * * * cd /var/www/talaja && php artisan schedule:run >> /dev/null 2>&1
```
The scheduler runs the daily 02:00 backup (`app:backup-database`).

## 4. Backup & Restore

- **Automated:** daily 02:00 via scheduler → `storage/app/backups/` + mirrors to S3 (`AWS_BUCKET`).
- **Manual:** `php artisan app:backup-database`
- **Retention:** 30 days (configurable `--keep=N`).
- **Restore drill (monthly):**
  ```bash
  # sqlite
  cp backups/db_<stamp>.sqlite database/database.sqlite
  # mysql
  mysql -u <user> -p talaja_temple < backups/db_<stamp>.sql
  php artisan migrate:fresh --seed --force  # validate
  ```

## 5. Security Hardening
- HTTPS-only (Certbot + Cloudflare), HSTS enabled.
- `APP_DEBUG=false`; secrets in `.env` (never committed).
- Rate limiting on `/otp/*`, `/donate`, `/login` (throttle middleware in place).
- Filament admin (`/admin`) restricted to `type` in [admin, staff, trustee].
- Run dependency audit: `composer audit`, `npm audit`.
- Annual penetration test.

## 6. Monitoring
- Uptime: Cloudflare / UptimeRobot on `/api/health`.
- Errors: configure Sentry (`SENTRY_LARAVEL_DSN`).
- Queue: Laravel Horizon (`php artisan horizon`).
- Logs: `storage/logs/laravel.log`, rotate via logrotate.

## 7. Go-Live Sequence
1. Final smoke test on staging with live (sandbox) keys.
2. UAT sign-off from trustees.
3. DNS cutover to production server.
4. Switch `.env` to live keys; clear+cache config.
5. Run queue worker + scheduler.
6. Post-deploy smoke test: home, donate (₹1), shop order, booking, admin login.
7. Rollback plan: keep previous release dir; Nginx root swap + `php artisan optimize:clear`.

## 8. Post-Launch (Hypercare — 3 months)
- Bug triage within 24h (P0), 72h (P1).
- Monthly: security + backup review.
- Quarterly: feature review with trust.
- Annual: penetration test + dependency upgrade.

## 9. Admin Credentials
Change the seeded `admin@talajatemple.org` / `password` immediately post-deploy.
Create trustee/staff accounts via `/admin` → Users (M4-T11).

## 10. Useful Commands
```bash
php artisan optimize                # cache config/routes/views
php artisan optimize:clear          # clear caches
php artisan queue:work redis        # process jobs
php artisan schedule:list           # view scheduled tasks
php artisan app:backup-database     # manual backup
php artisan horizon                 # queue dashboard
php artisan tinker                  # REPL
```
