# M4-T1 — Admin Auth, Roles, Permissions, MFA

- **Type:** Story
- **Priority:** Highest
- **Story Points:** 5
- **Milestone:** M4 – Admin Panel
- **Stack:** Laravel + Filament Shield (spatie/laravel-permission)

## User Story
> As an admin, I want secure role-based login with MFA.

## Acceptance Criteria
- [ ] Roles: Super Admin, Trustee, Admin, Officer, Staff.
- [ ] Permission matrix per module.
- [ ] MFA (TOTP) enforced for admin/trustee.
- [ ] Password policy (length, expiry, history).
- [ ] Login monitoring (IP, device, fail attempts).
- [ ] Session timeout + concurrent session limit.
- [ ] Account lockout.

## Definition of Done
Non-authorized role cannot access restricted modules.
