---
title: Contentpart alias
description: Renders another content part of the same installation in place, with optional own display settings.
sidebar_position: 29
---
# Contentpart alias (content part 24)

> Source: `include/inc_lib/content/cnt24.readform.inc.php`, `include/inc_front/ext.func.inc.php` (`getContentPartAlias()`), `include/inc_tmpl/content/cnt24.inc.php`

Embeds an existing content part by its numeric id. The alias CP resolves the target row, optionally overlays block, spacing, title, top-link and visibility settings with its own values, and the frontend then renders the target through its normal renderer. The CP itself produces no output.

## Purpose

Reuse one content part — a form, a text block, a file list — on several articles without duplicating content. Edits in the target CP propagate everywhere the alias is used.

## Backend form

Fields top to bottom (`include/inc_tmpl/content/cnt24.inc.php`).

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Alias ID | text | — | Numeric `acontent_id` of the target CP, or picked with the sitemap browser (`articlebrowser.php?opt=5`). A non-existing or trashed id is emptied on save. When set, an edit button opens the target CP and the type of the target is shown; module targets (type 30) without an installed module show a warning instead. |
| Display (block) setting | checkbox | off | Off: the alias CP's own block assignment (CONTENT, HEADER, …, named blocks) applies. On: the target keeps its own block. |
| Spaces setting | checkbox | off | Same pattern for the space-before/space-after values. |
| Titles setting | checkbox | off | Same pattern for title and subtitle; off means the alias CP's title/subtitle is printed. |
| Top link setting | checkbox | off | Same pattern for the "top" link flag. |
| Visibility | checkbox | off | On: the target must itself be `acontent_visible=1`, otherwise the alias renders nothing. |

## Rendering

`getContentPartAlias()` in `include/inc_front/ext.func.inc.php` runs during content part gathering, before any renderer:

1. Load the target row with `acontent_trash=0`, within its live/kill date window, visibility check when the box is set, and the same frontend-user grant rule as normal CPs.
2. For each unchecked option, copy the alias CP's own value (block, before/after spaces, title, subtitle, top flag) into the target row; checked options keep the target values.
3. Return the merged row so the render loop `include` targets `cnt<target type>.article.inc.php`.

When the target cannot be resolved, the CP renders nothing. A target that is itself an alias CP (type 24) resolves only one hop: the render loop includes the target row's own renderer, and the alias renderer file outputs nothing, so such chains print no content.

## Storage

- `acontent_form` — serialized array with `alias_ID`, `alias_block`, `alias_spaces`, `alias_title`, `alias_toplink`, `alias_status`.
- Target link itself: none stored beyond `alias_ID`; no back-references are maintained.

## Related

- Block assignments and CP settings: [Core concepts](../general/concepts.md#the-content-hierarchy).
- Same embedding pattern for whole articles: [Teaser/link article (cp 8)](17-teaser-link-article.md).
- Glossary: [Content part](../modules/13-glossary.md#content-part).