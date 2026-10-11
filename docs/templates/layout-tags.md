---
title: Layout tags
description: Every replacement tag and block section a page layout template and its block texts understand.
sidebar_position: 2
---
# Layout tags

> Source: `include/inc_front/content.func.inc.php`, `include/inc_front/content.article.inc.php`, `index.php`, `include/inc_front/front.func.inc.php`

This page documents the tags available to a **layout template** (block texts plus the custom render markup) and to the print templates. Replacement tags that generate navigation are documented under [NAV_LIST_UL](../frontend/replacement-tags/01-nav-list-ul.md); content part template tags belong to their [content part](../content-parts/index.md) page. Tags work in content parts and custom block texts alike — there is no scoping at the tag level.

Two notations exist:

- `{TAG}` — a replaceable single tag, sometimes with parameters (`{TAG:…}`).
- `[TAG]…[/TAG]` — a conditional section: content stays when the value is non-empty, otherwise the `_ELSE` variant `[TAG_ELSE]…[/TAG_ELSE]` renders. `render_cnt_template()` handles both.

## Content blocks

The block names come from the page layout. The five predefined ones exist on every install; additional blocks are defined by the page layout field *Custom blocks* (comma-separated, uppercase, max 20 characters per name).

| Tag | Filled by |
| --- | --------- |
| `{CONTENT}` | The rendered article stack (list mode or article detail), produced by the content part renderers |
| `{HEADER}` | Content parts assigned to block `HEADER` |
| `{FOOTER}` | Content parts assigned to block `FOOTER` |
| `{LEFT}` | Content parts assigned to block `LEFT` |
| `{RIGHT}` | Content parts assigned to block `RIGHT` |
| `{<CUSTOMBLOCK>}` | Content parts assigned to that custom block |

The template block texts (`headertext`, `footertext`, `lefttext`, `righttext` in the template editor and the `maintext` main text) carry these tags. In table and div render modes the page layout places each block text at its fixed position; if the main text is empty, the rendered article stack takes its place directly. When a block text holds the placeholder (as in `<header>{HEADER}</header>`), assigned content parts land there; when the block text is empty, the raw content parts insert at the pagelayout cell instead. Each block understands the conditional form `[BLOCK]…[/BLOCK]` and `[BLOCK_ELSE]…[/BLOCK_ELSE]` for markup that reacts to empty or filled blocks.

Content parts choose their target block per content part (acontent_block). A content part assigned to a custom block lands in `{<CUSTOMBLOCK>}`; the reserved names `CONTENT`, `LEFT`, `RIGHT`, `HEADER`, `FOOTER`, `CPSET` and `SYSTEM` cannot be defined as custom blocks.

> **Note:** Tags such as `{CONTENT_LEFT}` or `{CONTENT_TOP}` do not exist. Column content uses the plain block names above.

## Page title and meta

There is no `{TITLE}` or `{META}` layout tag. `<title>` and the meta tags are emitted automatically:

