---
title: SEO settings
description: URL rewriting, meta tags, canonical links, sitemap generation and crawler policy configuration.
sidebar_position: 4
---

# SEO settings

> Source: `include/config/dist.conf.inc.php`, `include/inc_front/front.func.inc.php` (structure data, meta tags), `include/inc_front/content.article.inc.php` (canonical), `sitemap.php`, root files `example.robots.txt`, `ai.txt`, `example.llms.txt`

All values are `include/config/conf.inc.php` keys; defaults quoted from `dist.conf.inc.php`. Per-page SEO fields (article/structure level) are edited in the backend; see [Structure tree](../backend/02-structure-tree.md) for the level form.

## URL rewriting and aliases

| Key | Type | Default | Effect |
| --- | ---- | ------- | ------ |
| `rewrite_url` | bool | `1` | Enables rewritten URLs; the frontend then renders `/im/` style image URLs and alias paths handled by the web server rewrite rules (templates in `_.htaccess`, `nginx.conf`, `web.config`). |
| `rewrite_ext` | string | `'.html'` | URL suffix: `/alias.html` (default) or a trailing `/` when set to `/`. |
| `alias_allow_slash` | bool | `1` | Permits `/` inside structure/article aliases. |
| `alias_allow_utf8` | bool | `1` | Keeps UTF-8 special characters in aliases (charset must be utf-8). |
| `allow_empty_alias` | bool | `false` | `true` prevents phpwcms from auto-creating an alias when the alias field is left empty. |
| `reserved_alias` | array | `[]` | List of alias names that may never be used (e.g. `array('admin', 'download')`). |
| `force301_id2alias` | bool | `false` | Sends a 301 redirect when an article/structure has an alias but the page is called by ID. |
| `force301_2struct` | bool | `false` | Sends a 301 redirect to the structure level when the level contains exactly one article. |
| `GET_pageinfo` | bool | `0` | Appends `&pageinfo=/cat1/cat2/page-title.htm` breadcrumb information to site links. |
| `remove_empty_get_vars` | bool | `true` | Drops valueless GET parameters (except the alias) from URLs. |
| `log_404error` | bool | `false` | Logs each 404 hit so redirects can be defined from the log; the redirect editor lives in Admin → Links & redirects (`p=14`). |
| `disable_next_prev` | bool | `false` | `true` suppresses the `<link rel="prev">`/`<link rel="next">` head tags that content-part pagination emits (the pagination links themselves still render). |

Rewriting only works when the web server passes unmatched requests to `index.php` — ship the matching rewrite config (`_.htaccess` renamed to `.htaccess` for Apache is created by the setup wizard; [Setup wizard](../installation/setup-wizard.md)). A missing `mode_rewrite`-style rewrite setup yields `index.php?alias` URLs but no broken pages, because both forms render.

## Meta tags from structure levels and articles

For each level phpwcms reads `acat_keywords`, `acat_info` and `acat_pagetitle`, for each article `article_keywords` and `article_description`:

| Rendered meta tag | Filled from |
| ----------------- | ----------- |
| `<meta name="description">` | Custom `meta.description` head block set via template/level; otherwise the structure level's `acat_info` (Info field). |
| `<meta name="keywords">` | Merged keywords of the current structure level and article(s). |
| Page title | Layout template title plus level `acat_pagetitle`/`acat_overwrite` settings. |

`enable_GDPR` (default `true`) additionally makes search-engine-related logging anonymize IPs where phpwcms handles logging itself (see [Modules overview](../modules/index.md)).

## Canonical links

| Key / field | Values | Effect |
| ----------- | ------ | ------ |
| `canonical_off` | bool, default `false` | `true` disables canonical tag output entirely. |
| `acat_canonical` (structure level) | string | Explicit canonical URL that overrides the generated one for this level. |
| `article_canonical` (article) | string | Explicit canonical URL for the article; overrides level value. |

