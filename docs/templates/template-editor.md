---
title: Template editor
description: The admin forms for page layouts (p=8) and layout templates (p=11) with all fields and how they persist.
sidebar_position: 3
---
# Template editor

> Source: `include/inc_tmpl/admin.pagelayout.tmpl.php`, `include/inc_tmpl/admin.templates.tmpl.php`, `include/inc_act/act_frontendsetup.php`

Both editors live under **Admin** in the backend. Deleting happens through `include/inc_act/act_frontendsetup.php`: `?do=1|<id>` removes a page layout, `?do=2|<id>` removes a template. The template list also offers a copy action (`&c=1`). Every save runs `update_cache()`.

## Page layout editor (p=8)

Defines how the page blocks assemble. Saved serialized into `phpwcms_pagelayout.pagelayout_var` with the name in `pagelayout_name`.

> Source: `include/inc_tmpl/admin.pagelayout.tmpl.php` (POST branch lines 68-212)

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Name | text | auto timestamp name | List label |
| Default | checkbox | off | Only one page layout may carry the flag; saving clears it on all others |
| Render | radio: custom / table / div | custom (2) | Assembly mode — custom (2) uses the template's block texts as-is, table (0) and div (1) wrap header/left/content/right/footer into classic structure |
| Custom blocks | text | empty | Comma separated block names, uppercased, spaces normalized, max 20 chars each; `CONTENT`, `LEFT`, `RIGHT`, `HEADER`, `FOOTER`, `CPSET`, `SYSTEM` are rejected |
| Pagetitle | text | `Pagetitle` | Base page title |
| Add to title | select | pagetitle, category, articlename | Title composition order, 15 options including short forms (value 0-14, see `setPageTitle()`) |
| Break | text | ` \| ` | Spacer between title parts |
| Align / margin / background / link colors / onload | text or radio | empty | Only used by the table and div render modes; hidden behind a jQuery toggle when render mode is custom |
| Column type | radio: 4-, 3-, 2-, 1-column | 3-column style (0) | Which of left/right blocks the table/div layout renders |
| Width/color/background/class per block | text | empty | Fields for *all blocks*, left, left space, main (content), right space, right, header, top space, bottom space, footer |

All block dimensions accept pixel or percent values (`get_pix_or_percent()`). The background fields of the top area exist only for the classic render modes.

## Template editor (p=11)

Defines the content of blocks plus behavior. Saved serialized into `phpwcms_template.template_var`. A `&c=1` copy pre-fills the form with a suffixed name.

### Base

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Name | text | random if empty | List label |
| Set as default | checkbox | off | Used when a structure level has no template |
| Page layout | select | first entry | Which page layout assembles the block; changing it with block-relevant differences triggers a confirm warning (`doPageLayoutChange()`) |
| Onepage | switch | off | Sets `IS_ONEPAGE_TEMPLATE`, see [template system](template-system.md) |
| Overwrite | select | empty | PHP file from `template/inc_settings/template_default/` that overrides `$template_default` values |

### CSS and login URL

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| CSS | multi-select | empty | Files from `template/inc_css/`, rendered as `<link>` tags in page order |
| Frontend login URL | text | site URL | Redirect target for regonly levels; `{SITE}` inside is replaced by the site URL, otherwise `$template_default['login_form_url']` applies |

### Blocks

Each block is a code textarea with mode-dependent height: header, main, left, right, footer, plus every custom block declared in the page layout, plus the error block. Every block additionally offers a file select listing `template/inc_cntpart/template-sections/<dir>/` (see [template sections](template-system.md#file-based-template-sections)). The file content wins over the textarea content.

The error block understands the `[404]…[/404]` and `[404_ELSE]…[/404_ELSE]` sections.

### HTML head and JavaScript

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| htmlhead | textarea | empty | Markup emitted into `<head>` verbatim (metas, links, scripts) |
| JavaScript library | select | first of `$phpwcms['js_lib_default']` | Wrapper from `include/inc_front/lib/js.<name>.inc.php`; invalid values fall back to the `jquery-3.7-migrate-1` wrapper |
| Load library permanently | checkbox | off | `jslibload` — calls `initJSLib()` on every page |
| Use CDN | checkbox | on | `googleapi`/`use_cdn` — sets `PHPWCMS_USE_CDN` |
| onload | text | empty | Comma semicolon list of JS calls merged into the DomReady block |
| frontend.js | checkbox | off | Always loads `template/inc_js/frontend.js` |

### Tracking

Google Analytics (gtag.js), Google Tag Manager and Matomo/Piwik each carry an enable switch plus ID fields; none render without a matching ID. GA options: anonymize IP, show optout script, cookie flags, custom properties as a JS object snippet. Piwik needs a host URL without scheme plus site ID. Trackings respect `$phpwcms['donottrack']` cookie consent state.

### Cookie consent

Two variants, the 2.x v3 flavor (orestbida) wins when both are on:

| Group | Fields |
| ----- | ------ |
| v2 | message, dismiss, more, link + target, theme (files `template/lib/cookieconsent2/`) |
| v3 | enable, reload on change, texts (title, description, accept all, accept necessary, accept selected, reject all, customize, more, link), theme, consent and preferences modal layout/position/button options, per-category sections `general`, `necessary`, `functionality`, `analytics`, `marketing`, `social`, `more` with active flag, title and description |

Translations load from `template/lib/cookieconsent3/lang/<lang>.json` with English fallback.

Additionally: *Do not track* respect (`block['donottrack']`, evaluates the browser DNT header) and *Require consent* (block rendering of scripts until the consent cookie named under the given name/value exists).

## Storage and caching

Both editors write a serialized PHP array. The template editor merges loaded values with `array_replace_recursive()` over the defaults above, so newly added option keys survive upgrades of old data rows. Single legacy CSS strings are normalized to arrays on load. Saving also updates the backend cache (`update_cache()`), so list views and structure forms see the change immediately.

## Related

- [Template system](template-system.md)
- [Default templates](default-templates.md)