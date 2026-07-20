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

## [0.5.0]
### Fixed
- **Real cause of the heading-font bug**: our design token was named `--display`,
  which collides with the `--display: flex` CSS variable Elementor sets on every
  flexbox Container. Inside a container `var(--display)` resolved to `flex`, so
  `font-family` was invalid and fell back. Renamed the font tokens to
  `--snc-display` / `--snc-body` / `--snc-script` so they can never collide.

## [0.4.0]
### Fixed
- **Headings now use the brand display font (Bricolage).** Elementor's global
  "kit" typography was tying on specificity and winning by load order (showing
  its `--e-global-typography-*` var). Brand fonts + image radii are now enforced.
- **Image border-radius** reliably applied to hero, feature, gallery and IG
  media even when a global `img` rule would reset it.
### Added
- **Footer "Say hello" is configurable**: custom label per point, a master
  show/hide-icons toggle, built-in channel icons, and extra Facebook + WhatsApp
  points (Customize → The Sanctuary Options → Say hello).

## [0.3.0]
### Fixed
- **Elementor editor dark mode**: theme CSS is no longer injected into the
  editor *panel* UI (only the preview iframe), so control labels are readable
  again in dark mode. Our global reset was overriding Elementor's panel styling.
### Added
- **Find Us map works out of the box**: the widget now auto-generates a Google
  Maps embed from an address (no API key), falling back to the Customizer
  contact address, then the venue default. A custom `<iframe>` still overrides it.

## [0.2.0]
### Fixed
- **Fonts/wordmark now match the mockups** — the theme loads the original Google
  Fonts (Bricolage Grotesque, Hanken Grotesk, Sacramento) instead of falling
  back to system fonts. (0.1.0 shipped empty self-hosted font files.)
### Added
- **Auto-update is now live**: vendored Plugin Update Checker v5.6 into
  `lib/` and pointed the updater at `hellomahfuzpro/sanctuary-theme`. No extra
  plugin needed — updates appear under Appearance → Themes.
- **Auto free-image import**: the page seeder now downloads a curated set of
  free-licensed Unsplash photos into the Media Library and assigns them to the
  widgets (filter `sanctuary_image_sources` to swap). Falls back to gradient
  placeholders if a download fails.
### Changed
- **Page seeder builds flexbox Containers**, not the legacy Section/Column
  structure. Also enables the container feature on build.

## [Unreleased]
- Optional: self-host the fonts (drop woff2s in assets/fonts) for UK GDPR;
  add `screenshot.png`; live QA on a WordPress install.
