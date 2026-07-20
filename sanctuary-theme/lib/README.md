# Bundled libraries

## plugin-update-checker (required for GitHub auto-updates)

**Status: vendored (v5.6).** Present at `lib/plugin-update-checker/` and used by
`inc/updater.php` (pointed at `hellomahfuzpro/sanctuary-theme`). No action needed.

To re-vendor / upgrade in future:

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
