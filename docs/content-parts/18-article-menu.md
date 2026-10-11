---
title: Article menu
description: List the articles of one structure level as a simple ul, div, dl, span or table menu.
sidebar_position: 18
---
# Article menu (content part 15)

> Source: `include/inc_lib/content/cnt15.readform.inc.php`, `include/inc_tmpl/content/cnt15.inc.php`, `include/inc_front/content/cnt15.article.inc.php`, `include/inc_lib/general.inc.php` (`get_order_sort()`)

Lists the articles of a single structure level as a simple menu, with optional per-article summary text. The content part has no own templates; markup follows the four fixed output modes.

## Backend form

Fields as they appear in the content part dialog, top to bottom.

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Site level | radio + select | Current level | `calist_cat` 0 = the level the article is placed in; otherwise `calist_catid` picks a level from the structure tree (Index = 0). |
| Show as | radio | Table | `calist_ul`: `<ul>` (1), `<div>` (2), `<dl>` (3), `<span>` (4), Table (0). Invalid values fall back to Table. |
| CSS class | text | *(empty)* | `calist_class` on the wrapper tag; in Table mode a `<div class="…">` wraps the table; the active item gets `<class>-active` (or `alink-active` when the class is empty). |
| Numerize title | checkbox | off | `calist_titleasnumber`: menu text becomes the item's position number instead of the menu title/title. |
| Label | text | *(empty)* | `calist_label`: rendered once inside the wrapper as item tag with `class="label"` — `li`, `div`, `dt` or `b` depending on the output mode. |
| Break | text | *(empty)* | `calist_break`: HTML inserted between items, wrapped in the item tag with `class="break"`. |
| Summary | checkbox | off | `calist_headertext`: show the article summary under each menu entry. |
| Max char | text | *(empty)* | `calist_maxchar`: truncate the summary at this character count (first line after word wrapping); replacement tags are removed and entities decoded. |
| More link | text | *(empty)* | `calist_morelink`: append a "more" link to the truncated summary, pointing back to the article. |
| Title wrap | select | *(empty = none)* | `calist_titlewrap`: wrap each link in `p`, `div`, `span`, `h1`–`h6`, `pre`, `blockquote` or `em`. |
| Hide active | checkbox | off | `calist_hideactive`: exclude the article currently displayed from the list. |

## Rendering

- The list queries articles of the chosen level: `article_aktiv=1 AND article_deleted=0`, the visibility window unless preview mode, plus `article_id != <current>` when Hide active is set. It selects `article_id, article_title, article_cid, article_summary, article_alias, article_menutitle`.
- Sorting comes from the structure level's article order (`acat_order`, `get_order_sort()` in `include/inc_lib/general.inc.php`), always led by `article_priorize DESC`:

  | `acat_order` | SQL order |
  | ---- | ------------- |
  | 0 / 1 | `article_sort` ASC / DESC |
  | 2 / 3 | `article_created` ASC / DESC |
  | 4 / 5 | `article_tstamp` ASC / DESC |
  | 6 / 7 | `article_begin` ASC / DESC |
  | 8 / 9 | `article_title` ASC / DESC |
  | 10 / 11 | `article_end` ASC / DESC |

- Menu text per item: menu title, else article title, both HTML-escaped; the numeric position replaces it when Numerize title is set. Each link carries `title="…"` with the article title.
- Wrapper tags per mode — 1: `ul`/`li`, 2: `div`/`div`, 3: `dl`/`dd` (`dt` for the label), 4: `div`/`span` (`b` for the label). Each item is rendered inside its mode's item tag.
- `Table` mode wraps rows in a bare `<table>`; each row opens with a sign cell (`template_default['article']['link_article_sign']`, sign as `valign="top"` cell with class `nowrap`), the link cell carries `article-link-internal` (the `link_article_class` default) plus the summary occupying a second row (`rowspan="2"` on the item cell).
- The current article's item receives `class="alink-active"` or `<CSS class>-active` in all modes.
- Article links are built as `index.php?<aid parameter>` via `setGetArticleAid()`, so URL aliases are honored by the URL routing.
- The whole block is wrapped in `<div class="… attr class" id="…">` when the content part carries an attribute class or ID, and the content part title/subtitle render through the standard `headline()` function — no template tags, no template file.

## Storage

Serialized array `alist` in `phpwcms_articlecontent.acontent_form` with the keys `cat`, `catid`, `headertext`, `ul`, `class`, `maxchar`, `morelink`, `titlewrap`, `hideactive`, `titleasnumber`, `break`, `label`. No `acontent_template` value is used.

## Related

- Auto-collected teasers with templates: [Teaser/link article (cp 8)](17-teaser-link-article.md)
- News records as list: [News (cp 33)](19-news.md)