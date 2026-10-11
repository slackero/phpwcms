---
title: Ads
description: Manages banner campaigns and ad places with delivery, view and click tracking on the frontend.
sidebar_position: 7
---
# Ads

> Source: `include/inc_module/mod_ads/`

The ads module manages banner campaigns and ad places, delivers them on frontend pages through `{ADS_<id>}` tags and tracks views and clicks. Module registration (`module.default.php`): `$_module_name = 'ads'`, `$_module_type = 0` (backend and frontend), no content part, frontend init and render hooks.

It defines the constant `PHPWCMS_ADS_DIR` from `$phpwcms['ads_path']` (fallback `'ads'`, `module.default.php` line 31) — mainly used to keep ad delivery paths flexible against ad blockers.

## Installation / activation

Ships as part of the codebase; place the module directory under `include/inc_module/` (`include/inc_module/mod_ads/INSTALL.txt`). The backend creates the media directory `content/<ads_path>/` with an `index.html` automatically on first use (`backend.default.php`). Uploaded campaign media land in `content/<ads_path>/<campaign_id>/` with an `.htaccess` denying PHP execution (`processing.campaign.inc.php`).

Backend access follows the module group in the usergroup editor — see [Modules overview](index.md).

## Backend

URL: `phpwcms.php?do=modules&module=ads`. The routing lives in `backend.default.php`: without parameters the campaign list shows; `listadplace` lists ad places; `campaign=1` / `adplace=1` open the respective new-entry forms; `edit`, `verify`, `delete` act on the ids. Deleting is a soft delete (`status = 9`); campaign media files are renamed to a `_deleted_` prefix. Duplicating a campaign copies the row with a title suffix (`_dbDuplicateRow`).

### Campaign form

> Source: `backend.form.campaign.php`, `inc/processing.campaign.inc.php`

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Title | text | – | Campaign name |
| Adplace | select | – | One of the existing [ad places](#ad-place-form); shows the place size read-only |
| Target URL | text | – | Link target of the banner |
| Target | select | – | Window target: `_blank`, `_top`, `_self`, `_parent` |
| Start date / time | date picker | – | `DD.MM.YYYY` / `HH:MM` entry |
| End date / time | date picker | – | Same format |
| Max views | number | – | Delivery stops when reached |
| Max clicks | number | – | Delivery stops when reached |
| Max views per visitor | number | – | Per-visitor cap, counted via tracking cookie |
| Ad type | radio | image (0) | 0 = image, 1 = video, 2 = HTML, 3 = HTML5 package, 4 = remote HTML |
| Media | file select/upload | – | Per type: image (GIF/JPG/JPEG/PNG/WebP), video (MP4/WebM), HTML5 zip package (extracted server side; its CSS gets an entry field) |
| HTML | textarea | – | Raw HTML for types 2 and 4 |
| Alt / title text | text | – | Image HTML attributes |
| Background / border color | color | – | Wrapper box colors |
| Comment | textarea | – | Internal note |
| Activate | checkbox | – | Campaign state |
| Save as duplicate | checkbox | – | Stores a copy on save |

Media metadata is stored serialized in the campaign's `adcampaign_data` column.

### Ad place form

> Source: `backend.form.adplace.php`

Ad places define the slot: a title, a format from `phpwcms_ads_formats` (user-created; no seed data ships), width and height shown read-only from the format, optional HTML prefix and suffix wrapping the output, the ready-to-copy banner code `{ADS_<id>}`, and a status switch.

## Frontend

> Source: `frontend.render.php`, `inc/ads.fe_render.inc.php`

Any `{ADS_<id>}` placeholder in the page markup triggers delivery (regex `/\\{ADS_(\\d+)\\}/`) of a matching campaign:

- Delivery selects randomly (`array_rand`) among campaigns of that ad place that are active, inside their date window, and under their max views, max clicks and per-visitor caps. Bot user agents and unknown browsers (`BROWSER_OS` = `Other`) receive nothing.
- Per-visitor view counting uses the tracking cookie set by the module's delivery logic.
- Output shapes by ad type: `<a><img>` for images; `<video autoplay muted loop playsinline>` with poster fallback for videos; HTML rendered inside a colored box; HTML5 packages inside an iframe with `clickTag=` appended; remote HTML inserted raw.
- When at least one ad rendered, a view tracking pixel `img/blank.php?t=...` is appended to the page.
- Renders skip ad-specific URL paths — together with the configurable `PHPWCMS_ADS_DIR` this reduces ad-blocker collisions.

### Click tracking

Banner clicks route through `index.php?adclickval=<id>&url=...`. The init hook (`inc/ads.fe_init.inc.php`) validates the `u` parameter against the visitor cookie key, counts the click in `phpwcms_ads_tracking`, increments the campaign click counter and redirects to the target URL. The view counter runs in the site root script `img/blank.php`, which anonymizes IPs when the constant `PHPWCMS_GDPR_MODE` is defined — set through `$phpwcms['enable_GDPR']` (default true, `include/config/dist.conf.inc.php` line 158) — and sets a two-year cookie.

## Storage

> Source: `setup/default_sql/phpwcms_init.sql` lines 18–82

| Table | Contents |
| ----- | -------- |
| `phpwcms_ads_campaign` | Campaigns: title, comment, date start/end, max view/click/visitor caps plus current counters, type, place reference, serialized `adcampaign_data` |
| `phpwcms_ads_place` | Ad places: title, format reference, HTML prefix/suffix, status |
| `phpwcms_ads_formats` | Format definitions (width/height); user created, none seeded |
| `phpwcms_ads_tracking` | Per-visit tracking rows: campaign id, ip, cookie id, click and view counts, user agent, referrer, category id, article id |

## Configuration

| Key | Type | Default | Description |
| ---- | ---- | ------- | ----------- |
| `$phpwcms['ads_path']` | string | `'marketing'` | Directory below `content/` for campaign media (`include/config/dist.conf.inc.php` line 48) |

## Related

- [Modules overview](index.md) — architecture and activation
- [Statistics](11-statistic.md) — module-independent backend dashboards