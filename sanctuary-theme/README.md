# The Sanctuary — WordPress theme

Bespoke Elementor-powered theme built from the client's mockups for The
Sanctuary, Stone. Each page section is a custom Elementor widget; header, footer
and global options are theme-native; the theme updates over the air from GitHub.

## Requirements
- WordPress 6.2+
- PHP 7.4+
- **Elementor** (free) — required
- **Elementor Pro** — required for the Enquiry Form widget
- An Instagram feed plugin (e.g. Spotlight / Smash Balloon) for the IG widget
- An Acuity Scheduling account (class timetable + booking)

## Install
1. Vendor the update library — see [`lib/README.md`](lib/README.md).
2. Set the real repo URL in `inc/updater.php` (`sanctuary_github_repo`).
3. Add the self-hosted fonts — see [`assets/fonts/README-FONTS.md`](assets/fonts/README-FONTS.md).
4. Zip the `sanctuary-theme` folder and install via Appearance → Themes → Add New,
   or push to the server. Activate.
5. Install & activate Elementor (+ Pro).
6. Configure **Appearance → Customize → The Sanctuary Options**.
7. Import the page templates (Phase 4) and set the homepage under
   Settings → Reading.

## Widget library
All custom widgets live in `/widgets` (one file per section) and appear in
Elementor under the **The Sanctuary** category. Adding a section is just dropping
a `class-{slug}.php` file that extends `Sanctuary_Base_Widget` — the autoloader
in `inc/elementor.php` registers it.

## Releasing an update
1. Make changes, bump `Version:` in `style.css` and add a `CHANGELOG.md` entry.
2. Commit and push.
3. Create a GitHub release/tag (e.g. `v0.2.0`).
4. Sites running the theme see the update under Appearance → Themes.
