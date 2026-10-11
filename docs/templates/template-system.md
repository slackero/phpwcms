---
title: Template system
description: Layout templates, page layouts, content part templates and how the chosen template resolves per request.
sidebar_position: 1
---
# Template system

> Source: `include/inc_front/content.func.inc.php`, `include/inc_tmpl/admin.templates.tmpl.php`, `include/inc_tmpl/admin.pagelayout.tmpl.php`

phpwcms separates layout from content with three independent pieces. You edit them all in the backend admin panel, and every piece has a file based fallback layer under the active `template/` directory.

| Piece | Managed in | Database | File fallback |
| ----- | ---------- | -------- | ------------- |
| **Page layout** | Admin &rarr; Page layout (`p=8`) | `phpwcms_pagelayout` (serialized in `pagelayout_var`) | only through template sections, see below |
| **Layout template** | Admin &rarr; Templates (`p=11`) | `phpwcms_template` (serialized in `template_var`) | `template/inc_settings/template_default/` overrides |
| **Content part template** | selected per content part | filename stored in `acontent_template` | `template/inc_cntpart/<type>/`, then `template/inc_default/` |

## Layout template versus page layout

The layout template holds the editable skeleton of a page: the HTML head extensions, the block texts — header, main, left, right, footer, error — plus classes and settings for tracking, cookie consent and the JavaScript library. All of it serializes into `phpwcms_template.template_var` and arrives in the frontend as the `$block` array.

The page layout defines how the blocks are *assembled*: render mode (table, div, custom), column type, spacing and per-block width or background settings. It serializes into `phpwcms_pagelayout.pagelayout_var` and arrives as `$pagelayout`.

Both are required. If no page layout exists at all the frontend stops with a login notice, because a page cannot be assembled.

## Template fallback chain

### Which layout template is used

| Order | Source |
| ----- | ------ |
| 1 | The template ID assigned to the current structure level (`acat_template`) |
| 2 | The template flagged as default (`template_default = 1`) |
| 3 | Any non-deleted template, ordered `template_default DESC` |

The chain runs in `content.func.inc.php` right after the breadcrumb is built. A copy operation in the template list (`&c=1`) creates a new template row with the same serialized settings.

### Which page layout is used

The chosen template references a page layout by ID (`template_layout`). If the template has none, the page layout flagged `pagelayout_default = 1` is used.

### Which content part template is used

Each content part renderer follows the same three-step chain:

1. The template filename selected in the content part form (`acontent_template`) — loaded from `template/inc_cntpart/<type>/<name>.tmpl` when it exists.
2. The type's default template `template/inc_default/<name>.tmpl` when no template was selected.
3. A hard-coded minimal markup inside the renderer itself, when neither file exists.

Example from the text-with-image renderer `cnt1.article.inc.php`: `inc_default/imagetext.tmpl` &rarr; `inc_cntpart/imagetext/<selected>` &rarr; inline fallback. Type 29 (image div) additionally picks `list.images.tmpl` when the content part runs in list mode. Image special (31) picks `list.imagespecial.tmpl` accordingly.

Content part templates may carry `<!--SECTION_NAME_START//-->…<!--SECTION_NAME_END//-->` blocks that the renderer reads with `get_tmpl_section()`; unneeded sections are stripped with `replace_tmpl_section()`.

## File based template sections

A template can pull each block text from a file instead of the database field. In the template editor, every block offers a file select fed by `template/inc_cntpart/template-sections/`:

| Block field | Directory under `template/inc_cntpart/template-sections/` |
| ----------- | ---------------------------------------------------------- |
| htmlhead | `head/` |
| header text | `header/` |
| main text | `main/` |
| footer text | `footer/` |
| left text | `left/` |
| right text | `right/` |
| error text | `error/` |

Custom blocks use the lowercase block name as directory (`<customblock>_file`). When the file exists its content replaces the template's block text. This lets you keep template sections in version control.

