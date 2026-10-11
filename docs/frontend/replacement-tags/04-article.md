---
title: Article tags
description: Article level replacement tags and the article summary template fields.
sidebar_position: 4
---

# Article tags

> Source: `include/inc_front/content.func.inc.php` (article level pass), `include/inc_front/content.article.inc.php` (detail renderer), `include/inc_front/front.func.inc.php` (`render_cnt_date()`, `setPageTitle()`)

When the request shows an article (`aid` or an article alias), the renderer stores the article record in `$content` and adds an article specific pass to the cascade. The tags below resolve on the whole page — layout template, header/footer and content part output.

## Syntax

```
{AUTHOR}
{CATEGORY}
{DATE_ARTICLE}
{TIME_ARTICLE}
{CURRENT_ARTICLEID}
{CURRENT_CATEGORYID}
```

## Parameters

| Tag | Default | Description |
| --- | ------- | ----------- |
| `{AUTHOR}` | article author | `article_username` of the shown article, guard with `[AUTHOR]…[/AUTHOR]`; empty in list mode. |
| `{CATEGORY}` | current level name | Name of the article's structure level in article view, name of the current level in list mode. |
| `{DATE_ARTICLE}` | — | Article date formatted with `$template_default['date']['article']`; see [date tags](07-date-time.md). |
| `{TIME_ARTICLE}` | — | Article time formatted with `$template_default['time']['short']`. |
| `{CURRENT_ARTICLEID}` | 0 | Numeric ID of the article being rendered, 0 on level pages. |
| `{CURRENT_CATEGORYID}` | 0 | Numeric ID of the current structure level. |

In list mode (no article detail) `{AUTHOR}` renders empty and the date tags fall back to the current time.

Sections follow the guard pattern of `render_cnt_template()`: `[AUTHOR]…[/AUTHOR]` keeps content only when the value exists, `[AUTHOR_ELSE]…[/AUTHOR_ELSE]` keeps it otherwise.

## Date fields of the article

`render_cnt_date()` receives the article date plus the live and kill dates of the shown article and renders the parametric [date tags](07-date-time.md) page wide:

```
{DATE:FORMAT}, {NOW:FORMAT}, {LIVEDATE:FORMAT}, {KILLDATE:FORMAT}
```

`[LIVEDATE]…[/LIVEDATE]` and `[KILLDATE]…[/KILLDATE]` keep their inner content only while the live or kill date is set, so a template can hide a date row for timeless articles.

## Article summary template fields

The article detail renders through `template/inc_default/article_summary.tmpl` or a custom `template/inc_cntpart/articlesummary/article/` template. The detail renderer fills:

| Field | Content |
| ----- | ------- |
| `{TITLE}` / `{SUB}` | article title / subtitle |
| `{SUMMARY}` | summary text, suppressed when the article hides the summary |
| `{IMAGE}` | summary image `<img>` element |
| `{ZOOMIMAGE}` | zoom link markup when configured |
| `{IMAGE_URL}` `{IMAGE_TARGET}` | image target URL and target from the caption info |
| `{THUMB_*}` / `{IMAGE_*}` | thumbnail and zoom image values (name, rel, abs, width, height, ext) |
| `{IMAGE_ID}` `{IMAGE_HASH}` `{IMAGE_EXT}` | original file identity |
| `{CAPTION}` `{CAPTION_SUPPRESS}` `{ALT}` `{IMAGE_TITLE}` `{COPYRIGHT}` | caption values from the file info |
| `{EDITOR}` | author name |
| `{MORE}` | a single space while the article has a read-more link |
| `{CLASS}` | CSS class from `*CSS-class*` keywords and article meta |
| `[SYSTEM]…[/SYSTEM]` | `SYSTEM` block content parts of the article |

The list rendering used by [SHOW_CONTENT](03-show-content.md) adds fields on top: `{SUMMARY:n}` word capped summary, `{CATEGORYID}`, `{ARTICLELINK}` / `{TARGET}`, `{ARTICLEID}`, `{MORE}` as the full read-more markup (`top_readmore_link`), and `{SPACE}` between entries.

The article list navigation is a separate template string (`$template_default['article_paginate_navi']`) placed at list top or bottom; inside it `{PREV:TEXT}`, `{NEXT:TEXT}`, `{NAVI:123}` or `{NAVI:1-3}` build the page links and `{POS}` tells whether it sits on top or bottom. Details for the analogous content part pagination live in [content part pagination](05-cp-paginate.md).

## Page title

`content.article.inc.php` builds `$content['pagetitle']` with `setPageTitle()`: layout title, article level name and article title merge in the order configured by `pagelayout['layout_title_order']` (0-14, default 0 = layout title, level, article), joined by `pagelayout['layout_title_spacer']` (default ` | `). A custom title per article or level (`article_pagetitle` / `acat_pagetitle`) overrides the composed one. See [meta and head tags](06-meta.md).

## Not present in 2.x

`{ARTICLE:FIELD}` variants known from older documentation do not exist in this code base. Article fields reach templates through the summary template fields above, or through article meta values — description and keywords — described in [meta and head tags](06-meta.md).

## Related

- [SHOW_CONTENT](03-show-content.md) — reuses the same list template fields
- [Date and time](07-date-time.md) — full parameter reference of the `{DATE:…}` family
- [NAV_LIST_UL](01-nav-list-ul.md) — article menu variant