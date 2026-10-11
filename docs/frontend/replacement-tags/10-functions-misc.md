---
title: Functions and misc tags
description: Article lists, related and keyword links, external URL include, breadcrumb, browse and search tags.
sidebar_position: 10
---

# Functions and misc tags

> Source: `include/inc_front/content.func.inc.php` (dispatch), `include/inc_front/front.func.inc.php` (`get_new_articles()`, `get_related_articles()`, `get_keyword_link()`, `get_index_link_*()`, `include_url()`, `breadcrumb()`)

This page collects the replacement tags outside the groups with dedicated pages. All of them run in the late cascade, after [SHOW_CONTENT](03-show-content.md) and navigation.

## NEW and RELATED

### {NEW:max[,category]}

> Source: `include/inc_front/content.func.inc.php` regex `/\{NEW:(\d+):{0,1}(\d+){0,1}\}/`, `front.func.inc.php` `get_new_articles()`

Lists the newest articles of the installation or of one category.

| # | Parameter | Default | Description |
| - | --------- | ------- | ----------- |
| 1 | max | — | Maximum number of links; 0 lists without limit. |
| 2 | category | *(empty)* | Structure level ID to restrict the list to. |

Output comes from `$template_default['news']`: wrapper `before` (`<div class="news">`) / `after`, per link `link_before` (`<p>`) / `link_after` (`</p>`), optional `link_symbol`, `link_target`, title cut at `link_length` with `cut_title_add`, and — when `date_format` is set — a date prefix wrapped by `date_before` / `date_after` (`<span class="datelink">…</span>`, separator ` - `). Sorting follows `$template_default['news']['sort_by']`: `cdate` (creation), `ldate` (live date), `kdate` (kill date), otherwise change date. Articles without a read-more link are skipped; redirected articles link to their redirect target.

### {RELATED:max} and {RELATED:max:keywords}

> Source: `include/inc_front/content.func.inc.php`, `front.func.inc.php` `get_related_articles()`

Lists articles whose `article_keyword` matches.

| # | Parameter | Default | Description |
| - | --------- | ------- | ----------- |
| 1 | max | — | Maximum number of links; 0 without limit. |
| 2 | keywords | current article keywords | Comma separated list; `ALLKEYWORDS` expands to the page keyword collection; a leading `-` excludes a keyword; keywords shorter than 3 characters drop out. |

Matching is `LIKE %KEY%` against every keyword; visible mode, deletion and live/kill window apply, the current article is excluded. Output uses `$template_default['related']`: wrapper, link prefix/suffix, symbol, target, title cut — analogous to `{NEW}`. Sorting follows `$template_default['related']['sort_by']`: `title_asc`/`title_desc`, `ldate_asc`/`ldate_desc`, `kdate_asc`/`kdate_desc`, `cdate_asc`/`cdate_desc`, default change date descending.

## {KEYWORD:keyword[,keyword]}

> Source: `front.func.inc.php` `get_keyword_link()`, regex `/\{KEYWORD:(.*?)\}/`

Matches articles whose keyword field contains `*keyword*` markers and returns a link. One matching article renders as a single `<a>` with the keyword list as the link text. Several matches render the keyword as plain text followed by numbered links in brackets: `Keyword [1][2]`. No match renders the keywords as text. The output wraps with `$template_default['article']['keyword_before']` / `keyword_after`.

## {BROWSE:UP|NEXT|PREV:text[,flag]}

> Source: `front.func.inc.php` `get_index_link_up()`, `get_index_link_next()`, `get_index_link_prev()`

| Tag | Behavior | Flag |
| --- | -------- | ---- |
| `{BROWSE:UP:text}` | Link to the parent structure level. | — |
| `{BROWSE:NEXT:text,flag}` | Link to the next article of the current level. | `1` continues into sub levels and then to the next level of the same parent depth. |
| `{BROWSE:PREV:text,flag}` | Link to the previous article of the current level. | same continuation behavior |

The `text` parameter is the link text (`UP`, `NEXT`, `PREV` when empty). When no target exists the text renders as plain text; with a target it becomes `<a href="…">text</a>`. Hidden levels without alias use `id=<level>` URLs.

## {URL:address[,CACHE=seconds]}

> Source: `content.func.inc.php` regex `/\{URL:(.*?)\}/i`, `front.func.inc.php` `include_url()`

Includes an external HTML page — but only the part between `<body>` and `</body>`. Relative `href` / `src` / `action` attributes become absolute, scripts and styles are filtered (`htmlfilter_sanitize`), the charset converts to the installation charset, and PHP markers are HTML escaped before insertion.

```
{URL:https://example.com/news.html CACHE=3600}
```

