---
title: Content part pagination
description: Split an article into pages with the content part paginate tags.
sidebar_position: 5
---

# Content part pagination

> Source: `include/inc_front/content.article.inc.php` (page collection), `include/inc_front/content.func.inc.php` (navigation render)

Content part pagination turns one article into several pages: each content part belongs to a paginate page, and the reader moves with numbered links or prev/next links. The tags below only render when the article uses pagination; when the article has no second page the whole `CP_PAGINATE` section of the template is removed.

## Syntax

```
{CP_PAGINATE}
{CP_PAGINATE_PREV}
{CP_PAGINATE_NEXT}
{CP_PAGINATE_CLASS}
[CP_PAGINATE_MENU]…[/CP_PAGINATE_MENU]
[CP_PAGINATE_MENU:menu parameters]
```

Place them inside the article summary template (`template/inc_default/article_summary.tmpl`, a custom `template/inc_cntpart/articlesummary/article/` template, or the paginate variant in `articlesummary/article/paginate/`). Guard them with the matching bracket sections, for example `[CP_PAGINATE_PREV]…{CP_PAGINATE_PREV}…[/CP_PAGINATE_PREV]`, so markup disappears when a direction does not exist.

## How pages are collected

For each content part the backend stores a paginate page number (`acontent_paginate_page`) and an optional title (`acontent_paginate_title`). Page `0` and page `1` are the same page; content parts without a pager belong to it. The renderer collects distinct pages only from content parts in the `CONTENT` block that are visible and within their live/kill window. The requested page arrives as the part after the first dash of `aid`, so `/index.php?aid=12-2` shows page 2 of article 12.

While a page greater than 1 is shown, the article summary template falls back to the paginate variant when one exists — `template/inc_cntpart/articlesummary/article/paginate/<selected template>`, `template/inc_default/article_summary_paginate.tmpl`, else a built in fallback with previous/next/number links.

## Tags

| Tag | Default | Description |
| --- | ------- | ----------- |
| `{CP_PAGINATE}` | — | One link per page, label = page number. Link classes from `cp-paginate-link` / `cp-paginate-link-active`. Guard with `[CP_PAGINATE]…[/CP_PAGINATE]`. |
| `{CP_PAGINATE_PREV}` | — | Absolute URL of the previous page, empty on page 1. |
| `{CP_PAGINATE_NEXT}` | — | Absolute URL of the next page, empty on the last page. |
| `{CP_PAGINATE_CLASS}` | — | Value of the class `cp-paginate-link`, convenience for templates outside the standard list rendering. |

Numbered links are built from `$template_default['attributes']['cp-paginate']`: each link is wrapped by `link-prefix` / `link-suffix` and its label by `value-prefix` / `value-suffix`. Active-page handling uses the `cp-paginate-link-active` class.

When `$phpwcms['disable_next_prev']` is unset, the renderer also writes `rel="prev"` / `rel="next"` `<link>` tags into the document head for the current page.

## CP_PAGINATE_MENU

`[CP_PAGINATE_MENU]…[/CP_PAGINATE_MENU]` renders one link per page using each page's `acontent_paginate_title` as the label (fall back: `#n` page number). Custom parameters override the defaults:

```
[CP_PAGINATE_MENU:item_prefix|item_suffix|active_class|hide_active]
```

| # | Parameter | Default | Description |
| - | --------- | ------- | ----------- |
| 1 | `item_prefix` | `link-prefix` | HTML before each item `<a>`. |
| 2 | `item_suffix` | `link-suffix` | HTML after each item `</a>`. |
| 3 | `active_class` | `cp-paginate-link-active` | Class for the active page link; empty removes classes. |
| 4 | `hide_active` | 0 | `1` drops the active page from the menu instead of marking it. |

With custom parameters the outer wrap (`wrap-prefix` / `wrap-suffix`) is dropped; without them the defaults apply, so the menu renders as a `<ul class="pagination">` list by default.

## Output

```html
<!-- template with defaults -->
[CP_PAGINATE]{CP_PAGINATE}[/CP_PAGINATE]

<!-- output; the requested page arrives as aid=12-2 -->
<li class="page-item"><a href="https://example.com/index.php?news" class="page-link active">1</a></li>
<li class="page-item"><a href="https://example.com/index.php?aid=12-2" class="page-link">2</a></li>
```

Page 1 links back to the page alias — article alias when the article has one, else level alias, else `aid=<id>`; all other pages use `aid=<article id>-<page number>` even when the article has an alias.

Class and attribute names come from the default configuration `include/config/conf.template_default.inc.php`: classes `page-link`, `page-link active`, `page-link disabled` and the `cp-paginate` attributes `wrap-prefix` (`<ul class="pagination">`), `wrap-suffix` (`</ul>`), `link-prefix` (`<li class="page-item">`), `link-suffix` (`</li>`), empty `value-prefix` / `value-suffix` / `href-disabled`. A template can overwrite them via its template default include.

Also handled at this stage: `<!--CP_PAGINATE_START//-->…<!--CP_PAGINATE_END//-->` comment markers around the pagination block are stripped while pagination works and the complete section disappears when the article has no pages. The article's canonical `<link>` uses the paginated URL (`aid=<id>-<page>`, see [meta and head tags](06-meta.md)).

## Related

- [Article tags](04-article.md) — the list level equivalent with `{PREV}` / `{NEXT}` / `{NAVI}`
- [SHOW_CONTENT](03-show-content.md) — CP modes can embed a paginated article's content parts