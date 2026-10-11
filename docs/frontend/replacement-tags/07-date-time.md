---
title: Date and time
description: Fixed and parametric date and time replacement tags.
sidebar_position: 7
---

# Date and time

> Source: `include/inc_front/content.func.inc.php` (fixed `{DATE_*}` / `{TIME_*}` pass), `include/inc_front/front.func.inc.php` (`render_cnt_date()`, `render_date()`, `date_callback()`, `international_date_format()`)

Two groups of date tags exist: fixed tags that carry the format from the configuration, and parametric tags that name the PHP `date()` format right inside the tag.

## Syntax

```
{DATE_LONG}          {DATE_MEDIUM}          {DATE_SHORT}          {DATE_ARTICLE}
{TIME_LONG}          {TIME_SHORT}           {TIME_ARTICLE}

{DATE:format}
{NOW:format}
{LIVEDATE:format}
{KILLDATE:format}
{DATE:format lang=xx}
```

## Fixed tags

> Source of the defaults: `include/config/conf.template_default.inc.php`

| Tag | Default format | Description |
| --- | -------------- | ----------- |
| `{DATE_LONG}` | `l, j. F Y` | Current date, verbose (`Monday, 1. October 2003`). |
| `{DATE_MEDIUM}` | `D, j. M y` | Current date, medium (`Mon, 1. Oct 03`). |
| `{DATE_SHORT}` | `Y/m/d` | Current date. |
| `{DATE_ARTICLE}` | `Y/m/d` | Date of the shown article (`article_date`), formatted with `$template_default['date']['article']`. |
| `{TIME_LONG}` | `H:i:s` | Current time with seconds. |
| `{TIME_SHORT}` | `H:i` | Current time. |
| `{TIME_ARTICLE}` | `H:i` (time short format) | Time of the shown article. |

The fixed date tags resolve with `international_date_format()` against `$template_default['date']['language']`, so a language file under `include/inc_lang/date/` localizes month and day names. All values default from the configuration; only the language key and the format strings are configurable there.

## Parametric tags

`render_cnt_date()` runs over the assembled page after article rendering (step 20 of the [pipeline](index.md#processing-order)) and per entry inside article list templates, replacing:

| Tag | Date used |
| --- | --------- |
| `{DATE:format}` | Article date in article view, current time otherwise. |
| `{NOW:format}` | Always the current time. |
| `{LIVEDATE:format}` | Article live date (`article_begin`). [LIVEDATE]…[/LIVEDATE] content stays only when set. |
| `{KILLDATE:format}` | Article kill date (`article_end`). [KILLDATE]…[/KILLDATE] keeps content only when set. |

Outside article detail view the call passes the current time for all four values, so `{LIVEDATE:…}` / `{KILLDATE:…}` render `now()` there.

Format follows PHP `date()` codes; the callback uses `date($format, …)`.

### lang parameter

```
{DATE:j. F Y lang=de}
```

When the tag ends with ` lang=<two letters>` the renderer switches to `international_date_format()` and outputs localized weekday and month names for that language. Language files live in `include/inc_lang/date/`, with aliases `cz`→`cs`, `se`→`sv`, `vn`→`vi`, `el`→`gr`, `uk`→`ua`, `zh`→`zh-cn`, `in`→`id`. An empty language uses `{DOCTYPE_LANG}`.

Without the `lang` parameter, weekday/month format letters still localize automatically when the document language is not `en` and the format contains `M`, `F`, `D` or `l` — otherwise `date()` runs with the plain format.

### set option

```
{DATE:format set=<timestamp>}
{DATE:format set=<date string>}
```

Both date callbacks accept a trailing ` set=` in the format part: a numeric value is the direct timestamp, a string goes through `phpwcms_strtotime()`. Without it the tag uses the article date.

## GMDATE variant

`include/inc_lib/general.inc.php` defines one more parametric pair, applied by `replaceGlobalRT()` — newsletter mail text (content parts 12 / 23) and `verify.php`:

```
{DATE:DATE_FORMAT[ SET:timestamp]}
{GMDATE:DATE_FORMAT[ SET:timestamp]}
```

`SET:` takes a timestamp or a date string; `{GMDATE:…}` renders UTC (`gmdate()`). These run only where `replaceGlobalRT()` applies, not on normal pages — use the `{DATE:…}` family above there.

## SORTDATE

The news content part (type 33) adds `{SORTDATE:format}` inside its entry templates, bound to the entry's sort date — a content part scoped tag, covered by the [content parts reference](../../content-parts/index.md). The tag resolves through the same `render_date()` callbacks as above.

## Not present in 2.x

Older documentation lists `{DATE_M_<format>}` and similar month variants; no such handler exists in this code base. Use `{DATE:format lang=xx}` to get localized output.

## Related

- [Article tags](04-article.md) — which article dates feed the parametric tags
- [Meta and head tags](06-meta.md) — how `{DOCTYPE_LANG}` is set