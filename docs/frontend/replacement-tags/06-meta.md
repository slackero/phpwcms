---
title: Meta and head tags
description: Page title, meta description and keywords, canonical links and Open Graph rendering.
sidebar_position: 6
---

# Meta and head tags

> Source: `index.php` (page assembly), `include/inc_front/content.func.inc.php` (metas, Open Graph), `include/inc_front/content.article.inc.php` (article metas), `include/inc_front/front.func.inc.php` (`set_meta()`, `setPageTitle()`)

phpwcms builds the `<head>` automatically: doctype and document language, content type, base href, page title, description, keywords, canonical link and social sharing meta. Templates add to it with `set_meta()` from a frontend render script or with custom code in a template's head fields.

## Document language

The `lang` attribute of the `<html>` tag comes from `PHPWCMS_DOCTYPE_LANG`, defined per doctype mode in `include/inc_lib/default.inc.php`:

```
{DOCTYPE_LANG}
```

The placeholder sits inside the doctype template string and receives the value of `$phpwcms['DOCTYPE_LANG']` — the two letter code of the frontend language (for example `en`, `de`). With `use_content_lang` enabled, a structure level language (`acat_lang`) overrides the default, so a translated subtree serves a matching `lang` attribute. The same value feeds `og:locale` and the localized [date tags](07-date-time.md).

The XHTML doctypes (modes 1 and 2) additionally emit `xml:lang`; the HTML 4.01 and HTML5 forms emit `lang` only.

## Page title

`index.php` writes `$content['pagetitle']` into `<title>`. The value builds from three parts — layout title (`pagelayout['layout_title']`), structure level name and article title — merged by `setPageTitle()` in the order configured by `pagelayout['layout_title_order']` (default 0: layout title, level, article), joined by `layout_title_spacer` (default ` | `). Orders 1-14 produce other combinations up to a single part; empty parts drop out and duplicates collapse.

A custom title wins over the composed one: `article_pagetitle` for the article, `acat_pagetitle` for the level. Replacement tag pairs inside the page title get cleaned before output (`sanitize_replacement_tags()` replaces `{TAG}…{/TAG}` pairs with their inner content), so stray tags never leak into the title.

| Source | Priority |
| ------ | -------- |
| `article_pagetitle` | 1 — article detail view |
| `acat_pagetitle` | 2 — level view and list mode |
| `setPageTitle()` composition | 3 — fallback |

## Description and keywords

| Value | Source | Behavior |
| ----- | ------ | -------- |
| `description` | `article_description` of the shown article | `set_meta('description', …)` when the article defines one; feeds `og:description`. |
| `description` | `acat_info` of the current level | Fallback when the page has no description yet and the level carries an info text. |
| `keywords` | article `article_keyword` plus level `acat_keywords` | Merged, `*CSS-…*` class keywords filtered out, added as one `keywords` meta when none was defined. |

`set_meta()` registers the tag in `$block['custom_htmlhead']['meta.<name>']`; a template or script can set the same key earlier to keep its own value — the automatic pass checks for an existing `description` / `keywords` meta before adding.

Robots: when the article meta defines `noindex` or `nofollow`, phpwcms emits `set_meta('robots', …)` and the matching `X-Robots-Tag` HTTP header.

## Canonical link

The canonical `<link rel="canonical">` resolves in this order:

| Source | Config / field | Behavior |
| ------ | -------------- | -------- |
| Article custom canonical | `article_canonical` | Replaces any other canonical. |
| Level custom canonical | `acat_canonical` | Relative values resolve against `PHPWCMS_URL`; a value starting `{SITE}` expands to the install URL first. |
| Auto canonical | default, unless `canonical_off` | Points at the article alias, or at the level alias when the level shows exactly one article. |
| Off | `$phpwcms['canonical_off']` | No canonical link. |

With `force301_2struct` the auto canonical turns into a real 301 redirect to the level alias where the redirect is allowed (`acat_disable301` excludes a level, and POST requests and content-part-paginated views keep their URL). The level redirect to its alias runs earlier for plain level URLs (`force301_2struct`) and for article URLs with alias (`force301_id2alias`).

## Open Graph and social sharing

When `$phpwcms['set_sociallink']['render']` is enabled and the page allows sharing (`acat_opengraph` / `article_opengraph` per level or article), the renderer adds:

| Meta | Value |
| ---- | ----- |
| `og:type` | `website` on level pages, `article` on article pages. |
| `og:title` | sanitized Open Graph title — article title, level name or layout title, `{TAG}…{/TAG}` pairs cleaned. |
| `og:url` | page URL without the GET values `phpwcms_output_action`, `print`, `phpwcms-preview`, `unsubscribe`, `subscribe`. |
| `og:site_name` | layout title when set. |
| `og:locale` | `$content['opengraph']['locale']` when a frontend script sets it, otherwise `get_locale_canonical()` of `{DOCTYPE_LANG}` with `$phpwcms['default_lang']` and `en` as fallbacks. |
| `og:description` | article description when set. |
| `og:image` | shop, news or article image in `$phpwcms['opengraph_imagesize']` (default `1200x630x1`), else `template/img/opengraph-default.png` when present. |

Twitter / X Cards: `twitter:card` defaults to `summary_large_image` when the page has an image, else `summary`. A template comment `<!--TWITTER:summary-->`, `<!--TWITTER:summary_large_image-->`, `<!--TWITTER:player-->` or `<!--TWITTER:app-->` overrides it; so does `$content['opengraph']['twitter:card']`. `twitter:site` comes from `$phpwcms['twitter_site']`, `twitter:creator` from the article author name when it starts with `@`.

## Generator meta

`<meta name="generator" content="phpwcms …">` and the `X-phpwcms-Release` header render unless `$phpwcms['disable_generator']` is set or the installation runs whitelabeled (`is_whitelabel()`).

## Base href and viewport

`index.php` adds `<base>` when `$phpwcms['base_href']` is set (`true` uses the install URL; a path uses that path), and `<meta name="viewport" …>` from `$phpwcms['viewport']`. The doctype pass also writes the content type meta from `PHPWCMS_CHARSET`, or the HTML5 short form `<meta charset="…">` in doctype mode 3 (`<!DOCTYPE html>`).

## Adding meta from scripts

```php
include/inc_lib: set_meta($name, $content, $type, $return, $allow_multiple)
```

| Parameter | Default | Description |
| --------- | ------- | ----------- |
| `name` | — | Meta name (`description`) or property (`og:image`). |
| `content` | — | Value, HTML escaped on output. |
| `type` | FALSE | `FALSE` = `name`, `property` / `prop` = `property`, `itemprop`, `http-equiv` (or TRUE), or any attribute name. |
| `return` | FALSE | TRUE returns the built `meta` string instead of registering it in head code. |
| `allow_multiple` | FALSE | TRUE appends instead of overwriting — used for several `og:image` values. |

## Related

- [Date and time](07-date-time.md) — `{DOCTYPE_LANG}` selects localized date names
- [Site and server tags](02-site.md) — `{SITE}` inside a canonical value expands to the install URL
- [Login functions](08-login-functions.md) — the same head channel carries login redirects