---
title: Page rendering
description: The request to page pipeline in index.php and content.func.inc.php, and the full replacement tag cascade in order.
sidebar_position: 1
---
# Page rendering

> Source: `index.php`, `include/inc_front/content.func.inc.php`, `include/inc_front/content.article.inc.php`, `include/inc_front/content/*.article.inc.php`

Every frontend request runs `index.php` top to bottom. `content.func.inc.php` does the URL resolution, article rendering and tag replacement; `index.php` assembles the final HTML and emits it. The order below is the actual execution order.

## 1. Bootstrap

1. `index.php` loads `include/config/conf.inc.php`, `default.inc.php`, `helper.session.php`, `dbcon.inc.php`.
2. When `content/tmp/update/maintenance.flag` is fresh (< 15 minutes), a 503 maintenance page renders instead.
3. `$IS_A_BOT` decides whether a session initializes (`SESSION_FEinit` or preview request). `cleanupPOSTandGET()` and `buildGlobalGET()` fill `$GLOBALS['_getVar']`; `FE_CURRENT_URL` captures the full URL; `init_frontend_edit()` prepares backend edit links.
4. `ob_start()` buffers the whole render; the config defaults and language files load (`conf.template_default.inc.php`, `conf.indexpage.inc.php`, `cnt.lang.inc.php`, `modules.check.inc.php`, `article.contenttype.inc.php`, `imagick.convert.inc.php`, `front.func.inc.php`, `ext.func.inc.php`), then `content.func.inc.php` runs.
5. Search engine referrers log into `log_seo` when `enable_seolog` is on.

## 2. Request mode

- `$_POST` requests disable the 301 forcing (see [URL handling](../general/url-handling.md)).
- `?phpwcms-preview` enters preview mode: it disables forcing, requires a backend login (otherwise redirects to the site root) and defines `PREVIEW_MODE`, which lifts the article `begin`/`end` time checks.
- `?print=1` selects the HTML print template, `?print=2` the PDF template (`PRINT_PDF`, requires `$phpwcms['wkhtmltopdf_path']`).
- `?phpwcms_output_action=F-filter1|filter2--S-SECTION1|SECTION2` requests a trimmed fragment of defined template sections instead of a full page; handled at the very end of `index.php`.

## 3. URL resolution

`$aktion` is the six-slot array `[<level-id>, <article-id>, <print>, <level-mode>, <show-article>, 0]`:

| URL form | Resolution |
| -------- | ---------- |
| `index.php?id=<cat>[,<aid>…]` | Legacy combined form; validates the level, else 404; a comma separates level and article |
| `index.php?aid=<id>-<cppage>` | Resolves the article's level via a DB lookup; `-<n>` selects the content part pagination page |
| `index.php?<alias>` | Single UNION query against `articlecat.acat_alias` first, then `article.article_alias`; the first empty-valued GET key in `_getVar` is the alias |

Alias based URLs define `PHPWCMS_ALIAS`; the constant also covers `id=`/`aid=` forms later when no alias resolves.

## 4. 404 and redirects

When the requested level, article or alias does not exist, `$content['404error']` fills:

1. Requests arriving with alias `r404` or `REDIRECT_STATUS == 404` retry the original `REDIRECT_URL` as alias once more.
2. The failing combination (id/aid/alias) queries the `redirect` table — an active entry issues a redirect (codes 301, 302, 307, 401, 404, 503, default 302; types `home`, `alias`, `id`, `aid`, `link`) and bumps its view counter.
3. Without a matching entry, `$phpwcms['log_404error']` inserts a new record.
4. Otherwise `headerRedirect('', 404)` emits the 404 status — and the active template's error block (`[404]…[/404]`) renders on that attempt.

Structure level redirects (`acat_redirect`) and SSL forcing (`site_ssl_mode`, `acat_ssl`) 301 before any content renders.

## 5. Permissions and template choice

`get_breadcrumb()` builds `$LEVEL_ID`, `$LEVEL_KEY`; a `regonly` level anywhere on the path sets `PERMIT_ACCESS = FALSE`. `_checkFrontendUserAutoLogin()` runs, and unauthorized visitors redirect with HTTP 401 to the configured login URL (`feloginurl` template override or `$template_default['login_form_url']`).

