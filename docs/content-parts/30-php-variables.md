---
title: PHP variables
description: Feeds custom variables from an INI block into the global CUSTOM array for use by scripts and replacement tags.
sidebar_position: 30
---
# PHP variables (content part 52)

> Source: `include/inc_lib/content/cnt52.readform.inc.php`, `include/inc_lib/content/cnt52.sql.inc.php`, `include/inc_front/content/cnt52.article.inc.php`

A data-only content part: one INI-style text block parsed with `parse_ini_str()` and merged into `$GLOBALS['CUSTOM']`. It renders nothing.

## Purpose

Per-article custom configuration for your own frontend scripts, replacements or template logic — for example a map key, a color accent or a flag a custom `frontend_render` script switches on. Because it is a content part, editors set the values per article without touching code.

## Backend form

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Variables | textarea, INI syntax | — | One `key = value` pair per line; parsed with `parse_ini_str()`, so quoted strings, numbers and booleans are supported. Stored raw; title, subtitle, block, spacing and attribute fields behave like in every other CP. |

## Rendering

No output. The renderer parses the block and merges the result:

```php
$GLOBALS['CUSTOM'] = array_merge($GLOBALS['CUSTOM'], parse_ini_str($crow['acontent_text']));
```

Keys already present in `CUSTOM` are overwritten by values of CPs rendered later in the article. `CUSTOM` starts empty and is only filled by this CP, so name collisions between articles on one page follow render order.

## Storage

- `acontent_text` — the raw INI text.
- No `acontent_form`, no template selection.

## Related

- Rendering order of content parts: [Core concepts](../general/concepts.md#rendering-model).
- Scripts that can read `CUSTOM`: [Templates](../general/concepts.md#templates) describes `template/inc_script` integration points.
- Glossary: [Content part](../modules/13-glossary.md#content-part).