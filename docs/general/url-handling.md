---
title: URL handling
description: URL forms, aliases, rewriting, 301 forcing, canonical links and the redirect admin.
sidebar_position: 2
---
# URL handling

> Source: `include/inc_front/content.func.inc.php` (resolution), `include/inc_lib/default.inc.php` (`rel_url()`, `abs_url()`, `returnGlobalGET_QueryString()`), `include/inc_front/front.func.inc.php` (`url_search()`), `include/config/dist.conf.inc.php`

## URL forms

phpwcms URLs name the page with the alias of the structure level or the article:

| Form | Meaning |
| ---- | ------- |
| `index.php?<level-alias>` | Structure level page, e.g. `index.php?about-us` |
| `index.php?<article-alias>` | Article page by its own alias |
| `index.php?id=<level>[,<aid>…]` | Level and optionally article by numeric IDs (legacy combined form) |
| `index.php?aid=<article-id>` | Article by ID; `aid=<id>-<n>` addresses content part pagination page `<n>` |

Alias resolution runs one UNION query: a matching `articlecat.acat_alias` wins over `article.article_alias`. When the alias equals the index page alias, the site root renders. With rewriting configured the first (empty-valued) GET parameter becomes the path — `/about-us.html` — while remaining parameters stay as query string.

`PHPWCMS_ALIAS` defines the canonical name of the current request: the alias when one exists, otherwise `id=<level>` or `aid=<article-id>`.

## Rewrite configuration

| Key | Default | Description |
| --- | ------- | ----------- |
| `rewrite_url` | 1 | Enables path based URLs (`PHPWCMS_REWRITE`); when 0, `rel_url()`/`abs_url()` keep `index.php?` |
| `rewrite_ext` | `.html` | Suffix of rewritten URLs; `/` renders directory style (`/alias/`) |
| `alias_allow_slash` | 1 | Allows `/` inside aliases for path-like aliases |
| `alias_allow_utf8` | 1 | Keeps UTF-8 characters in aliases (requires `charset = utf-8`) |
| `allow_empty_alias` | false | When true, empty alias fields no longer auto-generate an alias |
| `reserved_alias` | empty array | Alias names blocked from use |

Outgoing markup rewrites by itself: `url_search()` in `front.func.inc.php` converts `href="index.php?…"` and `onclick="location.href='index.php?…'"` attributes into `<value><rewrite_ext>` paths (an old style `id=<cat>,<aid>` becomes `<cat>.<aid>`), and the image handler URL `img/cmsimage.php` shortens to `im`. The webserver side needs matching rewrite rules delivering `/alias.html` back to `index.php?<alias>`.

## rel_url and abs_url

`rel_url($add, $remove, $id_alias, $format, $glue, $bind)` and `abs_url()` build URLs from the current GET state in `$GLOBALS['_getVar']`:

- First pair values with `$add`, drop `$remove` (plus `$phpwcms['global_unregister_getVar']`), optionally substitute the first pair by `$id_alias` (e.g. `'aid=42'` or an alias).
- With rewriting on the first pair becomes `<value><rewrite_ext>` and the rest a normal query string; without, everything lands after `index.php?`.
- `$format` controls escaping: `htmlspecialchars` (default), `htmlentities`, `urlencode`, `rawurlencode`, or default (no escaping).
- `rel_url()` returns the URL relative to the site root and falls back to `PHPWCMS_URL`; `abs_url()` always prefixes the full site URL.

`{SITE}` (see [layout tags](../templates/layout-tags.md#site-paths)) provides the same absolute prefix inside markup.

## 301 forcing

| Key | Default | Behavior |
| --- | ------- | -------- |
| `force301_id2alias` | false | When a request uses numeric `id=`/`aid=` but an alias exists, send 301 to the alias URL; content part pagination (`aid-N`) stays numeric |
| `force301_2struct` | false | When a level shows exactly one article, 301 to the level alias |

Level redirects (`acat_redirect`) always 301 before anything renders. Both force keys switch off for POST requests and preview mode, and a level can exempt itself from the structure redirect with `acat_disable301`.

## Canonical handling

`$phpwcms['canonical_off']` disables the canonical link completely. Otherwise `content.article.inc.php` writes `<link rel="canonical">` into the head:

- A page requesting an alias resolves canonically; the single-article-of-a-level case canonically points to the level alias.
- When `force301_2struct` redirects anyway, the canonical step skips; content part pagination pages canonicalize to `aid=<id>-<page>`.
- Overrides: the article field *canonical URL* (`article_canonical`) or the level equivalent (`acat_canonical`) replaces the automatic value. A leading `{SITE}` expands to the site URL, a leading `/` mounts beneath it.

When more GET parameters than the page identity ride along (`count($_getVar) > 1`), automatic canonicalization drops — search or filter traffic keeps its identity rather than folding into the base page.

## 404 handling and the redirects admin

A request hitting nothing (level, article, alias) enters `$content['404error']`:

1. With alias `r404` or `REDIRECT_STATUS == 404`, the original `REDIRECT_URL` retries as alias once.
2. Failing that, the recorded combination (level id, article id, alias) looks up the `redirect` table — the same store the backend **Admin → Redirects** page manages (`p=14`, `include/inc_tmpl/admin.redirect.tmpl.php`). An active matching entry redirects with its configured code (301, 302, 307, 401, 404, 503; default 302) and increments its `views` counter. Entry types: `home` (level tree entry of the root), `alias`, `id`, `aid`, `link`.
3. No match: `$phpwcms['log_404error']` auto-inserts the failed combination so it appears in the admin list for later setup.
4. Nothing redirectable: HTTP 404 status with the current template's error block (`[404]` / `[404_ELSE]` sections, see [layout tags](../templates/layout-tags.md#list-mode-pagination-and-error-sections)).

The redirects admin lists entries with filters (active/inactive, search terms, page size), lets you define redirects manually and toggles existing entries active or inactive; `update_404redirect()` flushes the cached set.

## Related

- [Core concepts](concepts.md#urls-and-aliases)
- [Page rendering](../frontend/page-rendering.md) — resolution and 404 flow in execution order