> **Note:** Serialized templates that still hold a single CSS string (very old rows) are normalized to an array at load time.

## Template default overrides

Two file based override layers can rewrite `$template_default` values (classes, date formats, attributes, tracking snippets, custom fields):

1. The template's `overwrite` select — a file inside `template/inc_settings/template_default/` (`block['overwrite']`).
2. The structure level's overwrite file (`acat_overwrite`), applied after the template one.

Both are filtered against `..` (no path traversal) and included as plain PHP. The template editor lists all `*.php` files in that directory (see [template editor](template-editor.md)).

## Multi-device rendering with render_device

`$phpwcms['render_device']` must be enabled in `config.inc.php` for device markup to work:

`<!--if:condition-->…<!--/if-->` renders its content only when the user agent matches, `<!--!if:condition-->…<!--/!if-->` only when it does not match.

Conditions are `key:value` pairs separated by `;` (OR within one key, AND across keys):

| Key | Values |
| --- | ------ |
| `mobile` | no value — any mobile device |
| `desktop` | no value — any non-mobile device |
| `platform` | WinPhone, WinCE, Win, iOS, Mac, GoogleTV, Android, BlackBerry, WebOS, Linux, Unix, Symbian, Other |
| `device` | Default, Other, Smartphone, Tablet, Desktop, TV |
| `browser` | Other, Firefox, Chrome, Safari, IE, IEMobile, Opera, Mozilla |
| `engine` | Gecko, Other, WebKit, Opera, KHTML |
| `version` | comparison against the browser version, e.g. `>=10`, `<68` |

`render_device()` processes the assembled page twice, once before the replacement cascade and once after the frontend render scripts, so device blocks written by scripts are caught too. The user agent classification comes from `phpwcms_getUserAgent()`.

## Onepage templates

The template editor offers a **onepage** switch (`block['onepage']`). It defines the constant `IS_ONEPAGE_TEMPLATE`, which makes the navigation link levels as `#<alias>` anchors instead of separate URLs — the entire site lives on one page. Per level, `acat_onepage` extends this behavior (see `NAV_LIST_UL` in [the navigation replacement tag](../frontend/replacement-tags/01-nav-list-ul.md)).

## CSS and JavaScript collection

The frontend collects styles and scripts from four sources, all rendered into the page head (or before `</body>` when `$phpwcms['js_in_body']` is on, see [page rendering](../frontend/page-rendering.md)):

1. **Template CSS field** — multi-select of files from `template/inc_css/`; each becomes a `<link rel="stylesheet">` pointing at `TEMPLATE_PATH` + `inc_css/<file>`.
2. **htmlhead block** — free markup the administrator maintains in the template editor; rendered verbatim into `<head>`.
3. **`<!-- JS: … -->` / `<!-- CSS: … -->` comments** — anywhere in the page markup; registered via `renderHeadJS()`/`renderHeadCSS()`, detailed in [assets and libs](assets-and-libs.md).
4. **`$block['custom_htmlhead']`** — array entries appended by renderers and frontend scripts (metas, scripts, links). Scripts without the `head_` key prefix can be moved above the closing body tag by `js_in_body`.

The JavaScript library itself is chosen per template (`template_jslib`); the wrapper file `include/inc_front/lib/js.<lib>.inc.php` then registers the actual `<script>` tags. When no valid library is selected, the fallback `jquery-3.7-migrate-1` wrapper applies. `$block['jslibload']` loads the library on every page, `$block['googleapi']` allows CDN URLs (`PHPWCMS_USE_CDN`), and `$block['frontendjs']` always loads `template/inc_js/frontend.js`.

## Related

- [Layout tags](layout-tags.md) — every tag a layout template understands
- [Template editor](template-editor.md) — the backend forms behind `phpwcms_template` and `phpwcms_pagelayout`
- [Default templates](default-templates.md) — the files in `template/inc_default/`
- [Assets and libs](assets-and-libs.md) — what ships under `template/lib/` and how it loads