---
title: Site and server tags
description: Tags that resolve to site URLs, template paths and request values.
sidebar_position: 2
---

# Site and server tags

> Source: `include/inc_front/content.func.inc.php` (page cascade), `include/inc_front/front.func.inc.php` (`html_parser()`), `include/inc_lib/general.inc.php` (`replaceGlobalRT()`)

These tags stand for installation-wide paths and request values. They work everywhere the replacement cascade runs — layout templates, content parts, admin header/footer code.

## Syntax

```
{SITE}
{TEMPLATE}
{PHPWCMS_URL}
{PHPWCMS_TEMPLATE}
{IP}
{PHPWCMS_RESIZE_IMAGE}
{LAZY_LOADING}
{RSSIMG}
```

All are plain tags without parameters.

## Parameters

| Tag | Resolves to | Description |
| --- | ----------- | ----------- |
| `{SITE}` | `PHPWCMS_URL` | Absolute installation URL, protocol aware. See [PHPWCMS_URL](#phpwcms_url). |
| `{TEMPLATE}` | `TEMPLATE_PATH` | URL path of the current template directory, for example `template/`. |
| `{PHPWCMS_URL}` | `PHPWCMS_URL` | Same value as `{SITE}`; see [PHPWCMS_URL](#phpwcms_url). |
| `{PHPWCMS_TEMPLATE}` | `TEMPLATE_PATH` | Same value as `{TEMPLATE}`. |
| `{IP}` | visitor IP | Client IP, anonymized in GDPR mode. See [GDPR mode](#gdpr-mode). |
| `{PHPWCMS_RESIZE_IMAGE}` | image resize path | `img/cmsimage.php` or, with URL rewriting, `im`. |
| `{LAZY_LOADING}` | loading attribute string | `loading="…" decoding="async"` from `$phpwcms['lazy_loading']` (`lazy`, `eager` or `auto`, default `lazy`); empty when disabled. Content parts append it to their `<img>` tags. |
| `{RSSIMG}` | RSS icon markup | Value of `$template_default['rss']['image']`, an inline SVG feed icon. |

`{RSSIMG}` belongs to the [RSS group](09-rss.md) and is listed here only for completeness.

## Output

`{SITE}` and `{TEMPLATE}` are replaced twice so that both the final page and the text markup layer see them:

- In the main cascade (`content.func.inc.php`), before navigation rendering.
- Inside `html_parser()` after content part text is parsed, so a `{SITE}` inside article text also resolves.

```html
<!-- template source -->
<a href="{SITE}page.html"><img src="{TEMPLATE}img/logo.png" alt="" /></a>

<!-- output -->
<a href="https://example.com/page.html"><img src="template/img/logo.png" alt="" /></a>
```

{LAZY_LOADING} is a single pass at the end of the cascade (`str_replace`), because content parts embed it into image tags while rendering.

<a id="phpwcms_url"></a>
## PHPWCMS_URL

`{SITE}` and `{PHPWCMS_URL}` carry the same value, but run on different stages:

| Tag | Replaced in | Runs for |
| --- | ----------- | -------- |
| `{SITE}` | page cascade and `html_parser()` | every frontend request |
| `{PHPWCMS_URL}` | `replaceGlobalRT()` | newsletter mail text (content parts 12/23) and `verify.php` output |

Use `{PHPWCMS_URL}` in templates that ship both to the browser and into mail text, for example a newsletter confirmation template. Otherwise use `{SITE}`.

## GDPR mode

With `$phpwcms['enable_GDPR']` set (constant `PHPWCMS_GDPR_MODE`), `{IP}` returns `getAnonymizedIp()` — the second half of the address is zeroed (IPv4 `76.97.0.0` style) — instead of the raw client address from `getRemoteIP()`. The tag only resolves inside `replaceGlobalRT()`, so it is available in newsletter mail text and at `verify.php`, not in the main page cascade.

## Not present in 2.x

Older releases and third party documentation list additional "site" tags that no longer exist in this code base. Do **not** use them; the table shows what replaced them:

| Removed tag | Replacement |
| ----------- | ----------- |
| `{SITE_NAME}`, `{SITE_LEVELKEY}`, `{SITE_LINK_*}` | plain template text; level names come from the structure, navigation from [NAV_LIST_UL](01-nav-list-ul.md) |
| `{PHPWCMS_ROOT}` | PHP constant, not a replacement tag |
| `{PHPWCMS_USER_IP}`, `{USER_AGENT}`, `{HTTP_DNT}` | not handled; use device conditionals `<!--if:…-->` or a frontend render script |
| `{PHPWCMS_DOMAIN}` | not handled |

## Related

- [NAV_LIST_UL](01-nav-list-ul.md) — navigation built from the level tree
- [Meta and head tags](06-meta.md) — page title and canonical values also use absolute URLs
- [Deprecated tags](../deprecated-tags.md) — the legacy image and navigation tags