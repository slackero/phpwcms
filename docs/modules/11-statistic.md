---
title: Statistics
description: Backend dashboards over article status, downloads, newsletter subscriptions, guestbook entries, polls, logins and SEO log data.
sidebar_position: 11
---
# Statistics

> Source: `include/inc_module/mod_statistic/`

The statistics module is a read-only reporting layer over data the core and other modules already store. It records nothing itself. Module registration (`module.default.php`): `$_module_name = 'statistics'`, `$_module_type = 1` — backend only, no content part, no frontend hooks. There is no `phpwcms_statistic` table.

## Backend

Available to usergroups with the statistics module enabled. URL: `phpwcms.php?do=modules&module=statistics`.

The active page is selected with the `controller` GET parameter (`backend.default.php`). Tabs are rendered by `inc/tabs.inc.php` and appear only when the underlying data exists:

| Controller | Shows | Availability |
| ---------- | ----- | ------------ |
| (default/overview) | Article status counts (active, start/end not reached, missing alias, missing description), file center image counts, content part usage counts per type, expired articles | Always |
| `downloads` | Files from the file center with download traffic — rows from `phpwcms_file` where `f_dlstart > 0` and `f_trash = 0`, sortable by name, download start, final count, created date; searchable and paginated (`page`, `c`) | When downloaded files exist |
| `subscriptions` | Newsletter subscription lists with recipient counts, verified vs unverified address counts, last 200 entries of `phpwcms_log` for log types 1–4 joined to addresses; per-row delete via `delete`; blacklist an email via `blacklist=<email>` (removes matching log rows and the unverified address, inserts into the blacklist) | When `phpwcms_address` rows exist |
| `guestbook` | Content part type 18 instances with their entry counts from `phpwcms_guestbook` | When guestbook content parts exist |
| `polls` | Content part type 89 instances with summed votes (unserialized `acontent_form` count) | When poll content parts exist |
| `user` | Last 100 backend login log rows from `phpwcms_userlog` | Admin users only (`$_SESSION['wcs_user_admin']`) |
| `seo` | `phpwcms_log_seo` rows per hit (date, domain, position, query) plus a top 20 query frequency list, own session filter `$_SESSION['seo_filter']` | Always — same data as [SEO log](10-seolog.md) |

Listings use POST `list_search` for search terms and GET `page`/`c` for pagination.

## Data sources

The module writes no data. The tables it reads are fed elsewhere:

- `phpwcms_userlog` is written on backend login (`login.php`) and refreshed during the session by `include/inc_lib/checklogin.inc.php`.
- `phpwcms_file` download columns (`f_dlstart`, `f_dlfinal`) are updated by the download handler.
- `phpwcms_log_seo` is filled by the core referrer logging described on the [SEO log](10-seolog.md) page.
- `phpwcms_address`, `phpwcms_subscription` and `phpwcms_log` belong to the [Newsletter (core)](06-newsletter.md) and its address storage.

## Configuration

The module registers no configuration keys. Backend access follows the module group in the usergroup editor — see [Modules overview](index.md).

## Related

- [Modules overview](index.md) — architecture and activation
- [SEO log](10-seolog.md) — the module dedicated to the `phpwcms_log_seo` listing
- [Newsletter (core)](06-newsletter.md) — subscriptions and address data