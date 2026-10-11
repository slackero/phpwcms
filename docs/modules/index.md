---
title: Modules
description: Module architecture, activation and the module reference overview.
sidebar_position: 0
---

# Modules

> Source: `include/inc_lib/modules.check.inc.php`, `include/inc_module/<module>/module.default.php`

Modules are optional functional units shipped in `include/inc_module/`. Each module lives in a single directory and adds backend pages, database tables and — where applicable — content parts and frontend behavior. phpwcms ships the modules listed in [the page table](#reference-pages); third-party modules follow the same structure.

## Architecture

### Discovery

`include/inc_lib/modules.check.inc.php` scans the subdirectories of `include/inc_module/` on every request. A directory counts as a module only when both `module.default.php` and `lang/en.lang.php` exist inside it — English is the mandatory base language, merged from `lang/<BE_LANGUAGE>.lang.php` when present. Discovery is automatic: dropping a module directory into `include/inc_module/` registers it, deleting the directory unregisters it. No install step exists.

### Registration variables

`module.default.php` sets the module metadata. All variables are read by the discovery loop and stored in `$phpwcms['modules'][$_module_name]`:

| Variable | Type | Values | Stored as |
| -------- | ---- | ------ | --------- |
| `$_module_name` | string | Lower case, no spaces or special characters | Key under `phpwcms['modules']` |
| `$_module_type` | int | 0 = backend and frontend, 1 = backend only, 2 = frontend only | `type` |
| `$_module_contentpart` | bool | True to list in the content part selection | `cntp` |
| `$_module_fe_render` | bool | Registers `frontend.render.php` for the replacement tag pass | `search`/`setting` flags plus the render list |
| `$_module_fe_init` | bool | Registers `frontend.init.php` for the early frontend pass | Init list |
| `$_module_fe_search` | bool | True to participate in frontend search | `search` |

A module that sets `$_module_contentpart = true` needs the content part bridge files under `inc/` (see [Bridging into content parts](#bridging-into-content-parts)). Language keys go into `$BL['modules'][$_module_name]`; every module defines `backend_menu` and `listing_title` there.

### Frontend hooks

Registered module scripts run in the frontend render pipeline (`include/inc_front/content.func.inc.php`):

| Hook | Script | Run |
| ---- | ------ | --- |
| Init | `frontend.init.php` | Early in `content.func.inc.php` (line 641) |
| Render | `frontend.render.php` | In the replacement tag pass (line 1148) |

The init hook handles request-level tasks (shopping cart, address lookups); the render hook parses module replacement tags in the finished page markup.

### Backend access and usergroup gating

Two conditions gate the **Modules backend menu** (`phpwcms.php`):

1. The configuration key `$phpwcms['enable_backend_module']` must be true (default true, `include/config/dist.conf.inc.php` line 166). `$phpwcms['enable_backend_newsletter']` gates the separate newsletter menu the same way.
2. The user must belong to the SYSGROUP permission group with syskey `module`.

Each module entry in the menu additionally requires membership of the [module group](../administration/users-usergroups.md#module-groups) whose `group_modkey` holds the module's internal name; the group editor seeds one matching group per module.

### Bridging into content parts

Content part **type 30** ("Plug-in") carries all module content parts; its number is never used directly in the content part type list (`include/inc_lib/article.contenttype.inc.php` line 12). When any module sets `$_module_contentpart = true`, the type list gains one "Plug-in" entry whose options are the individual modules (`30:<module_name>`, only modules with `inc/cnt.list.php` — `include/inc_lib/backend.functions.inc.php` line 533). Editing a module content part routes files from the module directory:

| File | Role |
| ---- | ---- |
| `inc/cnt.form.php` | Content part edit form, included in `articlecontent.edit.tmpl.php` |
| `inc/cnt.read.php` | Loads stored values into `$content`, `article.editcontent.inc.php` line 562 |
| `inc/cnt.sql.php` | Builds the insert/update statement on save, line 645 |
| `inc/cnt.post.php` | Validates POST data on save, `article.readform.inc.php` line 147 |
| `inc/cnt.article.php` | Renders the content part on the frontend, `content.article.inc.php` line 622 |
| `inc/cnt.list.php` | Marks the module as selectable; provides the list title via `$BL['modules'][name]['listing_title']` |

Content parts of type 30 whose module no longer exists are skipped silently on the frontend and in the article overview (`content.article.inc.php` line 582, `admin.functions.inc.php` line 359).

## Module activation overview

| Module | Internal name | Type | Content part | Frontend hooks |
| ------ | ------------- | ---- | ------------ | -------------- |
| Shop | `shop` | 0 (BE+FE) | Yes (product listing, cart, checkout) | init, render, search |
| Calendar | `calendar` | 0 (BE+FE) | No | render |
| Ads | `ads` | 0 (BE+FE) | No | init, render |
| Feed import | `feedimport` | 1 (BE only) | No | init |
| Addresses | `phpwcms_addresses` | 1 (BE only) | No | render |
| SEO log | `seo_log` | 1 (BE only) | No | — |
| Statistics | `statistics` | 1 (BE only) | No | — |
| Module promo | `modulpromo` | 1 (BE only) | No | — |
| Glossary | `glossary` | 0 (BE+FE) | Yes | render, search |

The newsletter is no module but part of the core with its own backend menu and the subscription content part type 12 — see [Newsletter (core)](06-newsletter.md).

## Reference pages

| Page | Module | Contents |
| ---- | ------ | -------- |
| [Shop – overview and activation](01-shop.md) | shop | Activation, backend area map, preferences, shop content part |
| [Shop – catalog management](02-shop-catalog.md) | shop | Products, categories, product fields, image sizing, shop tables |
| [Shop – ordering and checkout](03-shop-ordering.md) | shop | Cart, checkout, shipping, payment, order mails, order list, API |
| [Shop – product options](04-shop-options.md) | shop | Option engine, price modifiers, content part options and accordion |
| [Calendar](05-calendar.md) | calendar | Entries, repeat rules, frontend calendar rendering |
| [Newsletter (core)](06-newsletter.md) | – (core) | Subscribers, subscription lists, queue sending, content part 12 |
| [Ads](07-ads.md) | ads | Ad places, campaigns, ad delivery and tracking |
| [Feed import](08-feedimport.md) | feedimport | RSS/Atom feed import into articles |
| [Addresses](09-addresses.md) | phpwcms_addresses | Address records with geocoding, frontend address locator |
| [SEO log](10-seolog.md) | seo_log | Search engine referrer logging |
| [Statistics](11-statistic.md) | statistics | Backend dashboards over article, file, newsletter and log data |
| [Module manager](12-module-manager.md) | modulpromo | Informational module promo panel |
| [Glossary](13-glossary.md) | glossary | Keywords highlighted as abbr tooltips, backend glossary management |

## Related

- [Core concepts](../general/concepts.md#modules) — modules within the content model
- [Users and usergroups](../administration/users-usergroups.md) — permission groups and module groups
- [Glossary](13-glossary.md) — the glossary module reference