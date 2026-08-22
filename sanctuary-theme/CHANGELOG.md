# Changelog

All notable changes to The Sanctuary theme. The version here must match
`Version:` in `style.css` — that is what the GitHub updater compares against.

## [0.11.0] — Separate footer logo + mobile overflow guards
### Added
- **Customizer → Footer → Footer logo**, with a height control. The header
  logo is the core Site Identity logo; the footer previously reused that
  same image, which is a problem when a dark header logo lands on the
  footer's dark ink background. Falls back to the Site Identity logo, then
  the site name as text, so nothing changes until a footer logo is set.

### Fixed
- Long unbreakable strings (emails, URLs) could overflow their column on
  narrow screens — `enquiries@thesanctuarystone.co.uk` is 33 characters
  with no natural break point. Added `overflow-wrap:anywhere` to the Find
  Us detail rows and the footer contact links.
- `.snc-find-grid` children now set `min-width:0`. Grid items default to
  `min-width:auto`, meaning they refuse to shrink below their content —
  the usual cause of a long value blowing out the column, and the page,
  on mobile.

## [0.10.0] — Timetable stacking spacing + Instagram label
### Fixed
- **Stacked Timetable Days had a huge gap between them.** Each widget
  renders one `.day`, so that card is always `:last-child` and its
  `margin-bottom` zeroes out — meaning all spacing came from the inherited
  `.snc-section` band padding, which doubles between neighbours
  (bottom + top). Four stacked days sat up to 10rem apart where the mockup
  had 2rem.
- The Instagram widget appended a hardcoded build-stage note
  ("live feed, auto-pulled…") to its label on every render, including on
  the live site once a real feed was connected. It now shows only while
  the placeholder tiles are displayed, and reads as an instruction
  ("paste your feed shortcode to go live").

### Added
- Timetable Day → **Top / bottom spacing**: Compact (default, for
  stacking), Normal (full section band), or None. Style → Layout &
  Spacing → Padding still overrides it with exact values.

### Notes
- Existing Timetable Day instances have no stored `spacing` value, so they
  pick up the `compact` default — this is the intended spacing fix, but it
  does mean an already-built timetable page will tighten up on update.
  Set any instance back to "Normal" to restore the previous look.

## [0.9.0] — Style controls on every widget + updater fixes
### Added
- A shared **Style tab** on all 21 widgets: text alignment, padding and
  margin (all responsive), background (colour/gradient), heading / text /
  eyebrow / link colours, border, border-radius and box-shadow.
  Implemented once in `Sanctuary_Base_Widget::add_style_controls()` and
  appended automatically, so new widgets get it for free.
- Tools → **Sanctuary: Updates** — a diagnostics screen showing the
  installed version, the actual installed folder name, the configured
  repo, and the latest release the updater can see, plus a "Check for
  updates now" button that clears the cached check. "No update showing"
  was previously impossible to debug from inside WordPress.

### Fixed
- The updater hardcoded the theme directory slug as `sanctuary`. The
  release zip does extract to `sanctuary/`, but a theme installed any
  other way (repo download, manual folder upload) sits in a differently
  named folder — and the mismatch made updates silently never appear. Now
  derived from `get_template()`, so it matches however it was installed.
- `Theme URI:` still pointed at the `OWNER/sanctuary-theme` placeholder.

### Notes
- Adding controls is non-destructive: existing widget instances simply
  have no value stored for the new keys, so Elementor falls back to their
  defaults — every one of which is empty here, emitting no CSS. Existing
  pages render exactly as before. Theme updates also never run the page
  seeder (it has no automatic hooks), so page content is untouched either
  way.
- Widgets now define `register_content_controls()` instead of
  `register_controls()`; the base class calls it and then appends the
  Style tab. Renamed across all 21 widgets.
- After updating, Elementor → Tools → **Regenerate CSS & Data** if styles
  look stale — that is a cache flush, not content loss.

## [0.8.0] — Per-page rebuild + Timetable page wired in
### Changed
- `snc_build_pages()` now takes an optional list of slugs to limit the
  rebuild to — previously it always rewrote `_elementor_data` on every
  page, so fixing or adding one page silently reset any manual edits made
  in the Elementor editor on the others.
- Tools → Sanctuary: Build pages now shows one button per page ("Rebuild
  this page" / "Create this page"), each scoped to only that page.
  "Rebuild ALL pages" still exists but is now a separate, explicitly-risky
  action behind a warning.
- `wp sanctuary build-pages` gained `--page=<slug>` (comma-separated for
  more than one); omitting it keeps the old rebuild-everything behaviour.

### Added
- Wired the Timetable Intro + Timetable Day widgets (0.7.0) into an actual
  `timetable` page definition: the intro section, then Monday/Tuesday/
  Wednesday/Thursday as separate Timetable Day instances with the real
  class times, categories, and booking links ported from
  `sanctuary-timetable.html` (verified byte-for-byte against the source —
  all 10 booking URLs, including the two reused across multiple classes).

## [0.7.0] — Adults Timetable page
### Added
- `Timetable Intro` widget (`sanctuary_timetable_intro`) — centered heading,
  an optional callout (e.g. "prices to be added"), and a colour-coded category
  legend (repeater).
- `Timetable Day` widget (`sanctuary_timetable`) — one day's class listing as
  a coloured card: day label, accent colour, and a repeater of classes
  (time / name / category chip / price / booking button). Stack one instance
  per day, same composable pattern as Event Cards / FAQ.
- New CSS in `assets/css/widgets.css`: `.snc-timetable-day` (the day card +
  responsive time/class/price/book grid, collapsing to a stacked layout under
  720px) and shared `.chip`/`.chip-{bl,ld,sq,pr}` + `.snc-callout`/
  `.snc-legend` styles used by both new widgets.
- Ported from `sanctuary-timetable.html` (adults ballroom/Latin/line-dancing
  timetable mockup).

### Fixed
- Avoided a section-class collision: the existing Booking Embed widget
  already used `.snc-timetable` — the new day-listing widget uses
  `.snc-timetable-day` instead.

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

## [0.6.0]
### Fixed
- Footer "Visit" menu no longer shows list bullets (`list-style: none`).
- Footer address now shows a location-pin icon, matching the other "Say hello"
  points (respects the show-icons toggle).

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
