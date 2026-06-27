# Infrastructure Provisioning Checklist — M1-T6

## 1. Domain & DNS
- [ ] Register/transfer `talajatemple.org` (+ www).
- [ ] Create A record → server IP once provisioned.
- [ ] Configure Cloudflare (proxy on) for CDN + WAF + DDoS.
- [ ] MX/TXT (SPF, DKIM, DMARC) for transactional email domain.

## 2. Server
- [ ] Provision VPS (Ubuntu 24.04, 2 vCPU / 4 GB min).
- [ ] Install: PHP 8.3-FPM, MySQL 8, Redis, Node 20, Nginx, Composer, Certbot.
- [ ] PHP extensions: mbstring, mysql, redis, gd, intl, zip, bcmath, xml, curl.
- [ ] Create deploy user; SSH key auth; disable root login + password auth.
- [ ] UFW: allow 22, 80, 443 only.

## 3. Application
- [ ] Clone repo to `/var/www/talaja`.
- [ ] `composer install --no-dev`, `npm ci && npm run build`.
- [ ] Set production `.env` (APP_ENV=production, APP_DEBUG=false).
- [ ] `php artisan key:generate`, `migrate --force`, `storage:link`.
- [ ] `php artisan config:cache && route:cache && view:cache`.
- [ ] Nginx vhost (root → public) + Certbot SSL.

## 4. Integrations (sandbox first, then live)
- [ ] **Razorpay:** account → API keys → webhook URL `/api/webhooks/razorpay` + webhook secret.
- [ ] **MSG91:** account → DLT-approved SMS templates → auth key + sender ID.
- [ ] **Email (SendGrid/SES):** API key + verified sending domain.
- [ ] **WhatsApp Cloud API:** Meta Business → test number → template approvals.
- [ ] **S3:** bucket + IAM user (read/write `backups/`, `receipts/`, media).

## 5. Processes
- [ ] Queue worker (`queue:work redis` via pm2/systemd).
- [ ] Scheduler cron (`* * * * * php artisan schedule:run`).
- [ ] (Optional) Laravel Horizon for queue monitoring.

## 6. Observability
- [ ] Uptime monitor (Cloudflare/UptimeRobot) on `/api/health`.
- [ ] Sentry DSN for errors.
- [ ] Logrotate for `storage/logs/laravel.log`.

## 7. Backups & DR
- [ ] Verify daily 02:00 backup lands in `storage/app/backups/` + S3.
- [ ] 30-day retention; monthly restore drill documented.
- [ ] Offsite copy of `.env` + DB dump in secure vault.

## 8. Sign-off
- [ ] Smoke test (home, ₹1 donate, shop order, booking, admin login).
- [ ] Change default admin password; create trustee/staff accounts.
- [ ] DNS cutover; SSL verified; rollback plan confirmed.
