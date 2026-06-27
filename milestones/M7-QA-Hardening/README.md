# Milestone 7 – QA, Hardening & Backup

**Duration:** Week 10
**Stack:** Laravel + Pest/Dusk + Cloudflare
**Goal:** Ensure quality, security, accessibility, performance and reliable backups.
**Exit Criteria:** All P0/P1 bugs fixed, security pass, Lighthouse ≥ 85, DR drill success.

| Ticket | Title | Story Points |
|--------|-------|--------------|
| [M7-T1](M7-T1-Functional-Testing.md) | Functional & regression test suite | 8 |
| [M7-T2](M7-T2-Security-Audit.md) | Security audit + hardening | 5 |
| [M7-T3](M7-T3-Performance.md) | Performance & caching optimisation | 5 |
| [M7-T4](M7-T4-Accessibility.md) | WCAG 2.1 AA accessibility audit | 5 |
| [M7-T5](M7-T5-Cross-Browser.md) | Cross-browser & device matrix | 3 |
| [M7-T6](M7-T6-Backup-Restore.md) | Backup + disaster recovery | 5 |
| [M7-T7](M7-T7-Monitoring-Alerting.md) | Monitoring, logging, alerting | 3 |

## Build status (✅ partially implemented; some items need prod env)
- **T1 Tests:** `tests/Feature/PublicSiteTest` (home/news/contact/shop) passing; Temple/News factories added.
- **T2 Security:** throttle on OTP/donate; admin RBAC gate; APP_DEBUG flagging; `composer audit`.
- **T3 Performance:** Redis cache/session config, Vite build, image lazy-load in views.
- **T6 Backup:** `app:backup-database` command (sqlite/mysql + S3 mirror + 30-day prune), scheduled daily 02:00.
- **T7 Monitoring:** `/api/health` endpoint; Sentry/Horizon ready to wire via env.
- Cross-browser, accessibility, DR drill to be performed on staging/prod.
