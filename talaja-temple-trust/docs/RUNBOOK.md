# Operations Runbook — Talaja Temple Trust

## Daily
- Check uptime monitor + `/api/health`.
- Review `storage/logs/laravel.log` for errors (P0/P1 triage).

## Weekly
- Verify daily backups landed (local + S3); spot-check a backup file size.
- Review failed queue jobs (`php artisan queue:failed`); retry/clear.

## Monthly
- **Backup restore drill:** restore latest DB to a staging DB; confirm row counts match; destroy.
- Apply OS/security package updates; `composer audit && npm audit`.
- Rotate API tokens / review admin user list.

## Incident response

### Site down
1. `systemctl status nginx php8.3-fpm` → restart if failed.
2. `tail -100 storage/logs/laravel.log`.
3. `php artisan optimize:clear` then `php artisan config:cache`.
4. If DB: check `mysql` reachable; Redis `redis-cli ping`.
5. Rollback: switch Nginx root to previous release dir; reload nginx.

### Payment not reflected
- Search donation by gateway payment id in `/admin`.
- Check webhook log; re-trigger by replaying Razorpay webhook (Events panel) or manually update status.
- Verify `RAZORPAY_WEBHOOK_SECRET` matches dashboard.

### Email/SMS not sending
- SMS: confirm `MSG91_AUTH_KEY` + DLT template approved; check Notification Logs.
- Email: verify SPF/DKIM/DMARC; check mail driver logs.

### Queue backed up
- `php artisan horizon:terminate` then restart; add workers via pm2 scale.
- Inspect failed jobs; fix + `queue:retry all`.

## Backup/Restore commands
```bash
php artisan app:backup-database                 # manual backup
# restore sqlite
cp storage/app/backups/db_<stamp>.sqlite database/database.sqlite
# restore mysql
mysql -u <user> -p talaja_temple < storage/app/backups/db_<stamp>.sql
php artisan config:cache
```

## Cache
```bash
php artisan cache:clear        # app cache (home data etc.)
php artisan optimize:clear     # all caches
php artisan config:cache       # rebuild for prod
```

## Contacts (fill in)
- DevOps on-call: __________
- Razorpay support / MSG91 support keys vault: __________
- Trust IT owner: __________
