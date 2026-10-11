---
title: RSS feed
description: Import an external RSS or Atom feed and display its items through a template.
sidebar_position: 22
---
# RSS feed (content part 22)

> Source: `include/inc_lib/content/cnt22.readform.inc.php`, `include/inc_tmpl/content/cnt22.inc.php`, `include/inc_front/content/cnt22.article.inc.php`, `template/inc_default/rssfeed.tmpl`

Fetches a remote feed with SimplePie and renders feed info plus item list from a template. Nothing is output while the feed cannot be read or parsed.

## Backend form

Fields as they appear in the content part dialog, top to bottom.

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Template | select | *(empty = default)* | File from `template/inc_cntpart/rssfeed/`. Empty uses `template/inc_default/rssfeed.tmpl`. |
| Feed URL | text | *(empty)* | `rssurl`: the feed to import; without a URL the content part renders nothing. |
| Items | text (10 chars) | *(empty = all)* | `item`: maximum number of items (`set_item_limit`). |
| Source content type | select | Automatic | `content_type`: input charset passed to SimplePie when the feed does not declare it; values come from the installation's charset list. |
| Cache | checkbox | on | `cacheoff`: check "Off" to disable caching entirely. |
| Cache timeout | select | Default | `timeout`: 0 = default value of 3600 seconds (1 hour), otherwise 1 minute to 1 month; picking a value unchecks the cache Off box. |

The feed cache lives in `content/rss/` (`PHPWCMS_RSS`, defined in `include/inc_lib/default.inc.php`). Rendered feed text is passed to the output charset (`PHPWCMS_CHARSET`), wrapped in div removed, and all HTML tags are stripped before rendering.

## Rendering

### Template sections

| Section | Meaning |
| ------- | ------- |
| `RSSFEED` | Overall wrapper — also holds the assembled `{FEEDINFO}` and `{ITEMS}`. |
| `FEEDINFO` | Feed-level block: feed title, description and feed image. |
| `ITEM` | Repeated per item. |
| `DIVIDER` | Markup placed through `{DIVIDER}` wherever the wrapper wants it (the default puts it above and below the item `<ul>`). |

### Template tags

| Tag | Meaning |
| --- | ------- |
| `[TITLE]`/`{TITLE}`, `[DESCRIPTION]`/`{DESCRIPTION}` | Feed title and description in `FEEDINFO`; item title and description in `ITEM`; content part title/subtitle in `RSSFEED` (where the tag is `{TITLE}` inside `[TITLE]`). |
| `[IMAGE]`/`{IMAGE}` | Feed logo `<img>` linked to the feed image link or feed permalink (in `FEEDINFO`); item thumbnail URL from the first enclosure (in `ITEM`) — only image extensions `jpg`, `jpeg`, `png`, `gif` qualify. |
| `[LINK]`/`{LINK}` | Item permalink in `ITEM`; feed permalink in `RSSFEED`. |
| `[CONTENT]`/`{CONTENT}` | Item full content. |
| `[AUTHOR]`/`{AUTHOR}` | Item author name. |
| `{DATE:… lang=EN}` | Item publication date (`render_cnt_date`, so `{LIVEDATE}`/`{NOW}` variants work). |
| `{ITEMS}` | All rendered items joined by line breaks. |
| `{FEEDINFO}` | The rendered feed info block. |
| `{DIVIDER}` | Divider markup. |
| `[ATTR_CLASS]`/`{ATTR_CLASS}`, `[ATTR_ID]`/`{ATTR_ID}` `[ATTR_CLASS_ELSE]` | Content part wrapper class and `id`. |

Values pass through mostly unescaped — as parsed from the feed after tag stripping.

## Storage

Serialized array `rssfeed` in `phpwcms_articlecontent.acontent_form` with the keys `rssurl`, `template`, `item`, `cut1st` (legacy, no backend field and not evaluated), `cacheoff`, `timeout`, `content_type`. No `acontent_template` value is used.

## Related

- News list content part (own records, not remote feeds): [News (cp 33)](19-news.md)