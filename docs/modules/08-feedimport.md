---
title: Feed import
description: Imports RSS and Atom feed items as articles on request of a trigger URL.
sidebar_position: 8
---
# Feed import

> Source: `include/inc_module/mod_feedimport/`

The feed import module turns RSS/Atom feed items into phpwcms articles. Imports run on request of a trigger URL, not on a cron schedule. Module registration (`module.default.php`): `$_module_name = 'feedimport'`, `$_module_type = 1` (backend only), no content part, frontend init hook.

## Storage

> Source: `inc/processing.inc.php`

Feed definitions have no own table. Each feed is a row in `phpwcms_content` with `cnt_module = 'feedimport'`:

| Column | Contents |
| ------ | -------- |
| `cnt_text` | Feed URL |
| `cnt_prio` | Update interval in seconds (0 = import off) |
| `cnt_sort` | Timestamp of the last import |
| `cnt_object` | Serialized configuration from the [feed form](#feed-form) |

Deleting a feed sets `cnt_status = 9`.

## Backend

Available to usergroups with the feed import module enabled. URL: `phpwcms.php?do=modules&module=feedimport`. The routing (GET parameter `edit`, `active` toggle, else listing) lives in `backend.default.php`.

### Feed form

> Source: `inc/processing.inc.php`, `backend.editform.php`

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Title | text | – | Feed name in the listing |
| Feed URL | text | – | Validated as URL; reachable via HEAD request with HTTP 200 check |
| Category | select | – | Structure level the imported articles land in |
| Detail template | select | – | Article summary templates from `template/inc_cntpart/articlesummary/` |
| List template | select | – | Article list templates from the same source |
| Image folder | select | – | File storage directory where enclosure images are stored |
| Image URL replace | text | – | `search>replace` string pair applied to image URLs |
| Enable custom update intervals | checkbox + select | 14400 (4 hours) | Interval for the next import, 60 seconds to 1 month |
| Set new articles visible by default | checkbox | off | Initial visibility of imported articles |
| Author | select | – | Backend user set as article author |
| Fallback author name | text | – | Used when no backend user is selected |
| Source link | checkbox + text | on, `@@Source@@` | Appends a source link to the article text |
| Status email | text | – | Optional notification address receiving the import log |
| Active | checkbox | on | Feed participates in imports |

The form shows the read-only trigger URL: `index.php?feedimport=<md5 of cnt_id + cnt_text>`.

## Import flow

> Source: `frontend.init.php`, `inc/functions.inc.php`

1. A request to the trigger URL (or `index.php?feedimport=<hash>-now`, which bypasses the interval) matches the hash against active feed rows. The interval gate compares the stored last-import timestamp against `cnt_prio` when the standard interval is not overridden.
2. The feed is fetched and parsed with SimplePie, cache disabled.
3. Each item is checked against `phpwcms_crossreference` with `cref_type = 'feed_to_article_import'`; the reference id hashes feed URL, item title and item date, so repeated imports skip known items.
4. Matching items create a new article: the item date becomes the article start date, the summary serves as article summary, and the cleaned body goes into a first content part — either WYSIWYG HTML (type 14) or plain text (type 0).
5. The optional source link is appended with the class `feed-permalink`; enclosure imagery is downloaded into the file storage (`phpwcms_file`, inserted with parent directory `f_kid = 1`) and attached as serialized article image.
6. When a status email is configured, the import log is mailed; the script terminates after the import.

There is no scheduled execution in the module itself — the trigger URL has to be requested (manually or by an external scheduler/cron hitting the URL).

## Frontend

The `frontend.init` registration is the mechanism above: it reacts on the `feedimport` GET parameter before the page renders and then exits. Imported articles render like any other article — see [Core concepts](../general/concepts.md).

## Configuration

The module registers no `phpwcms` configuration keys. Update intervals live per feed in the feed form. Backend access follows the module group in the usergroup editor — see [Modules overview](index.md).

## Related

- [Modules overview](index.md) — architecture and activation
- [Calendar](05-calendar.md) — the other module driven by time-based processing