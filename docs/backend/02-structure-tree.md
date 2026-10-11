---
title: Structure tree
description: Manage structure levels, their fields, ordering and per-level defaults.
sidebar_position: 2
---
# Structure tree

> Source: `include/inc_tmpl/article.structlist.tmpl.php`, `include/inc_lib/admin.functions.inc.php`, `include/inc_tmpl/admin.structform.tmpl.php`, `include/inc_lib/admin.structure.inc.php`, `include/inc_act/act_structure.php`

The first tab of the **Articles** section renders the site's structure tree: level 0 (the index page) at the top, its [structure levels](../general/concepts.md#the-content-hierarchy) as folder rows, and each level's articles nested below. `phpwcms.php?do=articles` and `phpwcms.php?do=articles&p=6` show the same tree; the struct form itself opens under `p=6&struct=<level_id>` (or `struct=index` for level 0).

Saving a level is gated by the `artstruc` SYSGROUP — writes through `include/inc_act/act_structure.php` are ignored for users outside that group or without super admin rights.

## Tree actions

> Source: `include/inc_lib/admin.functions.inc.php` (`listmode_edits()`), `include/inc_act/act_structure.php`

Every level row offers a pencil edit button, a **More actions** drop-down, and a visibility toggle:

| Action | Behavior |
| ------ | -------- |
| Insert level | Opens the struct form with a new parent (`struct=<id>`) and a sort value of `(child count + 1) * 10` |
| New article | Opens the article form with that level preselected |
| Copy level | Marks the level; clicking the paste arrow on any target level creates a deep copy — level, its settings (with a fresh alias), all articles and all child levels (`copy_level_to_level()`); non-admins become the owner of the copy |
| Cut level | Marks the level; the paste arrow moves it under the target level (`acat_struct`, `acat_sort`), with the target's own subtree excluded |
| Sort up / Sort down | Swaps the `acat_sort` values between neighbors; both values must be ≥ 10 |
| Delete | Marks the level `acat_trash = 9`, renames its alias to `<alias>_del-<timestamp>`, and marks all contained child levels and articles deleted the same way |
| Eye icon | Toggles `acat_aktiv` inline for the row (also available on level 0) |

The level name is a live preview link (`phpwcms-preview`) opening the level in the frontend; the folder icon carries a tooltip with level ID, alias, sort value and assigned template.

## Level fields

> Source: `include/inc_tmpl/admin.structform.tmpl.php`, `include/inc_act/act_structure.php` (column mapping to `phpwcms_articlecat`)

The form header shows the parent level the new/edited level belongs to. Buttons: *Update* (submit, stays on the form), *Save and close*, *Cancel*.

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Category title (`acat_name`) | text | — | Menu name, up to 2000 chars, at least one word required. |
| Category alternative title (`acat_title`) | text | — | Alternative name, up to 2000 chars. |
| Alias (`acat_alias`) | text | — | URL alias, up to 1000 chars. Links assemble it from the level name, the parent alias or the level's own alias; `allow_empty_alias` in the configuration decides whether the alias may be empty. |
| OnePage ID (#anchor) support (`acat_onepage`) | switch | 0 | Level name acts as an anchor target in OnePage templates. |
| Language (`acat_lang`, `acat_lang_type`, `acat_lang_id`) | radio + picker | default language | Shown only when more than one backend/frontend language is enabled; links this level to a level (`category`) or article (`article`) ID of the default language. Not available for the index page (which is always default language). |
| Page title (`acat_pagetitle`) | text | — | Overwrites the page `<title>` on frontend pages of this level. |
| CSS class (`acat_class`) | text | — | Class fragment for frontend output; only letters, digits, space, underscore and hyphen survive the save. |
| Redirect to (`acat_redirect`) | text | — | External/global redirect target; empty = no redirect. The frontend redirects 301 to this URL when the level opens. |
| Canonical link (`acat_canonical`) | text | — | Canonical URL emitted for this level. |
| Keywords (`acat_keywords`) | textarea | — | Comma separated list, stored max 255 chars. |
| Category infotext (`acat_info`) | textarea | — | Free text description, up to 32000 chars. |
| Template (`acat_template`) | select | parent level's template | Layout template from `phpwcms_template`; the default template is marked. |
| Settings (`acat_overwrite`) | select | Default | Optional settings file from `template/inc_settings/template_default/*.php` overwriting `conf.template_default.inc.php` values for this level. |
| Pagination (`acat_paginate`) | switch | 0 | Enables article pagination on the level. |
| Topcount (`acat_topcount`) | number | index page value | Number of articles used by listings. |
| Articles per page (`acat_maxlist`) | number | 0 | Pagination batch size. |
| Sort value (`acat_sort`) | number | `(children + 1) * 10` on insert | Manual order of the level inside its parent; not present on the index page form. |
| Ordering of assigned articles (`acat_order` + `acat_ordersort`) | radio groups | stored as sum | Manual (0), date (2), changed date (4), start date (6), kill date (10) or title (8), each combined with ascending (0) or descending (1) — e.g. start date descending = 7. |
| Access group list (`acat_permit`) | dual multi-select | — | Shown only with `usergroup_support`. Groups moved to the left column get level access; writes the group IDs to `acat_permit` and forces "Visible for users logged on only" plus search off. |
| Content part selection (`acat_cntpart`) | dual multi-select | — | Restricts which content part types may be inserted on this level (negative option values select whole [optgroup]s). Empty = all. |
| Default content part (`acat_cpdefault`) | select | global `cp_default` or 0 | Content part type preselected when creating an article here. |
| Cache off (`acat_cacheoff`) + Cache timeout (`acat_timeout`) | switch + select | Default | Timeout in seconds: 60, 300, 900, 1800, 3600, 14400, 43200, 86400, 172800, 604800, 1209600, 2592000; the switch forces 0 = no cache. |
| Search (`acat_nosearch`) | switch (`off`) | off | Turns the search off for pages of this level. |
| Hide (`acat_hidden`) | switch | 0 | Level is hidden from navigation. |
| Visible when active (`acat_hiddenactive`) | switch | 0 | With "Hide": the hidden level still appears when one of its articles is the current page. |
| Visible for users logged on only (`acat_regonly`) | switch | 0 | Requires a frontend login to view articles on this level. |
| Visible (`acat_aktiv`) | switch | `set_category_active` config | Level is active/visible at all. |
| SSL (`acat_ssl`) | switch | 0 | Forces an SSL redirect for this level. |
| Sitemap (`acat_nositemap`) | switch | on for new levels, `index` = default | Includes/excludes the level from the sitemap. |
| Available after end date / archive (`acat_archive`) | switch | 0 | Articles stay listed after their end date once archived. |
| Allow Social Sharing (`acat_opengraph`) | switch | `set_sociallink.articlecat` config | Enables OpenGraph output; the new-level default falls back to 0 when the config is off. |
| Breadcrumb (`acat_breadcrumb_default`, `acat_breadcrumb_nothidden`, `acat_breadcrumb_nolink`) | switches | Default | Bitmask storage: nothidden = 1 (show hidden levels in breadcrumb), nolink = 2 (level breadcrumb entry without link). |

New levels inherit `acat_topcount`, `acat_order` and `acat_cntpart` from the index page configuration; all other fields start from `set_category_active` and safe zero/empty defaults.

### The index page (level 0)

The form opened with `struct=index` edits the `$indexpage` array — name, alternative title, alias, infotext, keywords, page title, template, listing/pagination defaults, order and every switch listed above. Saving does not write a database row; it generates `include/config/conf.indexpage.inc.php` as a flat PHP file instead, so keep the file writable. Language links and the sort value input are absent from this form.