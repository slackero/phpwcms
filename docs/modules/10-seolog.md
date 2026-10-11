---
title: SEO Log
description: Backend module listing search engine referrer hits collected on the frontend.
sidebar_position: 10
---
# SEO Log

> Source: `include/inc_module/mod_seolog/`

The SEO log module lists search engine referrer data collected by the core: which search engine domain referred visitors, with which query, and at which result position. Module registration (`module.default.php`): `$_module_name = 'seo_log'`, `$_module_type = 1` — backend only, no content part, no frontend hooks.

## Data collection

Logging is core behavior, not the module's:

- `index.php` calls `seReferrer()` when `$phpwcms['enable_seolog']` is set and the `HTTP_REFERER` of the request does not contain the site's own `SERVER_NAME`. The insert into `phpwcms_log_seo` runs with `LOW_PRIORITY`, storing the parsed referrer plus `hash = md5(strtolower(domain . query))`.
- `seReferrer()` (`include/inc_front/ext.func.inc.php`) extracts the search terms from the typical query parameter names (`q`, `p`, `w`, `s`, `qry`, `searchfor`, `as_q`, `as_epq`, `query`, `qt`, `keyword`, `keywords`, `encquery`) and from DogPile's `/search/web/...` path style. It resolves the result position from `start`, `b`, `first`, `stq` style parameters and decodes + converts the charset of the terms. When the referrer carries no recognizable query, nothing is stored — the log holds only search engine traffic, no general referrers.

The `hash` column was added by revision r497 (`include/inc_lib/revision/r497.php`) together with a backfill of existing rows.

## Storage

> Source: `setup/default_sql/phpwcms_init.sql` line 599

| Column | Type | Contents |
| ------ | ---- | -------- |
| `id` | int | Row id |
| `create_date` | timestamp | Insert time, defaults to `CURRENT_TIMESTAMP` |
| `domain` | varchar(255) | Referrer host, e.g. the search engine |
| `query` | varchar(255) | Search terms |
| `pos` | int | Position of the linked hit in the result list |
| `referrer` | text | Full referrer string |
| `hash` | char(32) | `md5` of lowercased domain + query, indexed |

## Backend

Available to usergroups with the SEO log module enabled. The page is a single listing, no tabs (`backend.default.php` only defines the module URL and includes `backend.listing.php`).

- URL: `phpwcms.php?do=modules&module=seo_log`
- GET parameters: `page` (pagination), `c` (rows per page: 10, 25, 50, 100, 250 or `all`, default 25).
- Filter: a POST form stores whitespace separated terms in `$_SESSION['filter_seo']`.
- Listing groups by `hash` and orders by hit count descending, so each row aggregates repeated hits for one domain/query combination. Columns: Count, Domain (linked to the referrer URL), Query.

There is no pruning or cleanup routine — the table grows until truncated manually.

## Configuration

| Key | Type | Default | Description |
| ---- | ---- | ------- | ----------- |
| `$phpwcms['enable_seolog']` | int | 1 | Enables referrer logging in `index.php` (`include/config/dist.conf.inc.php` line 112) |

The Statistics module offers an alternative view of the same table — see [Statistics](11-statistic.md).

## Related

- [Modules overview](index.md) — architecture and activation
- [Statistics](11-statistic.md) — per-row SEO listing and top queries