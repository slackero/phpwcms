---
title: NAV_LIST_UL
description: ul/li cascading navigation from the structure level tree, with folding, Bootstrap and article-menu variants.
sidebar_position: 1
---
# NAV_LIST_UL

> Source: `include/inc_front/content.func.inc.php` (tag dispatch), `include/inc_front/front.func.inc.php` (`buildCascadingMenu()`)

The primary navigation replacement tag. Renders the structure level tree as `ul/li` markup. Works in layout templates, content parts and admin header/footer code alike; there is no restriction on how often you place it.

## Syntax

```
{NAV_LIST_UL: menu_type, start_id, max_depth, class_path, class_active, ul_id_name, wrap_ul_div, wrap_link_text, articlemenu}
```

All parameters comma separated. Unset trailing parameters can be left blank (`,,`). No `{`/`}` needed around class names themselves.

## Parameters

| # | Name | Default | Description |
| - | ---- | ------- | ----------- |
| 1 | menu_type | *(empty)* | Output variant, see table below. |
| 2 | start_id | 0 | Structure level ID to start from; 0 = root. |
| 3 | max_depth | 0 | Maximum level depth; 0 = all levels. |
| 4 | class_path | *(empty)* | CSS classes for the active path, `|` separated. |
| 5 | class_active | *(empty)* | CSS classes for the active link, split as `class\|extra`. |
| 6 | ul_id_name | *(empty)* | Optional `id` for each level `ul` (`<ul id="…-0">` etc.). |
| 7 | wrap_ul_div | 0 | `0` off, `1` `<div>`, `2` `<div id="…">`, `3` `<div class="navLevel-0">`. Values >3 fall back to 2, <0 to 0. |
| 8 | wrap_link_text | *(empty)* | Link text wrapper: `prefix|suffix` around the `<a>` text. |
| 9 | articlemenu | *(empty)* | Article list options, only relevant for article-menu types — see below. |

## Menu types

| Type | Behavior |
| ---- | -------- |
| *(empty)* | Plain full menu, all levels unfolded. |
| `A` | Include article titles of each level as menu items. |
| `AH` | `A`, but skip the first (avoids doubling the parent link). |
| `B` | Bootstrap dropdown compatible `<li class="dropdown">` markup. |
| `BA` / `BAH` | Bootstrap + articles (+ hide first). |
| `P` | Also render a parent-level link. |
| `PA` / `PBA` / `PBAH` | Parent variants of the article types. |
| `F` | Folded: only the active path unfolds. |
| `FA` / `FAH` / `FP` / `FPA` / `FPAH` | Folded combined variants (fold + articles / parent). |
| `HCSS` / `HCSSP` | Horizontal sample CSS menu, optionally with parent. |
| `VCSS` / `VCSSP` | Vertical sample CSS menu, optionally with parent. |

`HCSS*`/`VCSS*` emit `<div class="navmenu-…">` scaffolding meant as starting points for custom CSS.

## Article menu options (9th parameter)

Only used by types containing `A`. Pipe separated, colon separated sub-values:

```
{NAV_LIST_UL:FAH,0,3,,nav-active,menu,2,|,[level]x300x80x1[|description:40|inside|<span class="…">}  (illustrative composition)
```

| Slot | Values | Purpose |
| ---- | ------ | ------- |
| 1 | `level` (or ID) | Per-level article menu — used to scope rendering. |
| 2 | `W×H[×1]` | Article summary image size; `×1` enables crop. |
| 3 | `description[:N]`, `menutitle[:N]`, `teaser[:N]`, `teaser:HTML` | Text source: summary description, level title or teaser text; `N` = character limit. |
| 4 | `inside` | Render the summary span inside the link instead of after it. |
| 5 | HTML | Custom wrap template for the summary, `%IMAGE%`, `%IMAGE_NAME%`, `%TEXT%` placeholders, default `<span class="amenu-extended">…</span>`. |

## Output

The renderer produces nested `<ul>`s, one class per depth (`navLevel-<depth>` naming when `wrap_ul_div` is 3), with the active path marked by the classes from parameter 4/5. Article entries reuse the summary image and teaser text configured above.

Typical layout template usage:

```html
<nav>{NAV_LIST_UL:F,0,2,active-path,active,mainnav,1,|</nav>
```

## Deprecated related tags

`{NAV_LIST}`, `{NAV_LIST_TOP}`, `{NAV_LIST_CURRENT}`, `{NAV_ROW}`, `{NAV_TABLE_SIMPLE}`, `{NAV_TABLE_COLUMN}` still exist but are replaced only when `enable_deprecated` is active in `config.inc.php` — do not use them in new templates. See [deprecated tags](../deprecated-tags.md).