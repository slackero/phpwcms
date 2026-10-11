---
title: Glossary
description: Keywords highlighted as abbr tooltips on the frontend and managed in the backend.
sidebar_position: 13
---

# Glossary

> Source: `include/inc_module/mod_glossary/`

The glossary module holds term definitions and automatically highlights keywords in frontend pages as `<abbr>` tooltips. Module type: backend **and** frontend with its own content part and frontend search registration (`module.default.php`: `$_module_type = 0`, `$_module_contentpart = true`, `$_module_fe_render = true`, `$_module_fe_search = true`).

## Backend

Available to usergroups with the glossary module enabled. Entry fields: title, keyword list (comma separated), description text, status, and a *highlight* flag — only entries with `glossary_status = 1` **and** `glossary_highlight = 1` participate in frontend replacement. Storage: `phpwcms_glossary`.

## Content part

Selecting "Plug-in" content part type (30) with module *glossary* renders a keyword list on the article page (alphabet navigation, definition list rendering — `include/inc_module/mod_glossary/inc/cnt.*.php`).

## Frontend highlighting

The registered frontend render script wraps matching text with an abbreviation tooltip:

```text
[glossary CMS ]content management system[/glossary]
```

renders as

```html
<abbr class="glossary" title="CMS :: content management system">content management system</abbr>
```

- Keyword matching is exact per comma-separated keyword of an entry (`LIKE` variants cover head, middle and tail positions).
- Best matching entry wins (highest entry count); all matching keywords of that entry go into a per-request cache, so repeated keywords resolve once.
- Styling hook: class `glossary` on the `abbr` element. A jQuery sample ships in `include/inc_module/mod_glossary/template/` (`sample.jquery.tmpl`, `jquery.glossary.js`).

## Frontend search

Glossary text participates in the frontend search (registered via `$_module_fe_search`).

## Activation

Ships disabled for no one — module presence is auto-discovered (`include/inc_lib/modules.check.inc.php`); backend visibility and content part availability are controlled per usergroup. See [Users and usergroups](../administration/users-usergroups.md) and the [modules overview](index.md).