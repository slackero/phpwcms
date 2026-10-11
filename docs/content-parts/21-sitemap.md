---
title: Sitemap
description: Render the structure levels and their sitemap-flagged articles as nested unsorted lists.
sidebar_position: 21
---
# Sitemap (content part 19)

> Source: `include/inc_lib/content/cnt19.readform.inc.php`, `include/inc_tmpl/content/cnt19.inc.php`, `include/inc_front/content/cnt19.article.inc.php`, `include/inc_front/front.func.inc.php` (`build_sitemap()`, `build_sitemap_articlelist()`)

Walks the visible structure levels below a start level and optionally lists each level's articles. Output is fixed nested `<ul>/<li>` markup — no template file.

## Backend form

Fields as they appear in the content part dialog, top to bottom.

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Before | textarea | *(empty)* | `before`: raw HTML prepended to the sitemap. |
| After | textarea | *(empty)* | `after`: raw HTML appended. |
| Category image | text (URL) | *(empty)* | `catimg`: used as `list-style-image` on every category `<li>`. |
| Article image | text (URL) | *(empty)* | `articleimg`: `list-style-image` on every article `<li>`. |
| Start level | select | Index (0) | `startid`: structure level where the walk begins. |
| Display | radio | Structure only | `display`: 0 lists only structure levels; 1 also lists each level's articles. |
| Without parent | checkbox | off | `without_parent`: do not wrap the start level itself as the first `<li>`. |
| Category class | text | *(empty)* | `catclass`: CSS class on the category `<ul>` tags. |
| Article class | text | *(empty)* | `articleclass`: CSS class on the article `<ul>` tags. |
| Class counter | radio | None | `classcount`: append the nesting depth number to both classes. |

## Rendering

- The start level is only rendered when it is not flagged "no sitemap" (`acat_nositemap`); child levels pass the same check plus `_getStructureLevelDisplayStatus()`: the level must directly follow the requested start level in the tree, not be hidden (hidden-with-keyword levels excepted), and not be registration-only for guests.
- Structure markup: every level becomes `<li style="list-style-image:url(catimg)"><a href="…">name</a>` followed by the optional article list and the child `<ul>`. Level names are HTML-escaped; the class on each child `<ul>` is `catclass` with the depth number appended when Class counter is set.
- Level links use the level alias or `id=<level id>`; a level redirect (`acat_redirect`) links straight to its target with the redirect target attribute.
- Article list (Display = structure + articles): selects `article_id, article_title, article_alias` where the article summary's "Sitemap" checkbox is set (`article_nositemap = 1`), the article is active and not deleted, and the visibility window applies outside preview mode. Sorted by the level's article order (`acat_order`, `get_order_sort()`, led by `article_priorize DESC`) — the same table as in Article menu (cp 15). The list only renders when more than one article matches (`count > 1`), wrapped in `<ul class="articleclass…">`.
- Article links use the article alias or `aid=<article id>`; titles are HTML-escaped.
- The whole block is wrapped in `<div class="…" id="…">` when the content part carries an attribute class or ID, and the content part title/subtitle render through the standard `headline()` function.

## Storage

Serialized array `sitemap` in `phpwcms_articlecontent.acontent_form` with the keys `before`, `after`, `catimg`, `articleimg`, `display`, `catclass`, `articleclass`, `classcount`, `startid`, `without_parent`. No `acontent_template` value is used.

## Related

- Simple article list of one level: [Article menu (cp 15)](18-article-menu.md)
- Teaser lists with templates: [Teaser/link article (cp 8)](17-teaser-link-article.md)