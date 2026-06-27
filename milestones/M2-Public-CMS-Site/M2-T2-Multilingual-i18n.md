# M2-T2 — Multilingual (EN/GU) i18n Switcher

- **Type:** Story
- **Priority:** Highest
- **Story Points:** 5
- **Milestone:** M2 – Public CMS Site
- **Stack:** Laravel + Vue (Inertia)

## User Story
> As a Gujarati-speaking devotee, I want to view the site in Gujarati so I can understand content in my language.

## Acceptance Criteria
- [ ] Language switcher persists choice (cookie/session).
- [ ] All CMS content stored with EN + GU translation columns.
- [ ] UI labels translated via Laravel lang files (en/gu) + Vue i18n.
- [ ] Date/number formatting locale-aware.
- [ ] SEO hreflang tags emitted.

## Tasks
- Add `*_trans` pattern or JSON translation column on CMS models.
- Vue `$t()` composable for static strings.
- Switcher store (Pinia).

## Definition of Done
Toggle flips entire page language without reload artifacts.
