---
title: SHOW_CONTENT
description: Embed article summaries or rendered content parts from any other article into the current page.
sidebar_position: 3
---

# SHOW_CONTENT

> Source: `include/inc_front/ext.func.inc.php` (`showSelectedContent()`), `include/inc_front/front.func.inc.php` (`list_articles_summary()`)

`{SHOW_CONTENT}` pulls rendered content into the current page: whole article summary teasers (`AS*` modes) or the output of single content parts (`CP*` modes). It evaluates once per assembled page, so the embedded content cannot contain another `{SHOW_CONTENT}`.

## Syntax

```
{SHOW_CONTENT:MODE,id[,id[,...]]}
```

`MODE` selects what to fetch and how to sort; the remaining comma separated numbers are article IDs (for `AS*`) or content part IDs (for `CP*`). The tag dispatches with `/\{SHOW_CONTENT:(.*?)\}/`.

## Parameters

| # | Parameter | Default | Description |
| - | --------- | ------- | ----------- |
| 1 | mode, optional `|count` / `|template` | — | Mode table below; the part before the first comma may carry `|count` and/or `|template` (AS modes only). |
| 2…n | id list | — | Article IDs (`AS*`) or content part IDs (`CP*`). Non numeric entries are dropped; only numeric IDs greater than 0 load. |

The `|count` / `|template` suffixes (AS modes): `AS|10` limits output to 10 articles, `AS|10|custom.tmpl` also names a list template, `AS|custom.tmpl` names the template only. A template name is accepted when a file `template/inc_cntpart/articlesummary/list/<name>` exists, or the literal `default`.

## AS modes — article summaries

`AS` returns article summaries rendered through a list template. Sorting suffixes (code comment: "L = live, K = kill, C = change date, R = random, T = keyword"):

| Mode | Sort | Note |
| ---- | ---- | ---- |
| `AS` | no explicit sort, order of the given IDs | |
| `AS*P` appended `P` | same as the base mode | adds `article_priorize DESC` in front of the sort column |
| `ASL` / `ASLD` | `article_begin ASC` / `DESC` | live date |
| `ASK` / `ASKD` | `article_end ASC` / `DESC` | kill date |
| `ASC` / `ASCD` | `article_tstamp ASC` / `DESC` | change date |
| `AST` / `ASTD` | `article_keyword ASC` / `DESC` | keyword |
| `ASR` | `RAND()` | random |

> **Note:** `ASCD` is broken in the current source: the order term references the column `article_tspamp` instead of `article_tstamp`, so a page requesting sorted-by-change-date output fails at the database level. Use `AS` plus a frontend render script workaround until this is fixed.

### AS selection options

Instead of an ID list, the second parameter may be a keyword:

```
{SHOW_CONTENT:AS,new}
{SHOW_CONTENT:AS,random}
{SHOW_CONTENT:AS|10,new}
{SHOW_CONTENT:AS,related|AND,keyword1[,keyword2,...]}
```

| Keyword | Behavior |
| ------- | -------- |
| `new` (default) | Most recent articles, `article_created DESC`; limit the count with the `|count` suffix in the mode block. |
| `random` | Random pick from the matching set (`RAND()`); same count handling. |
| `related` | Matches `article_keyword LIKE %KEY%` for every following keyword; `|AND` requires every keyword, `|OR` (default) any. The current article is excluded automatically. |

All AS requests automatically exclude `article_deleted=0`, respect the visibility mode and the live/kill window, and drop the current article ID.

## CP modes — content parts

| Mode | Behavior |
| ---- | -------- |
| `CP` | Renders one content part by its CP ID. Skips CPs assigned to the `CPSET` or `SYSTEM` blocks. |
| `CPA` | Renders the article's own content parts (IDs = article IDs), excluding `CPSET`/`SYSTEM`, in manual sort order. |
| `CPAD` | As `CPA`, descending. |
| `CPS` | Renders one content part by CP ID, `SYSTEM` block only. |
| `CPAS` / `CPASD` | As `CPA`, but only `SYSTEM` block content parts, ascending / descending. |

CP output re-runs the full content part renderer (`include/inc_front/content/cnt<N>.article.inc.php` or a module renderer), with the spacing, top link and frontend edit link each content part configured. Alias content parts (type 24) resolve their target, and module content parts (type 30) render only when the module is active. Inside CP templates you can print the content part ID with `{CPID}` or `[%CPID%]`.

Access control applies: content parts where `acontent_granted` denies the current frontend login state are skipped.

## Output

AS output comes from `list_articles_summary()`. The default template reads `template/inc_default/article_summary_list.tmpl`; the mode block may name another one. The template fields:

| Field | Content |
| ----- | ------- |
| `{TITLE}` / `{SUB}` | article title / subtitle, guarded by `[TITLE]`, `[SUB]` |
| `{SUMMARY:n}` | summary text, capped at `n` words (negative value: characters) |
| `{CATEGORYID}` | article level ID |
| `{CLASS}` | article class from `*CSS-class*` keywords and article meta |
| `{IMAGE}` | list image `<img>` element |
| `{ZOOMIMAGE}` | zoom/link popup markup when configured |
| `{THUMB_NAME}` `{THUMB_REL}` `{THUMB_ABS}` `{THUMB_WIDTH}` `{THUMB_HEIGHT}` | generated thumbnail values |
| `{IMAGE_NAME}` `{IMAGE_REL}` `{IMAGE_ABS}` `{IMAGE_WIDTH}` `{IMAGE_HEIGHT}` | zoom image values |
| `{IMAGE_ID}` `{IMAGE_HASH}` `{IMAGE_EXT}` | original file values |
| `{CAPTION}` `{CAPTION_SUPPRESS}` `{ALT}` `{IMAGE_TITLE}` `{COPYRIGHT}` | image caption parts from the file info |
| `{ARTICLELINK}` `{TARGET}` | link target of the article, target attribute |
| `{EDITOR}` `{ARTICLEID}` | author name, article ID |
| `{MORE}` | "read more" markup, `template_default['top_readmore_link']` |
| `{SPACE}` | set for every entry after the first |
| `[SYSTEM]…[/SYSTEM]` | renders `SYSTEM` block content parts of the article (detail/list scope) |
| date fields | `{DATE:…}`, `{NOW:…}`, `{LIVEDATE:…}`, `{KILLDATE:…}` see [date tags](07-date-time.md) |

Pagination: `list_articles_summary()` also renders the article list navigation when the level paginates. The list top/bottom placement and a global tag are configured with `$template_default['article_paginate_show']`; with `rt{RT}` the navigation is exposed to the page as the global tag `{RT}`.

## Examples

```
{SHOW_CONTENT:CP,1043}
{SHOW_CONTENT:AS,12,17,42}
{SHOW_CONTENT:ASLD|8,12,17,42}
{SHOW_CONTENT:AS|teaser.tmpl,related|AND,NEWS,SALES}
{SHOW_CONTENT:AS|5,new}
```

## Related

- [Article tags](04-article.md) — the same summary fields on the article detail page
- [Date and time](07-date-time.md) — date fields inside AS templates
- [NAV_LIST_UL](01-nav-list-ul.md) — article menu variant covers a similar teaser use case