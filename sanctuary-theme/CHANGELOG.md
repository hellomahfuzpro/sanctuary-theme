# Changelog

All notable changes to The Sanctuary theme. The version here must match
`Version:` in `style.css` — that is what the GitHub updater compares against.

## [0.1.0] — Phase 0
### Added
- Theme foundation: `style.css` header, `functions.php` bootstrap, template
  files (`header.php`, `footer.php`, `index.php`, `page.php`, `front-page.php`,
  `404.php`).
- Design tokens ported from the client mockups (`assets/css/tokens.css`) and
  per-widget styles (`assets/css/widgets.css`).
- Self-hosted font scaffolding (`assets/fonts/fonts.css` + README).
- Customizer "The Sanctuary Options": header CTA, footer, contact, social,
  integrations (Acuity, Maps).
- Elementor "The Sanctuary" widget category + autoloader.
- GitHub over-the-air updater scaffolding (`inc/updater.php`).
- **19 custom Elementor widgets** (one library covering all 36 section
  instances across the four pages): Section Heading, Hero, Stat/Reassurance
  Strip, Two-Column Feature, Promo Cards, Event Cards, Style Cards, Use-Case
  Grid, Included List, Mosaic Gallery, Pricing, Rates Band, FAQ, Testimonials,
  Booking Embed (Acuity), Enquiry Form (Elementor Pro), Instagram Feed,
  Find Us / Map, Closing CTA.
- Programmatic page seeder (`inc/demo-import.php`): builds Home, Classes,
  Venue Hire and Private Hire as Elementor pages and sets the front page.
  Run via **Tools → Sanctuary: Build pages** or `wp sanctuary build-pages`.
- Content checklist (`CONTENT-CHECKLIST.md`).

## [Unreleased]
- Vendor `lib/plugin-update-checker`, add self-hosted font files, set repo URL,
  add `screenshot.png`; then live QA on a WordPress install.
