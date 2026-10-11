---
title: phpwcms modules
description: Informational backend panel presenting available phpwcms modules.
sidebar_position: 12
---
# phpwcms modules

> Source: `include/inc_module/mod_modules/`

This entry presents available phpwcms modules. It is purely informational — no install, uninstall or configuration actions, no database access. Module registration (`module.default.php`): `$_module_name = 'modulpromo'`, `$_module_type = 1` — backend only, no content part, no frontend hooks.

## Backend

Available to usergroups with the module enabled. Backend URLs use the registered internal name, not the directory name: `phpwcms.php?do=modules&module=modulpromo`.

`backend.listing.php` renders one intro line and three static cards. Card headers and texts come from `include/inc_module/mod_modules/lang/en.lang.php`; no card contains a link:

- "phpwcms shop module" — "…enables you to set up a simple online store." Covered by [Shop](01-shop.md) and its sub-pages.
- "phpwcms calendar module" — "…enables you to build a feature-rich calendar." Covered by [Calendar](05-calendar.md).
- "phpwcms user management module" — "…allows managing user data such as customers or association members."

Installed modules are not listed here; the panel promotes capabilities. Actual module availability and access live in discovery and the module groups — see [Modules overview](index.md).

## Configuration

None. The panel has no settings and no own tables.

## Related

- [Modules overview](index.md) — module architecture and per-module reference pages