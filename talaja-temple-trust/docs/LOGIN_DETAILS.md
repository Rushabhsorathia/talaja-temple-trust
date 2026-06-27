# Login Details — Talaja Temple Trust Portal

> ⚠️ **DEMO / DEVELOPMENT credentials only.** Change the admin password and rotate seeded users before any production use. Do NOT commit real production credentials.

App URL (local): http://localhost:8001
Admin panel: http://localhost:8001/admin

---

## Admin Panel (Filament — full backend access)

| Field | Value |
|-------|-------|
| URL | http://localhost:8001/admin |
| Name | Super Admin |
| Email | `admin@talajatemple.org` |
| Password | `password` |
| Role | Super Admin |

---

## Devotee Login

Two methods available for every devotee:
- **Email + password:** http://localhost:8001/login
- **Mobile OTP:** http://localhost:8001/otp/login
  - In dev mode the OTP is written to `storage/logs/laravel.log` (search `[OTP]`). No real SMS is sent.

**Password for ALL devotee accounts:** `password`

| # | Name | Email | Mobile |
|---|------|-------|--------|
| 1 | Hetal Mehta | hetal-mehta0@example.com | 9710611142 |
| 2 | Pooja Gandhi | pooja-gandhi1@example.com | 9692063276 |
| 3 | Rajesh Mehta | rajesh-mehta2@example.com | 9973431563 |
| 4 | Rasilaben Raval | rasilaben-raval3@example.com | 9696038707 |
| 5 | Mitesh Modi | mitesh-modi4@example.com | 9957519614 |
| 6 | Ashok Soni | ashok-soni5@example.com | 9714581822 |
| 7 | Pooja Modi | pooja-modi6@example.com | 9819083671 |
| 8 | Pankaj Raval | pankaj-raval7@example.com | 9912525207 |
| 9 | Meena Patel | meena-patel8@example.com | 9749713560 |
| 10 | Rajesh Desai | rajesh-desai9@example.com | 9696418681 |
| 11 | Prakash Shastri | prakash-shastri10@example.com | 9995727815 |
| 12 | Prakash Mehta | prakash-mehta11@example.com | 9657545248 |
| 13 | Hardik Janjmera | hardik-janjmera12@example.com | 9937250663 |
| 14 | Anand Mehta | anand-mehta13@example.com | 9617136386 |
| 15 | Sanjay Joshi | sanjay-joshi14@example.com | 9637368183 |
| 16 | Anjali Chauhan | anjali-chauhan15@example.com | 9801368307 |
| 17 | Jigna Patel | jigna-patel16@example.com | 9755271519 |
| 18 | Dipak Shastri | dipak-shastri17@example.com | 9621117313 |
| 19 | Chirag Raval | chirag-raval18@example.com | 9721113950 |
| 20 | Nilesh Modi | nilesh-modi19@example.com | 9839760058 |
| 21 | Rajesh Shastri | rajesh-shastri20@example.com | 9878897653 |
| 22 | Rakesh Modi | rakesh-modi21@example.com | 9841696956 |
| 23 | Dipti Joshi | dipti-joshi22@example.com | 9816064402 |
| 24 | Manjulaben Dave | manjulaben-dave23@example.com | 9747130062 |
| 25 | Mahesh Pandya | mahesh-pandya24@example.com | 9683436617 |
| 26 | Kiran Dave | kiran-dave25@example.com | 9689402527 |
| 27 | Mahesh Joshi | mahesh-joshi26@example.com | 9642925854 |
| 28 | Ashok Chauhan | ashok-chauhan27@example.com | 9704058760 |
| 29 | Chirag Soni | chirag-soni28@example.com | 9676192596 |
| 30 | Lataben Shastri | lataben-shastri29@example.com | 9733701545 |
| 31 | Nilesh Patel | nilesh-patel30@example.com | 9931160964 |
| 32 | Dipak Bhatt | dipak-bhatt31@example.com | 9924991634 |
| 33 | Rajesh Bhatt | rajesh-bhatt32@example.com | 9842369822 |
| 34 | Sneha Joshi | sneha-joshi33@example.com | 9663860544 |
| 35 | Kiranben Joshi | kiranben-joshi34@example.com | 9659193355 |
| 36 | Mahesh Thakkar | mahesh-thakkar35@example.com | 9705260108 |
| 37 | Suresh Chauhan | suresh-chauhan36@example.com | 9901772507 |
| 38 | Hardik Acharya | hardik-acharya37@example.com | 9622949535 |
| 39 | Jigna Bhatt | jigna-bhatt38@example.com | 9998053196 |
| 40 | Ritu Dave | ritu-dave39@example.com | 9758416123 |

---

## Regenerate / list all accounts anytime

```bash
php artisan tinker --execute="echo App\Models\User::where('type','devotee')->orderBy('id')->get(['name','email','mobile'])->toJson(JSON_PRETTY_PRINT);"
```

## Notes
- Mobiles are random 10-digit numbers starting with 9 (format only; not real numbers).
- Devotee emails follow `{firstname}-{lastname}{index}@example.com` (index 0–39).
- OTP auth writes the code to the log; wire MSG91 (`MSG91_AUTH_KEY`) for real SMS.
- Admin panel access is restricted to users with `type` in [admin, staff, trustee].