- `<title>` is composed by `setPageTitle()` from the page layout's *Pagetitle* field, the level name and the article title, in the order selected per page layout (`layout_title_order`, 15 combinations) joined by the break string (`layout_title_spacer`, default ` | `). An article or level pagetitle field overrides the composition.
- `meta description` — auto-filled from the level's description when none exists in the htmlhead, else from `article_description`.
- `meta keywords` — auto-collected from article and level keywords; `*CSS-class*` tokens are stripped first.
- canonical link — see [URL handling](../general/url-handling.md#canonical-handling).

## Current page context

| Tag | Value |
| --- | ----- |
| `{CURRENT_ARTICLEID}` | Current article ID, `0` in list mode |
| `{CURRENT_CATEGORYID}` | Current structure level ID |
| `{LEVEL<n>_ID}` / `[LEVEL<n>_ID]…[/LEVEL<n>_ID]` | Structure level ID at breadcrumb depth `<n>` (0 = root); the bracket form keeps content only when the level exists |
| `[IF_CAT:id,id,…]…[/IF_CAT]`, `{IF_CAT_ID}` | Keeps content when the current level matches one of the IDs; `{IF_CAT_ID}` holds the matching ID |
| `[IF_NOTCAT:id,id,…]…[/IF_NOTCAT]`, `{IF_NOTCAT_ID}` | Keeps content when the current level matches none of the IDs |

## Site paths

| Tag | Value |
| --- | ----- |
| `{SITE}` | Absolute site URL including installation folder (`PHPWCMS_URL`) |
| `{SITE_URL}` | Host name in mail and system contexts (`PHPWCMS_HOST`); in page markup use `{SITE}` |
| `{TEMPLATE}` | Relative template directory path (`TEMPLATE_PATH`) |
| `{PHPWCMS_URL}`, `{PHPWCMS_TEMPLATE}` | Aliases of the two above, also active in mail text and non-cached strings via `replaceGlobalRT()` |
| `{LAZY_LOADING}` | `loading="lazy" decoding="async"` or empty, per `$phpwcms['lazy_loading']` |
| `{PHPWCMS_RESIZE_IMAGE}` | `img/cmsimage.php`, or `im` when URL rewriting is on |

## PHP and custom code

| Tag | Behavior |
| --- | -------- |
| `{PHP:<file-or-url>}` | Includes a PHP script and returns its buffered output; local paths must stay inside `PHPWCMS_ROOT`, remote URLs additionally need `$phpwcms['allow_remote_URL']` |
| `[PHP]code[/PHP]` | Evaluates a PHP code block — `eval`, `exec`, `system` and similar functions are forbidden and return a comment placeholder |
| `{PHPVAR:var}` | Returns a global variable value; `$phpwcms` access is masked out |

All three require `$phpwcms['enable_inline_php']` in `config.inc.php`; otherwise `render_PHPcode()` strips them with `remove_unsecure_rptags()`. Inside content parts the pair `$phpwcms['allow_cntPHP_rt']` and `$phpwcms['enable_inline_php']` decides whether rendered content keeps PHP tags. The `[PHP]…[/PHP]` form runs early in the replacement cascade, so its output becomes page markup. See also `$content['globalRT']`: a [frontend render script](../frontend/frontend-scripts.md) may register custom global tags which `html_parser()` then replaces in one pass.

## Navigation and structure

| Tag | Purpose |
| --- | ------- |
| `{NAV_LIST_UL:…}` | Primary ul/li navigation — full parameter reference under [NAV_LIST_UL](../frontend/replacement-tags/01-nav-list-ul.md) |
| `{BREADCRUMB}`, `{BREADCRUMB:<depth>}`, `{BREADCRUMB:<depth>,<flag>}` | Breadcrumb path from the level tree; `<flag>` = 1 shows the current page as plain text |
| `[PRINT]…[/PRINT]` | Anchor to the print view of the current page, class `link-print` |
| `[PRINT_PDF]…[/PRINT_PDF]` | Anchor to the PDF view (needs `wkhtmltopdf_path`), class `link-print-pdf` |
| `{SOCIAL_SHARE}` / `{SOCIAL_SHARE:options}` | Social share links rendered by `render_social_share()` |
| `{RSSIMG}` | Default RSS icon defined in `conf.template_default.inc.php` |

## Dates and times

| Tag | Value |
| --- | ----- |
| `{DATE_LONG}`, `{DATE_MEDIUM}`, `{DATE_SHORT}` | Current date in the formats from `conf.template_default.inc.php` |
| `{DATE_ARTICLE}` | Article date, short format |
| `{TIME_LONG}`, `{TIME_SHORT}` | Current time; `{TIME_ARTICLE}` article time |
| `{DATE:<format>}`, `{NOW:<format>}` | Any PHP `date()` format, optionally `SET:<timestamp>` |
| `{LIVEDATE:<format>}`, `{KILLDATE:<format>}` | Article start/end date, section forms `[LIVEDATE]…[/LIVEDATE]` apply |
| `{AUTHOR}` | Article author name, or level related fallback |
| `{CATEGORY}` | Current level name |

## Content selection

| Tag | Purpose |
| --- | ------- |
| `{SHOW_CONTENT:MODE,parameter[,parameter…]}` | Pulls articles or content parts into any block. Modes: `AS`, `ASL*`/`AST*` sort variants (livedate/killdate/change/keyword, `D` suffix = descending, `R` = random), `CP` (by content part IDs), `CPS`, `CPC`, `CPAD`/`CPAS`/`CPASD`. Optional `AS:<count>|<list template>` form |
| `{RELATED:<count>}`, `{RELATED:<count>:<opts>}` | Articles sharing the current article's keywords |
| `{NEW:<count>:<start>}` | Newest articles, sorted per `template_default['news']['sort_by']` |
| `{KEYWORD:<keywords>}` | Turns a keyword list into links to matching articles |
| `{URL:<url>}[,CACHE=<seconds>]` | Includes the `<body>` of an external HTML page; with a trailing space and `CACHE=` seconds the result caches under `content/tmp/` |
| `{BROWSE:UP:<text>}`, `{BROWSE:NEXT:<text>,<catdown>}`, `{BROWSE:PREV:<text>,<catup>}` | Links to the parent level / next article / previous article |

## Search form markers

Hand-built search forms use three markers, replaced by `get_search_action()`:

| Marker | Value |
| ------ | ----- |
| `###search_input_field###` | Passthrough identifier of the text field |
| `###search_input_value###` | Current search word, if any |
| `###search_input_action:<article-id>###` | Form action pointing at the search article |

## Print mode sections

`?print=1` (HTML) and `?print=2` (PDF) switch the template: `template/inc_default/print.tmpl` or `pdf.tmpl` (or `{CONTENT}<hr/>{CURRENT_URL}` when the file is missing). Both understand `{CONTENT}` and `{CURRENT_URL}`; the PDF template may hold a `[PDF_SETTINGS]…[/PDF_SETTINGS]` INI section parsed by `parse_ini_str()` before `wkhtmltopdf` runs. The result then flows through the regular replacement cascade, so page level tags apply to print output as well.

| Section | Effect |
| ------- | ------ |
| `[NO_PRINT]…[/NO_PRINT]` | Removed in print/PDF output, neutral markup comment otherwise |
| `[PDF_SETTINGS]…[/PDF_SETTINGS]` | INI options for `wkhtmltopdf`, PDF only |

## Login state sections

| Section | Effect |
| ------- | ------ |
| `[LOGGED_IN]…[/LOGGED_IN]` | Content only for logged in frontend users |
| `[LOGGED_OUT]…[/LOGGED_OUT]` | Content only for anonymous visitors |

There are no `{LOGIN_*}` layout tags. The frontend login form is a content part (28, FE Login) with its own `LOGIN_FORM` templates under `template/inc_cntpart/felogin/`, and `template/inc_script/felogin/` provides an ini-driven variant including the `{FELOGIN_USER}`, `{FELOGOUT_PREFIX}` and `{FELOGOUT_SUFFIX}` tags inside its own templates.

## List mode, pagination and error sections

| Section / tag | Effect |
| ------------- | ------ |
| `[IF_LIST_MODE]…[/IF_LIST_MODE]` | Content only while the level renders an article list |
| `[ELSE_LIST_MODE]…[/ELSE_LIST_MODE]` | Content only in article detail mode |
| `[404]…[/404]` | Error block content when a 404 was detected |
| `[404_ELSE]…[/404_ELSE]` | Error block content when the page renders with no content |
| `{CP_PAGINATE}` | Page numbers `[1][2][3]` of the content part pagination, wrapped per `template_default['attributes']['cp-paginate']` |
| `[CP_PAGINATE_PREV]`, `[CP_PAGINATE_NEXT]` | Hold the previous/next page URL when one exists |
| `[CP_PAGINATE_MENU]`, `[CP_PAGINATE_MENU:first|last|active|hide|wrap…]` | Title menu of the paginated content parts |
| `[CP_PAGINATE]…[/CP_PAGINATE]` | Wraps the pagination list; the START/END comments are stripped when pagination is inactive |

## Head injection comments

Anywhere in template or content:

```
<!-- JS: jquery.ui.slider -->
<!-- JS: https://cdn.example.com/widget.js -->
<!-- JS: ready| jQuery(".gallery").glightbox(); -->
<!-- CSS: my-extension.css -->
<!-- CSS: body{margin:0} -->
```

- A name without extension loads `template/lib/jquery/plugin/jquery.<name>.js` via `initJSPlugin()`.
- A `.js` file or `http(s)://`/`//` URL links as external script; `{TEMPLATE}` inside is replaced first.
- A name known to the installed JS library (e.g. jQuery plugins of `template/lib/jquery/plugin/`) loads through the lib wrapper.
- `ready`, `domready` or `onload` followed by code executes the code on DOM ready (jQuery wrapped when the code uses `$()` or `jQuery()`).
- Inline JavaScript without a `ready` prefix is emitted into the head once per unique code.
- CSS with a `.css` extension links as external stylesheet; everything else becomes an inline `<style>` block.

## Language blocks

With `$phpwcms['lang_parse']` and `$phpwcms['allowed_lang']`:

- `[LANG:de]…[/LANG:de]` and the legacy `[de]…[/de]` keep content for the active frontend language and strip it otherwise.
- `@@Text@@` tokens resolve against the language translation tables when `$phpwcms['i18n_parse']` is on.

## Deprecated tags

`{NAV_ROW}`, `{NAV_LIST}`, `{NAV_LIST_TOP}`, `{NAV_LIST_CURRENT}`, `{NAV_TABLE_SIMPLE}`, `{NAV_TABLE_COLUMN}`, `{RANDOM}`, `{IMAGE}`-family and `{GT:…}` render only with `$phpwcms['enable_deprecated']` or `$phpwcms['gt_mod']` — inventory in [deprecated tags](../frontend/deprecated-tags.md).

## Replacement order

The tags above are processed in a fixed sequence, not alphabetically. The exact top-to-bottom cascade lives in [page rendering](../frontend/page-rendering.md#replacement-cascade).

## Related

- [Template system](template-system.md)
- [Default templates](default-templates.md)