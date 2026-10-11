---
title: Replacement tags
description: How replacement tags work and in which order the frontend pipeline resolves them.
sidebar_position: 0
---

# Replacement tags

> Source: `include/inc_front/content.func.inc.php` (replacement cascade), `include/inc_front/front.func.inc.php`, `include/inc_front/ext.func.inc.php`

Replacement tags are `{NAME}` or `{NAME:parameter}` markers that the frontend resolves to generated HTML, text or values. They are the template developer's layer between raw content and the finished page. phpwcms resolves them late in the request, after every content part has rendered, so a tag placed anywhere in a template — or pasted into any content field — gets the same treatment.

## Where replacement tags work

Everything the renderer produces ends up in one assembled string (`$content['all']` in `content.func.inc.php`), and the replacement cascade runs once over that string. Tags therefore work wherever you can write template text:

- **Layout templates** — the page skeleton managed in the admin panel.
- **Content part templates** — `template/inc_cntpart/**` and the defaults in `template/inc_default/`.
- **Admin header/footer code of a template** — the `HEADER` and `FOOTER` block fields stored in the template record.
- **Custom blocks** — additional blocks a layout template defines.
- **Structure level and article fields** — for example level keywords or a custom canonical value.

Layout block tags such as `{CONTENT}`, `{HEADER}`, `{LEFT}`, `{RIGHT}`, `{FOOTER}` are filled directly from the rendered article stack before the cascade runs; see [replacement tag pages](#reference) for what the cascade itself resolves.

## Processing order

`index.php` loads `content.func.inc.php`, renders all content parts of the current article (or the article list of the level), assembles the layout, and passes the result through the steps below (line references: `include/inc_front/content.func.inc.php`, current 2.x source):

1. `404` block handling: `[404]…[/404]` when the page was not found, `[404_ELSE]…[/404_ELSE]` otherwise.
2. Layout render (table, div or main-only) or print layout; `{CONTENT}` replaced by the rendered content, `{CURRENT_URL}` in print mode.
3. Custom blocks: `{KEY}` filled from `$content['CB']`, wrapped by `[KEY]…[/KEY]` / `[KEY_ELSE]…[/KEY_ELSE]`.
4. Content part tabs: `<!-- TAB_x -->` markers.
5. `[IF_LIST_MODE]` / `[ELSE_LIST_MODE]` sections.
6. Device conditionals: `<!--if:…-->…<!--/if-->` (first pass, `render_device()`).
7. `{CURRENT_ARTICLEID}` and `{CURRENT_CATEGORYID}`.
8. `[LEVEL<n>_ID]…[/LEVEL<n>_ID]` with the `{LEVEL<n>_ID}` replacer.
9. `[IF_CAT:id,id]` / `[IF_NOTCAT:…]` sections with the `{IF_CAT_ID}` / `{IF_NOTCAT_ID}` replacers.
10. `{SHOW_CONTENT:…}`.
11. `{SITE}` and `{TEMPLATE}`; then `render_PHPcode()` — `{PHP:…}`, `[PHP]…[/PHP]` and `{PHPVAR:…}` (only with `$phpwcms['enable_inline_php']`).
12. `{BREADCRUMB…}`; then `{NAV_LIST_UL:…}`; then the deprecated `NAV_*` family when `enable_deprecated` is set.
13. Fixed date and time tags: `{DATE_LONG}`, `{DATE_MEDIUM}`, `{DATE_SHORT}`, `{DATE_ARTICLE}`, `{TIME_LONG}`, `{TIME_SHORT}`, `{TIME_ARTICLE}`.
14. Search form placeholders `###search_input_###`.
15. `{RELATED:…}`, `{NEW:…}`, `{RSSIMG}`, `{KEYWORD:…}`, `{URL:…}`, `{BROWSE:…}`.
16. `html_parser()` — the text markup layer — plus all registered `$content['globalRT']` pairs. Depending on `$phpwcms['parse_html_mode']` this runs here (empty or `before`, the default), later instead (`after`), or twice (`before+after`).
17. Frontend render scripts: `template/inc_script/frontend_render/*.php` (when `allow_ext_render`) and module frontend renders — your custom hook layer; scripts can add their own `$content['globalRT']` entries here.
18. Device conditionals (final pass), `render_PHPcode()` again, `{LAZY_LOADING}`.
19. Automatic `description` / `keywords` meta, Open Graph and Twitter Cards, `generator` meta.
20. `[PRINT]` / `[PRINT_PDF]` links, then the article-level tags `{AUTHOR}`, the `{DATE:…}` family via `render_cnt_date()` and `{CATEGORY}`.
21. `{SOCIAL_SHARE…}`; `<!-- JS: … -->` and `<!-- CSS: … -->` comments.
22. Content part pagination: `{CP_PAGINATE}`, `{CP_PAGINATE_PREV}`, `{CP_PAGINATE_NEXT}`, `{CP_PAGINATE_CLASS}` and the `[CP_PAGINATE_MENU]`.
23. `LOGGED_IN` / `LOGGED_OUT` sections, `[HTML]` / `[HTML_SPECIAL]` conversion.
24. Cookie consent, tracking codes (GA/GTM/Piwik), alias URL rewriting for `index.php?aid/id` links, i18n `@@Text@@` and `[LANG:xx]` parsing, `{GT:…}` (deprecated).
25. `index.php` assembles the final document: doctype with `{DOCTYPE_LANG}`, base href, `<title>`, CSS files, custom `<head>` code, body tag, body JS — see the pipeline description in [core concepts](../../general/concepts.md).

`render_cnt_date()` (step 20) and the fixed `{DATE_*}`/`{TIME_*}` tags (step 13) run over the whole page, so date tags resolve anywhere — layout, header/footer and content part output alike.

## Custom replacement tags

A [frontend render script](#processing-order) (step 17) registers its own tags by adding key/value pairs to `$content['globalRT']`:

```php
$content['globalRT']['{MY_TAG}'] = 'replaced with this';
```

The pairs are applied during `html_parser()` / after the scripts with plain `str_replace`, so keep the key a literal string with braces. See the [replacement tag reference](#reference) and the deprecated list below for what is built in.

## Reference

| Page | Group |
| ---- | ----- |
| [NAV_LIST_UL](01-nav-list-ul.md) | ul/li navigation |
| [Site and server tags](02-site.md) | `{SITE}`, `{TEMPLATE}`, `{IP}`, `{LAZY_LOADING}` |
| [SHOW_CONTENT](03-show-content.md) | embed articles and content parts |
| [Article tags](04-article.md) | `{AUTHOR}`, `{CATEGORY}`, article summary fields |
| [Content part pagination](05-cp-paginate.md) | `{CP_PAGINATE}` and related tags |
| [Meta and head tags](06-meta.md) | page title, canonical, Open Graph, `{DOCTYPE_LANG}` |
| [Date and time](07-date-time.md) | `{DATE_*}`, `{TIME_*}`, `{DATE:…}` family |
| [Login functions](08-login-functions.md) | `LOGGED_IN`/`LOGGED_OUT`, frontend login tags |
| [RSS](09-rss.md) | `{RSSIMG}`, feed links |
| [Functions and misc](10-functions-misc.md) | `{NEW}`, `{RELATED}`, `{BROWSE}`, `{URL}`, `{BREADCRUMB}`, search form, leftovers |

## Deprecated replacement tags

The following tags still exist but render only when `$phpwcms['enable_deprecated']` is set in `config.inc.php`:

- Navigation: `{NAV_LIST}`, `{NAV_LIST_TOP}`, `{NAV_LIST_CURRENT}`, `{NAV_ROW}`, `{NAV_ROW:…}`, `{NAV_TABLE_SIMPLE}`, `{NAV_TABLE_COLUMN}`
- Images: `{RANDOM:…}`, `{RANDOM_GIF:…}`, `{RANDOM_JPEG:…}`, `{RANDOM_PNG:…}`, `{IMAGE:…}`, `{IMAGE_LEFT:…}`, `{IMAGE_RIGHT:…}`, `{IMAGE_CENTER:…}`
- The deprecated HTML pass wraps a second set of legacy text markers; these are all listed in [deprecated tags](../deprecated-tags.md).

Additional deprecated behavior outside that switch: `{GT:…}` renders only with `$phpwcms['gt_mod']`, and `image_resized.php` image URLs are rewritten to the current resize path on output. Both are covered by [deprecated tags](../deprecated-tags.md) — the pages above deliberately do not duplicate that reference.