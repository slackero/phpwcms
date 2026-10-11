---
title: RSS
description: RSS icon tag and feed links for the frontend.
sidebar_position: 9
---

# RSS

> Source: `include/inc_front/content.func.inc.php` (`RSSIMG` pass), `include/inc_front/front.func.inc.php` (`html_parser()` feed link), `include/config/conf.template_default.inc.php`

phpwcms serves feeds from `feeds.php` and the template layer offers the RSS icon tag plus the feed link markup.

## {RSSIMG}

```
{RSSIMG}
```

Replaced by `$template_default['rss']['image']`. The default value in `include/config/conf.template_default.inc.php` is an inline SVG feed icon (`width="14" height="14"`, fill `#fd7e14`). Overwrite the value in a template default override file to place your own icon markup.

## Feed link markup

The text markup layer converts the pattern below into a link to `feeds.php?feed=<id>`:

```
[RSS 1]Feed title[/RSS]
```

```html
<a href="feeds.php?feed=1" target="_blank" rel="noopener noreferrer" class="link-rss">Feed title</a>
```

The link class comes from `$template_default['classes']['link-rss']` (`link-rss` by default). Feed IDs and their definitions come from the feed configuration (`include/config/feeds.ini.php`), rendered by `feeds.php`.

## Feed listing template (content part 22)

The RSS feed import content part ships its own template fields, resolved within that content part — `{FEEDINFO}`, `{ITEMS}`, `{TITLE}`, `{SUBTITLE}`, `{ATTR_CLASS}`, `{ATTR_ID}` and the entry fields inside `{ITEMS}`. They belong to the [content parts reference](../../content-parts/index.md), not to the global tag cascade.

The news content part (type 33) wraps its output in `[NEWS_ENTRIES]…[/NEWS_ENTRIES]` for the same reason.

## Not present in 2.x

`{RSS_TITLE}`, `{RSS_DESCRIPTION}` and other per-feed replacement tags from older documentation have no handler here. Generate feed pages through `feeds.php` or a frontend render script.

## Related

- [Site and server tags](02-site.md) — `{RSSIMG}` is listed among global output tags
- [Functions and misc](10-functions-misc.md) — the search and browse tags that share the same cascade slot