# Milestone 5 – Integrations & API Framework

**Duration:** Weeks 7–8
**Stack:** Laravel + Queue + REST API (Sanctum)
**Goal:** Wire external systems and expose a secure API layer.
**Exit Criteria:** All integrations verified end-to-end in staging with real sandbox transactions.

| Ticket | Title | Story Points |
|--------|-------|--------------|
| [M5-T1](M5-T1-Payment-Gateway.md) | Razorpay payment gateway integration | 5 |
| [M5-T2](M5-T2-SMS-Gateway.md) | SMS gateway (MSG91/Twilio) | 3 |
| [M5-T3](M5-T3-Email-Server.md) | Email (SendGrid/SES) | 2 |
| [M5-T4](M5-T4-WhatsApp.md) | WhatsApp Business API | 5 |
| [M5-T5](M5-T5-QR-Services.md) | Dynamic UPI QR service | 3 |
| [M5-T6](M5-T6-YouTube-Live.md) | YouTube Live darshan embed | 2 |
| [M5-T7](M5-T7-REST-API.md) | REST API + Sanctum auth + webhooks | 8 |

## Build status (✅ implemented)
- **T1 Payment:** `RazorpayService` (createOrder/verifySignature) + checkout flow + `/api/webhooks/razorpay` (idempotent). Dev-simulates when keys absent.
- **T2 SMS:** `NotificationService::sendSms` (MSG91-ready, logs in dev) + templates.
- **T3 Email:** `NotificationService::sendEmail` via Laravel Mail.
- **T4 WhatsApp:** `NotificationService::sendWhatsApp` (Cloud API-ready).
- **T5 QR:** dynamic UPI deep-link endpoint (`/donate/qr`).
- **T6 YouTube:** `LiveDarshanController` + responsive embed.
- **T7 REST API:** `/api/v1/*` (Sanctum) + `/api/health` + webhooks (api.php enabled in bootstrap/app.php).
