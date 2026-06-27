# API Documentation — Talaja Temple Trust (v1)

Base URL: `https://talajatemple.org/api`
Auth: Bearer token (Laravel Sanctum). Obtain via admin → API tokens.

## Endpoints

### Health
`GET /health` → `{ "status": "ok", "time": "<iso8601>" }` (no auth)

### Webhooks
`POST /webhooks/razorpay` — Razorpay payment events (signature-verified, idempotent).
Events handled: `payment.captured`, `payment.authorized`.

### Authenticated (`/v1` — Bearer token)

#### `GET /v1/me`
Current user profile.
**200:** `{ "id": 1, "name": "...", "email": "...", "mobile": "..." }`

#### `GET /v1/donations`
Donations belonging to the authenticated donor.
**200:** `[ { "id": 1, "receipt_no": "...", "amount": "501.00", "status": "success", "category": {...} } ]`

#### `GET /v1/news`
Latest published news (20).
**200:** `[ { "id": 1, "slug": "...", "title": "...", "excerpt": "...", "published_at": "..." } ]`

## OpenAPI 3.0 snippet
```yaml
openapi: 3.0.3
info:
  title: Talaja Temple Trust API
  version: 1.0.0
servers:
  - url: https://talajatemple.org/api
components:
  securitySchemes:
    bearerAuth:
      type: http
      scheme: bearer
paths:
  /health:
    get:
      summary: Health check
      security: []
      responses: { '200': { description: ok } }
  /v1/me:
    get:
      security: [ { bearerAuth: [] } ]
      responses: { '200': { description: profile } }
  /v1/donations:
    get:
      security: [ { bearerAuth: [] } ]
      responses: { '200': { description: donor donations } }
  /v1/news:
    get:
      security: [ { bearerAuth: [] } ]
      responses: { '200': { description: news list } }
  /webhooks/razorpay:
    post:
      summary: Razorpay payment webhook
      security: []
      responses: { '200': { description: processed } }
```

## Rate limiting
- OTP/auth/donate routes throttled (5/min).
- API routes: standard Sanctum throttling.

## Versioning
APIs are prefixed `/v1`. Breaking changes → `/v2`.