With `CACHE=seconds` the content is stored in `content/tmp/<md5>-url` and reused until the timeout expires. Remote loading writes no content when the request fails. Do not confuse this with the plain `{URL}` field inside download templates — that one belongs to the [content parts reference](../../content-parts/index.md).

## {BREADCRUMB}

> Source: `content.func.inc.php` regex `/\{BREADCRUMB:?(\-?\d+){0,1}(,[01]){0,1}\}/`, `front.func.inc.php` `breadcrumb_wrapper()`, `breadcrumb()`

Renders the path of structure levels from the current level up to the root.

| # | Parameter | Default | Description |
| - | --------- | ------- | ----------- |
| 1 | end level | 0 | Level ID where the path stops (inclusive). A negative value never stops early and walks to root. |
| 2 | category only | 0 | `1` omits the article title — without it, article pages name the article after the path. |

Output: level names in path order, linked except the current position; hidden level rules and the `acat_breadcrumb` setting of each level decide whether a level appears and whether it links. The active part carries `breadcrumb_active_prefix` / suffix (default `<strong aria-current="page">`), non linking entries wrap with `breadcrumb_nolink_prefix` / suffix, parts separate by `$template_default['breadcrumb_spacer']` (default ` <span class="breadcrumb-spacer">&gt;</span> `).

## Search form placeholders

> Source: `content.func.inc.php` (search_input pass), `include/inc_front/content/cnt13.article.inc.php`

The search content part form uses obfuscated placeholders the cascade rewrites late:

| Placeholder | Replaced by |
| ----------- | ----------- |
| `###search_input_field###` | `search_input_field` — the real input name. |
| `###search_input_value###` | Current search words from the request, the value attribute of the input. |
| `###search_input_action:<article id>###` | URL of the article that carries the search content part; resolves only while that article exists and is active. |

Search result highlighting works with the markers `<!--SEARCH_HIGHLIGHT_START//-->` and `<!--SEARCH_HIGHLIGHT_END//-->` — with a `highlight` GET parameter the text between them gets wrapped by the configured highlight markup, otherwise the markers are removed.

## Other page wide tags

| Tag | Description |
| --- | ----------- |
| `{CURRENT_URL}` | Absolute page URL; only the print/PDF layout template applies it. |
| `[PRINT]…[/PRINT]` | Print link, `print=1`, class `link-print` |
| `[PRINT_PDF]…[/PRINT_PDF]` | PDF link, `print=2`, class `link-print-pdf`; needs `wkhtmltopdf_path` configured. |
| `[BACK]…[/BACK]` | `history.back()` link, class `link-back`; content is the link text. |
| `[LANG:xx]…[/LANG:xx]` | Language split markup, kept for the active frontend language with `lang_parse` enabled; the legacy `[xx]…[/xx]` form works as well. |
| `@@Text@@` | i18n substitution, applied when `i18n_parse` is enabled. |
| `[HTML]…[/HTML]` | Inner text through `html_entities()`. |
| `[HTML_SPECIAL]…[/HTML_SPECIAL]` | Inner text through `html()`. |
| `[IF_CAT:id,id]…[/IF_CAT]` | Keeps inner content when the current level ID matches; `{IF_CAT_ID}` prints the matched ID. |
| `[IF_NOTCAT:id,id]…[/IF_NOTCAT]` | Negated variant; `{IF_NOTCAT_ID}` prints the current level ID. |
| `[LEVEL<n>_ID]…[/LEVEL<n>_ID]` | Level guard tag; `{LEVEL<n>_ID}` resolves to the level ID at depth `n` (root = 0). |
| `{LAZY_LOADING}` | Lazy loading attribute, see [site tags](02-site.md). |
| `{RSSIMG}` | RSS icon, see [RSS](09-rss.md). |
| `{PHPWCMS_RESIZE_IMAGE}` | Image resize path, see [site tags](02-site.md). |
| `{GT:style}text{/GT}` | Deprecated: wraps text in `<span class="gt-style">`; renders only with `gt_mod` enabled. |

## Not present in 2.x

Older documentation lists more tags in this group that have no handler in this code base: `{COUNT_*}`, `{VERSION}`, `{CACHE_*}` and `{EMAIL_*}`. The `{RANDOM:…}` / `{RANDOM_GIF}` / `{RANDOM_JPEG}` / `{RANDOM_PNG}` image tags exist only in the deprecated pass — see [deprecated tags](../deprecated-tags.md).

## Related

- [SHOW_CONTENT](03-show-content.md) — the preferred way to list or embed articles
- [Article tags](04-article.md) — article list pagination fields `{PREV}` / `{NEXT}` / `{NAVI}`