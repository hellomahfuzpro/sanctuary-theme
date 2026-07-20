# Bundled libraries

## plugin-update-checker (required for GitHub auto-updates)

The GitHub updater in `inc/updater.php` expects the **Plugin Update Checker**
library here at `lib/plugin-update-checker/`.

Vendor it once:

```bash
cd sanctuary-theme/lib
curl -L https://github.com/YahnisElsts/plugin-update-checker/archive/refs/tags/v5.6.zip -o puc.zip
unzip puc.zip
mv plugin-update-checker-5.6 plugin-update-checker
rm puc.zip
```

(Or `composer require yahnis-elsts/plugin-update-checker` and point the
`require_once` in `inc/updater.php` at the vendored path.)

Then set the real repo URL via the `sanctuary_github_repo` filter or by editing
the placeholder in `inc/updater.php`. Until both are done the updater no-ops
safely, so the theme still functions.

License: MIT.
