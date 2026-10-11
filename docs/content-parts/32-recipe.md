---
title: Recipe
description: Structured recipe with ingredients, preparation time, calories and difficulty on a selectable template.
sidebar_position: 32
---
# Recipe (content part 26)

> Source: `include/inc_lib/content/cnt26.readform.inc.php`, `include/inc_front/content/cnt26.article.inc.php`, `include/inc_tmpl/content/cnt26.inc.php`

A fixed-field recipe record. Fields map to legacy `acontent_*` columns, so recipe lists can filter on them. Ingredient lines support sub-headlines and amount\|name pairs rendered as list or table.

## Purpose

Store and render recipes with a consistent field set. The headline renderer of this CP is skipped (`headline()` call is commented out), so title and subtitle only print when the template asks for them.

## Backend form

Fields top to bottom (`include/inc_tmpl/content/cnt26.inc.php`).

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Category keywords | textarea | — | Comma-separated keywords ("Zutaten" helper select offers keywords used by other recipe CPs). Normalized to a comma list. |
| Template | select | *(empty = default)* | File from `template/inc_cntpart/recipe/`; empty falls back to the built-in skeleton. Ships `BS5.tmpl`, `default.tmpl`, `rezept.html` plus a `search` folder. |
| Ingredients | textarea, one line each | — | See syntax below. |
| Preparation time | number + text | — | Minutes and a free "additional" text. |
| Nutritional value | number + text | — | kJ value and a free "additional" text. |
| Difficulty | radio 1–5 | 1 | Clamped to 1–5 on save. |
| Preparation | WYSIWYG | — | Free HTML instructions. |

Ingredient line syntax: a leading `*` starts a new headline group; a `|` splits amount and name; a line without `|` prints the whole line as amount-less text.

## Rendering

Template resolution: selected `template/inc_cntpart/recipe/<file>`, else the built-in skeleton (a `<div class="recipe">` with title, subtitle, ingredients, calories, preparation, time, severity and category). The fallback in code also covers `[ATTR_CLASS_ELSE]`/`[ATTR_ID_ELSE]` branches.

| Tag | Meaning |
| --- | ------- |
| `[ATTR_CLASS]` `{ATTR_CLASS}` `[ATTR_ID]` `{ATTR_ID}` | Wrapper attributes with else-branches. |
| `[TITLE]` `{TITLE}` `[SUBTITLE]` `{SUBTITLE}` | CP title/subtitle, HTML-escaped. |
| `[INGREDIENTS]` `{INGREDIENTS}` | `<ul>` list per group, `<h5>` before each `*` headline. |
| `[INGREDIENTSTABLE]` `{INGREDIENTSTABLE}` | Same data as two-column `<table>` rows (`ingredients`, `ingredientsText` classes). |
| `[CALORIES]` `{CALORIES}` | kJ value. |
| `{KCAL}` | Computed `ceil(kJ / 4.1868)`. |
| `[CALORIESADD]` `{CALORIESADD}` | Calorie free-text, appended after kcal. |
| `[PREPARATION]` `{PREPARATION}` | WYSIWYG content, printed as stored. |
| `[TIME]` `{TIME}` / `[TIMEADD]` `{TIMEADD}` | Minutes ("Minuten" suffix in the default skeleton) and the additional time text. |
| `[SEVERITY]` `{SEVERITY}` | Difficulty number 1–5, not escaped. |
| `[CAT]` `{CAT}` | Category keyword list, HTML-escaped. |
| `{ID}` | Content part record ID. |

## Storage

Recipe fields reuse assorted columns so queries can filter without JSON parsing:

| Column | Content |
| ------ | ------- |
| `acontent_text` | Category keyword list (comma string). |
| `acontent_form` | Serialized recipe array (template, ingredients, time, calorific value, preparation, severity, category). |
| `acontent_alink` | `0-<calorific value>`, zero-padded to 15 digits. |
| `acontent_media` | `0-<time>`, zero-padded to 15 digits. |
| `acontent_files` | `0-<severity>`, zero-padded to 15 digits. |
| `acontent_newsletter` | Search text: `optimizeForSearch()` over preparation, ingredients, calorie text, time text, category. |

## Related

- How the search uses CP texts: [Core concepts](../general/concepts.md#the-content-hierarchy).
- Glossary: [Content part](../modules/13-glossary.md#content-part).