Without an explicit value the canonical points to the article's own URL — including the canonical target of a content-part pagination (`...-<page>` alias) — and falls back to the level's single-article alias when `force301_2struct` is off.

## Open Graph and Twitter

| Key / field | Type | Default | Effect |
| ----------- | ---- | ------- | ------ |
| `set_sociallink` | array | `['article' => false, 'articlecat' => false, 'news' => false, 'shop' => false, 'render' => true]` | Per-type default state of the social/OpenGraph switch and whether OpenGraph meta renders at all (`render`). The per-article/level/News switch overrides the defaults. |
| `acat_opengraph` (level) | bool | default `1` | Disables OpenGraph for the whole structure branch when off. |
| `article_opengraph` (article) | bool | — | Per-article switch (`article_opengraph` field in the article form). |
| `opengraph_imagesize` | string | `'1200x630x0'` | Size spec `WxH[x1=crop]` the `og:image` thumbnail is generated with; ignored when empty (then `1200x630x1`). |
| `twitter_site` | string | `''` | `@handle` of the website account, emitted as `twitter:site`. `twitter:card` and `twitter:creator` are always emitted while OpenGraph renders. |

The OpenGraph title, description, URL and type (`website` / `article`) are composed from level/article data automatically; the image is the first article/level social image, rendered through the [image processing pipeline](image-processing.md).

## Sitemap

`sitemap.php` in the document root emits a Google-style XML sitemap. Include it in a [robots.txt](#robots-ai-txt-and-llms-txt) or in search console to get articles indexed.

- Lists active, non-deleted, search-visible articles on public, sitemap-enabled structure levels, plus active [News](../modules/index.md) module entries.
- A level or article participates only when its *Sitemap* checkbox (`acat_nositemap` = 1 / `article_nositemap` = 1) is set — the field name is inherited from older versions although the meaning is "include in sitemap".
- Custom URLs: create `include/config/sitemap.custom.php` defining `phpwcms_getCustomSitemap(&$struct)` returning `array('url' => ..., 'date' => ...)` entries, or add entries to `include/config/sitemap.custom.ini` in INI sections (`[1] url = "https://..."` / `date = "YYYY-MM-DD"`). Both ship as templates.
- When no article qualifies, the base URL alone is emitted; flags `sitemap_set_base` / `sitemap_set_default` in the custom functions control whether the base link and all default articles render.
- Output is cached for 12 hours in `content/cache/sitemap.cache.xml` — delete the file to force regeneration after publishing.

<a id="robots-ai-txt-and-llms-txt"></a>
## robots, ai.txt and llms.txt

These are plain static files served from the document root — no code renders them. The repository ships examples only for `robots.txt`; use them as starting points:

| File | Ships as | Purpose |
| ---- | -------- | ------- |
| `robots.txt` | `example.robots.txt` | Search crawler rules plus `Sitemap:` line pointing at `sitemap.php`. |
| `ai.txt` | `ai.txt` (active) and `example.ai.txt` | Bot-specific crawling rules, protection for `/login.php`, `/filearchive/`, `/include/`, `/setup/`, and an EU TDM Article 4(3) reservation. |
| `llms.txt` | `llms.txt` (active) and `example.llms.txt` | Structured project context for LLMs per the llmstxt.org specification. |

Copy the active `ai.txt`/`llms.txt` or adapt the examples for your domain; the updater preserves `robots.txt` during an update.

## Search logging

`enable_seolog` (default `1`) enables logging of search engine referrer data (bot identification + search referrers) consumed by the [SEO log module](../modules/index.md). Bot user agents additionally skip sessions — the list is the `BOTS` config array (read-only behavior, `googlebot`, `bingbot`, ...).

## Related

- [Cache and performance](cache-performance.md) — page delivery, HTTP cache headers
- [Admin panel](admin-panel.md) — where the update engine and redirect editor sit
- [Structure tree](../backend/02-structure-tree.md) — per-level SEO fields