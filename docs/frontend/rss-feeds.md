---
title: RSS feeds
description: Site feed output with feeds.php, feed configuration in feeds.ini.php and the RSS feed content part.
sidebar_position: 4
---
# RSS feeds

> Source: `feeds.php`, `rss.php`, `include/config/feeds.ini.php`, `include/inc_front/content/cnt22.article.inc.php`

phpwcms produces site feeds and consumes external ones. Two directions, two mechanisms:

| Direction | Mechanism |
| --------- | --------- |
| Publish site articles | `feeds.php` builds a feed from the article table |
| Consume external feeds | Content part 22 (RSS feed) renders a feed URL inside a page; the Feedimport [module](../general/concepts.md#modules) imports feeds into articles |

## feeds.php

`feeds.php` loads the feed definitions from `include/config/feeds.ini.php` (a PHP-guarded INI file — keep the first line intact) and renders with the bundled `UniversalFeedCreator`. When the INI file holds no usable entries a built-in `default` feed applies.

Request parameters:

| Parameter | Meaning |
| --------- | ------- |
| `?feed=<name>` | Uses the `[<name>]` INI section; when unknown, the value acts as `structureID` on the default feed |
| `?format=<FORMAT>` | Overrides `defaultFormat`; valid: `0.91`/`RSS0.91`, `1.0`/`RSS1.0`, `2.0`/`RSS2.0`, `ATOM`/`ATOM1.0`, `ATOM0.3` |

The rendered feed caches into `content/rss/<format>-<filename>` for `cacheTTL` seconds via `useCached()`, then `saveFeed()` emits it. When `structureID` sits in the query (unknown `feed=` value), the filename becomes `<structureIDs>.xml`.

## Feed configuration

> Source: `include/config/feeds.ini.php`

| Field | Default | Description |
| ----- | ------- | ----------- |
| `title` | `Daily RSS feed` | Feed title |
| `description` | empty | Feed description |
| `link` | auto `PHPWCMS_URL` | Feed home link |
| `syndicationURL` | empty, then SELF | Canonical feed URL |
| `imagesrc`, `imagetitle`, `imagelink`, `imagedescription` | empty | Optional feed image |
| `useauthor` | 1 | Render per-item author tags |
| `feedAuthor`, `feedEmail` | editor strings | Author fallback (ATOM always needs one) |
| `timeZone` | `+01:00` | Timezone offset, empty = GMT |
| `cacheTTL` | 3600 | Feed file cache seconds, 0 disables caching |
| `structureID` | empty (all) | Structure level IDs or aliases, comma separated; the site root alias resolves to level 0 when the root is public |
| `maxentries` | 10 | Article limit |
| `encoding` | `UTF-8` | Output charset |
| `defaultFormat` | `RSS2.0` | Format when no `format=` GET value |
| `filename` | `default_feed.xml` (or md5 of name+title) | Cache file name |
| `orderBy` | `livedate` | `livedate`, `killdate`, `createdate`, `changedate` |
| `order` | `DESC` | `ASC`, `DESC` or `RAND` |

## Article selection

The feed SQL selects articles that are active, not deleted and not flagged `article_norss`, inside their publication window (`article_begin`/`article_end`), with `article_nosearch = 0`. Only public structure levels ship: levels must be active, not in trash and `acat_regonly = 0` — regonly levels never produce feed items. Items carry title, summary (falling back to subtitle), link by [URL handling](../general/url-handling.md), dates, guid and, when enabled, the author line. Multi-language markup strips through the same `[<lang>]` block parser the frontend uses (`combinedParser()`), and text converts into the feed's charset.

## rss.php

`rss.php` no longer generates feeds; it redirects to `feeds.php`. Keep links working across 1.x installs.

## RSS feed content part (CP 22)

Content part 22 pulls an *external* feed into the page:

- `rssurl` — the feed URL, parsed with the SimplePie based `rss_obj`.
- Template: selected file from `template/inc_cntpart/rssfeed/`, default `template/inc_default/rssfeed.tmpl`, hard-coded `<li>` markup as last fallback. Tags: `[RSSFEED]` wrapper with `[TITLE]`, `[SUBTITLE]`, `[ATTR_CLASS]`, `[ATTR_ID]`, `{ITEMS}`, `{FEEDINFO}`, `[LINK]`; per item `[ITEM]` loop with `LINK`, `TITLE`, `DESCRIPTION`, `CONTENT`, `AUTHOR`, `IMAGE`; the feed info header carries `IMAGE`, `TITLE`, `DESCRIPTION` and `{DIVIDER}`.
- `timeout` — cache seconds (default 3600), files under `content/rss/`; `cacheoff` disables caching.
- `item` — maximum item count.

## Related

- [Page rendering](page-rendering.md)
- [Core concepts](../general/concepts.md)