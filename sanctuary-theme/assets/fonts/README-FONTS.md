# Self-hosted fonts

The theme expects three `.woff2` files in this folder. Until they are added, the
CSS stacks fall back to `system-ui`, so the site still works — but it won't match
the mockups. Add these before launch (Phase 5 QA checks for them):

| Family | Filename expected | Weights used | Source |
|---|---|---|---|
| Bricolage Grotesque | `bricolage-grotesque.woff2` | 500, 600, 700 (variable) | Google Fonts / GitHub (OFL) |
| Hanken Grotesk | `hanken-grotesk.woff2` | 400, 500, 600, 700 | Google Fonts (OFL) |
| Sacramento | `sacramento.woff2` | 400 | Google Fonts (OFL) — only the wordmark fallback |

## How to generate the woff2 files
1. Download the TTFs from Google Fonts (all three are Open Font License).
2. Convert to variable/subset `woff2` with `fonttools` or https://transfonter.org.
   - Recommended subset: `latin` + `latin-ext`.
3. Drop them here with the exact filenames above. No code change needed —
   `fonts.css` already references them.

All three are OFL-licensed and safe to bundle and redistribute with the theme.
