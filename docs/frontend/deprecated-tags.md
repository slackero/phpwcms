---
title: Deprecated tags
description: Replacement tags that render only when enable_deprecated is active in config.inc.php.
sidebar_position: 3
---
# Deprecated tags

> Source: `include/inc_front/deprecated.inc.php`, `include/inc_front/content.func.inc.php` (deprecated branch), `include/config/dist.conf.inc.php` (`enable_deprecated`, `gt_mod`)

The tags on this page survive only behind the compatibility switch:

```php
$phpwcms['enable_deprecated'] = true; // config.inc.php
```

With the switch off, `content.func.inc.php` skips the whole deprecated branch and never loads `deprecated.inc.php` — every tag below stays unrendered in the page source. Do not use these tags in new templates; the maintained equivalents live under [NAV_LIST_UL](replacement-tags/01-nav-list-ul.md).

## Navigation tags

| Tag | Syntax | Renders by |
| --- | ------ | ---------- |
| Row navigation | `{NAV_ROW}` | `nav_level_row(0)` — all levels of the level tree, `|` separated from `template_default['nav_row']` settings |
| Row navigation variant | `{NAV_ROW:<start-id-or-CURRENT>:<show-home>}` | Same renderer, `show_home` = 0/1 |
| Level list | `{NAV_LIST}` | Normalized to `{NAV_LIST:0}` |
| Level list variant | `{NAV_LIST:<top-level>:<class>}` | `nav_list_struct()` — nested `<ul>` list with `listActive<depth>` classes |
| Top level list | `{NAV_LIST_TOP}`, `{NAV_LIST_TOP:<home-label>:<class>}` | `css_level_list()` with parent level on |
| Current level list | `{NAV_LIST_CURRENT}`, `{NAV_LIST_CURRENT:<level>:<back-label>:<class>}` | `css_level_list()` scoped to the current level, default class `list_level` |
| Simple table nav | `{NAV_TABLE_SIMPLE}` | `nav_table_simple_struct()` — one-column `<table>` of all levels |
| Table nav | `{NAV_TABLE_COLUMN}`, `{NAV_TABLE_COLUMN:<level>}` | `nav_table_struct()` — table navigation honoring the `template_default['nav_table_struct']` defaults, loads `frontend.js` |

These renderers use the legacy `$content['cat_path']` breadcrumb and the `nav_row` / `nav_table_struct` option blocks in `include/config/conf.template_default.inc.php`.

## Image placeholders

`html_parser_deprecated()` rewrites markup style placeholders of the 1.x era:

| Tag | Result |
| --- | ------ |
| `{RANDOM:path}` | ` <img>` of a random image found under `path` (`.gif`, `.jpeg`, `.jpg`, `.png`); `{RANDOM:SRC:path}` returns only the absolute image URL |
| `{RANDOM_GIF:path}` | `<img src="img/random_image.php?type=0&amp;imgdir=path">` |
| `{RANDOM_JPEG:path}` | Same with `type=1` |
| `{RANDOM_PNG:path}` | Same with `type=2` |
| `{IMAGE:file}` | `<img src="picture/file">` |
| `{IMAGE_LEFT:file}` | Same, floated left |
| `{IMAGE_RIGHT:file}` | Same, floated right |
| `{IMAGE_CENTER:file}` | Same inside a centered `<div>` |

## Style tag

`{GT:<style>}…{/GT}` wraps its content in `<span class="gt-<style>">`. It has its own gate and renders when `$phpwcms['gt_mod']` is on, independent of `enable_deprecated`.

## Related legacy behavior

Not gated by `enable_deprecated`, but rewritten unconditionally in the final cascade: old `<img src="image_resized.php?...">` markup converts to the `img/cmsimage.php` (or `im/`) schema. Keep that in mind when re-cutting old 1.x markup.

## Related

- [Page rendering](page-rendering.md#replacement-cascade) — where the deprecated branch runs
- [NAV_LIST_UL](replacement-tags/01-nav-list-ul.md)