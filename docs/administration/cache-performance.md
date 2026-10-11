---
title: Cache and performance
description: HTTP cache headers, image cache, generated file caches, lazy loading and debug switches.
sidebar_position: 5
---

# Cache and performance

> Source: `index.php` (page delivery, cache headers), `include/inc_front/content.func.inc.php` (structure cache, lazy loading), `include/inc_lib/imagick.convert.inc.php` (image cache), `sitemap.php` (sitemap cache), `include/config/dist.conf.inc.php`

## Page delivery

phpwcms renders every page on request and builds no full-page cache table. The former 1.x page cache (`phpwcms_cache` database table, controlled by `cache_enabled`) does not exist in 2.x: no code reads `cache_enabled`, and the two remaining keys only steer HTTP headers.

| Key | Type | Default | Effect |
| --- | ---- | ------- | ------ |
| `cache_enabled` | bool | `0` | Legacy key. Read nowhere in 2.x code; kept for configuration compatibility. |
| `cache_timeout` | int (seconds) | `0` | When set, the frontend sends `Expires`, `Last-Modified`, `Cache-Control: public, max-age=<timeout>` and `Pragma: public` headers with each page. `0` disables them. |

Output compression (gzip/deflate) is not handled by phpwcms — the web server does it (`mod_deflate`/`mod_brotli`, `ngx_http_gzip_module`). File downloads explicitly disable `zlib.output_compression` so binary delivery stays intact.

## Server-side data caches

| Cache | Location | Invalidates |
| ----- | -------- | ----------- |
| Image cache (generated sizes) | `content/images/` — one file per width/height/crop/quality/sharpen/colorspace combination, named by md5 thumb key | Admin → [Flush image cache](admin-panel.md#maintenance-actions); regenerate automatically on next request |
| Structure meta cache | `phpwcms_sysvalue` table, key `frontend_render` — serialized level/article metadata | Rebuilt automatically while rendering; no manual action needed |
| Sitemap cache | `content/cache/sitemap.cache.xml`, 12 hours | Delete the file after publishing to regenerate immediately |
| Image convert cache | `content/images/<hash>-<function>.<ext>` for `/im/convert...` endpoint requests (e.g. grayscale conversion) so repeated conversion requests stay cheap | Part of the image cache, flushed together |

## Lazy loading

`lazy_loading` (string, default `'lazy'`) controls how images and iframes load: `lazy` (recommended), `eager`, or `auto`. Templates render `{LAZY_LOADING}` where the value should appear in markup; the frontend replaces the tag globally in the rendered page. See [Template system](../templates/template-system.md) for placement inside custom templates.

## Performance-relevant switches

| Key | Default | Effect |
| --- | ------- | ------ |
| `gd_memcheck_off` | `0` | `1` disables the PHP memory pre-check before GD resizing (faster on known-good hosts, riskier on small memory limits). |
| `version_check` | `1` | Checks the current phpwcms release online for [System update](../installation/updating.md) notifications; `0` for offline servers. |
| `render_clean_html` | `0` | Strips HTML comments from the page source before output; marked experimental. |
| `recipient_count` | `0` | Legacy, no longer read in 2.x. |

Frontend sessions add database work per request: keep `SESSION_FEinit` at `0` (default) when no [frontend login](../general/concepts.md) is used. Bot user agents never start sessions regardless.

## Debug and diagnosis switches

| Key | Default | Effect |
| --- | ------- | ------ |
| `db_errorlog` | `false` | Logs database queries/errors — enable only while diagnosing. |
| `log_404error` | `false` | Logs each 404 for the redirect editor; see [SEO settings](seo-settings.md). |
| `SMTP_DEBUG` | `0` | SMTP debug level 0–4 for mail delivery problems; see [Configuration file](../installation/config-file.md). |
| `disable_generator` | `false` | `true` removes `<meta name="generator">` and the `X-phpwcms-Release` response header. |
| `disable_processed_in` | `false` | `true` removes the `X-phpwcms-Page-Processed-In` response header that reports page render time. |
| `browser_check` | array (`fe`/`be` = `false`) | Enables the browser-update.org notice in frontend and/or backend for outdated browsers ("vs" sets version rules, "insecure"/"required" tune behavior). |

Whitelabel mode force-disables the generator and processing-time headers; see [Whitelabel branding](whitelabel.md).

## Related

- [Admin panel](admin-panel.md) — image cache flushing, update maintenance mode
- [Image processing](image-processing.md) — how the cache file names derive from parameters
- [Configuration file](../installation/config-file.md) — full key reference