The [template fallback chain](../templates/template-system.md#which-layout-template-is-used) then selects `$block`, the page layout follows (missing pagelayout aborts with an admin hint), and `template/inc_settings/template_default/` overrides apply. `js.inc.php` loads next.

## 6. Content assembly

`frontend_init` scripts include (see [frontend scripts](frontend-scripts.md)), then one of two branches runs:

- **Article list mode** (no article requested, more than one article, or `topcount = -1`): `list_articles_summary()` (in `front.func.inc.php`) renders the list into `$content['main']` through `article_summary_list.tmpl`.
- **Article mode**: `content.article.inc.php` loads the article row (visible-mode aware), resolves alias articles, builds article redirects (301, meta refresh or JavaScript window handling), canonical, pagetitle, description, summary image with [the summary template chain](../templates/template-system.md#which-content-part-template-is-used), then loops all visible content parts in sort order: each renderer `include/inc_front/content/cnt<N>.article.inc.php` appends to `$content['main']` (or a named block in `$content['CB']`), tab and content-part-group content collects into `$content['cptab']`.

A category holding exactly one article shows the article directly and sets `$content['set_canonical']`; `force301_2struct` may 301 the request to the level alias first.

## 7. Layout assembly

- Normal mode (`layout_render` 0/1/2 of the [page layout](../templates/template-editor.md#page-layout-editor-p8)): table markup (`get_table_block()`), div markup, or the raw `maintext` — the custom mode.
- Print/PDF mode: `template/inc_default/print.tmpl` or `pdf.tmpl` wraps the maintext.

`{CONTENT}` is replaced, custom blocks render with `[BLOCK]`/`[BLOCK_ELSE]`, content part tab placeholders `<!-- <tabid> -->` fill, list-mode sections resolve, and `render_device()` applies its first pass.

## Replacement cascade

The assembled page then runs the replacements in exactly this order through `content.func.inc.php`:

1. `{CURRENT_ARTICLEID}`, `{CURRENT_CATEGORYID}`, `[LEVEL<n>_ID]`/`{LEVEL<n>_ID}`
2. `[IF_CAT:…]`, `[IF_NOTCAT:…]` condition blocks
3. `{SHOW_CONTENT:…}` content pulling
4. `{SITE}`, `{TEMPLATE}`, `render_PHPcode()` (PHP tags with `enable_inline_php`)
5. `{BREADCRUMB…}`
6. `{NAV_LIST_UL:…}`
7. Deprecated navigation and `{RANDOM}`-family tags (`enable_deprecated`)
8. `{DATE_*}`, `{TIME_*}` fixed date/time tags
9. Search form markers `###search_input_…###`
10. `{RELATED:…}` keyword related articles, `{NEW:…}` newest articles
11. `{RSSIMG}`
12. `{KEYWORD:…}`
13. `{URL:…}` external page include
14. `{BROWSE:UP/NEXT/PREV…}`
15. `html_parser()` pass (BB codes, `[img]`, `[mail]`, links, line breaks) unless `parse_html_mode` says `after` — including the `$content['globalRT']` custom tags
16. Redirect code into htmlhead; `PHPWCMS_ALIAS` definition
17. **frontend_render scripts** and module render scripts run here
18. Optional second `html_parser()` pass
19. `render_device()` second pass, PHP re-render, `{LAZY_LOADING}`
20. Frontend edit links and CSS
21. Meta description/keywords insertion
22. Open Graph and Twitter/X card meta rendering (`set_sociallink` config)
23. `generator` meta — unless disabled or whitelabeled
24. `[PRINT]`/`[PRINT_PDF]` link wrappers
25. Article context tags: `{AUTHOR}`, date tags (`render_cnt_date()` → `{DATE:…}`, `{NOW:…}`, `{LIVEDATE:…}`, `{KILLDATE:…}`), `{CATEGORY}`
26. `{SOCIAL_SHARE…}`
27. `<!-- JS: … -->` and `<!-- CSS: … -->` head comments; `frontend.js` legacy detection
28. Article/level onload handling (DomReady, Unload, inline scripts)
29. Search highlight wrapper (`?highlight=` words over `<!--SEARCH_HIGHLIGHT_START//-->…<!--SEARCH_HIGHLIGHT_END//-->` sections)
30. Content part pagination: `{CP_PAGINATE}`, `[CP_PAGINATE_PREV]/[NEXT]`, `[CP_PAGINATE_MENU]`, `link rel="prev"/"next"`
31. `[NO_PRINT]` stripping in print mode, `[LOGGED_IN]/[LOGGED_OUT]` sections
32. `[HTML]…[/HTML]` and `[HTML_SPECIAL]…[/HTML_SPECIAL]`, CKEditor resized image post-processing, XHTML Strict attribute cleanup
33. Cookie consent v3 or v2 script injection, GA/GTM/Matomo tracking code, pixel-ratio detector
34. `custom_htmlhead` flush into `<head>` (or shifted above `</body>` with `js_in_body`)
35. `index.php?aid=/id=` links rewritten to their aliases where they exist
36. `[LANG:…]`/`[<lang>]…[/</lang>]` blocks and `@@…@@` tokens (i18n)
37. `{GT:…}` spans (deprecated, `gt_mod`)
38. Legacy `image_resized.php` img tags migrated to the `cmsimage` schema
39. `download.php?f=` rewritten to `dl/` when rewriting is on; image extensions forced to `.webp` when `webp_enable` fits the client

## Final HTML assembly

`index.php` continues:

1. `page_start` builds the doctype (`PHPWCMS_DOCTYPE` per `mode_XHTML`), charset, viewport (`$phpwcms['viewport']`), `<base>` (`base_href`), `<title>` from `$content['pagetitle']`, the deprecated page-layout CSS attributes, the template CSS `<link>` files and `$block['htmlhead']`, closes `</head>`, opens `<body>` with the configured id/class plus `$content['body_id']`.
2. With rewriting on, `url_search()` rewrites remaining `href="index.php?…"` and `onclick` URLs to `/<value><rewrite_ext>…` forms, and the `img/cmsimage.php` prefix shrinks to `im`.
3. `page_end` appends `$block['bodyjs']`, the optional browser-update.org script and `</body></html>`.
4. `render_clean_html` strips HTML comments when enabled.

Output: the buffered page flushes. `cache_timeout` (config, level `acat_timeout` or article `article_cache`) drives `Expires`, `Last-Modified` and `Cache-Control` response headers; per-level caching beyond headers is not implemented in the current frontend — the config key `cache_enabled` exists but the page pipeline no longer reads it. File based caches do exist for resized images, feeds and `{URL}` includes (`check_cache()`).

Finally `X-phpwcms-Release` and the processing-time header emit (unless disabled or whitelabeled), PDF requests route through `include/inc_front/pdf.inc.php`, `phpwcms_output_action` requests return only the requested, filtered sections, and `ob_end_flush()` ships the page.

## Related

- [Frontend scripts](frontend-scripts.md)
- [Replacement tags](replacement-tags/01-nav-list-ul.md)
- [Layout tags](../templates/layout-tags.md)