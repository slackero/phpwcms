---
title: Custom CP
description: Renders one or more data records through fields defined in the Admin custom content parts manager.
sidebar_position: 36
---
# Custom CP (content part 60)

> Source: `include/inc_lib/content/cnt60.readform.inc.php`, `include/inc_lib/custom_fields.inc.php`, `include/inc_front/content/cnt60.article.inc.php`, `include/inc_tmpl/content/cnt60.inc.php`, `include/inc_tmpl/admin.custom_cpt.tmpl.php`

The backend label is `Custom CP`. Each Custom CP definition (key, title, fields, mode, template) is created in the admin area **Custom content parts**; an article then adds instantiations of it. One CP definition defines the field set; each CP record holds one or more data rows (`single` or `repeater` mode).

## Purpose

Build your own repeatable structured content — testimonial, feature box, banner — without code: the admin defines the fields and a starter template, editors fill the values, and a template in `template/inc_cntpart/custom/` decides the markup.

## Definition storage

Definitions live in three layers, resolved with `get_custom_contentparts()` in `include/inc_lib/custom_fields.inc.php` (DB wins, presets merge in, legacy last):

1. Database table `phpwcms_custom_cpt`: `cpt_id`, `cpt_key` (unique, lowercase identifiers), `cpt_title`, `cpt_desc`, `cpt_mode` (`single` or `repeater`), `cpt_icon`, `cpt_template`, `cpt_active`, `cpt_schema` (JSON field map created by `ensure_custom_cpt_table()`).
2. Presets: `template/config/custom_cpt/<key>.json` with the same fields; ignored when a DB row with the key exists.
3. Legacy `$template_default['settings']['customctp_custom_fields'][<key>]` groups from the template config.

Field keys are sanitized to `[a-z0-9_-]`; reserved keys (`title`, `subtitle`, `attr_class`, `attr_id`, `anchor`, `id`, `before`, `after`, `html`, `text`, `cpt_key`, `module`, `cpt_title`, `cpt_desc`, `item_index`, `item_num`, `item_total`) are reserved for the standard tags and rejected for fields.

## Backend form

Field rows depend on the definition (`include/inc_tmpl/content/cnt60.inc.php`). The CP select for the definition is fixed in the content part list; the dialog offers:

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Template | select | *(empty = default)* | First templates from `template/inc_cntpart/custom/<cpt_key>/`, then matching `inc_cntpart/custom/<cpt_key>*` files, then all general files. |
| Data rows | repeat list | — | `repeater` mode: add, sort, delete rows. `single` mode: exactly one row. Each row renders one input per defined field. |

Sanitization per field type (`custom_field_sanitize_value()`): `int`, `float`, `bool` cast; `url` filter-sanitized; `color` accepts `#rgb`/`#rrggbb`; `date` cleaned to 20 chars; `file`/`image` keep arrays; `textarea` with render `html`, `markdown`, `wysiwyg` keep HTML; everything else `clean_slweg()` honoring `maxlength`.

## Rendering

Template resolution (`cnt60.article.inc.php`): selected template, then `<cpt_key>/default.tmpl` or `.html`, then `<cpt_key>.tmpl`/`.html`, then `default.tmpl`/`default.html`. Sections: `CUSTOM_HEADER`, `CUSTOM_ENTRY` (per row), `CUSTOM_FOOTER`, `CUSTOM_ENTRY_SPACER` (between rows, else line feed). Without markers the whole file acts as the entry template.

| Tag | Meaning |
| --- | ------- |
| `{<FIELD>}` / `{CUSTCTP_<FIELD>}` | Field value, rendered per type: bool prints a space or nothing, color/date/url/str are HTML-escaped, textarea render modes honor `markdown` (`parse_markdown()`), `plain` (`plaintext_htmlencode()`), `html`/`wysiwyg` raw; `file`/`image` resolve a file id to `download.php` or cached image URL. |
| `[<FIELD>]…[/FIELD]` (and prefixed) | Kept when the field holds a value. |
| `[<FIELD>_<OPTION>]` | For option/select fields: kept for the chosen value, stripped otherwise. |
| `{ITEM_INDEX}` / `{ITEM_NUM}` / `{ITEM_TOTAL}` | Zero-based index, 1-based number, row count. |
| `[ITEM_FIRST]` `[ITEM_LAST]` | First/last row of the set. |
| `[ENTRY_DATA]…[/ENTRY_DATA]` | Collected and re-emitted at `[DATA]` in header/footer — data usable outside the row loop. |
| `[ATTR_CLASS]` `[ATTR_ID]` `[TITLE]` `[SUBTITLE]` `[ANCHOR]` `{ID}` `[BEFORE]` `[AFTER]` | Standard CP attributes. |
| `[HTML]` `[TEXT]` | `acontent_html` and `acontent_text` raw values. |
| `[CPT_KEY]` `[MODULE]` `{CPT_TITLE}` `[CPT_DESC]` | Definition key and metadata. |

## Storage

| Column | Content |
| ------ | ------- |
| `acontent_form` | JSON (not serialized): `custom_elements[]` rows of `custom_fields` key/value sets plus `fieldgroup`/definition key. Legacy rows store the same structure serialized; the renderer accepts both. |
| `acontent_template` | Selected template path. |
| `acontent_html` | Round-tripped `custom_html` value; the current backend dialog exposes no input for it. |
| `acontent_module` | Definition key (`cpt_key`) — the CP is listed and filtered by it. |

## Related

- Fieldgroup custom fields for the Tabs CP use the same rendering rules: [Tabs (cp 32)](35-tabs.md).
- Templates and `template/inc_cntpart`: [Core concepts](../general/concepts.md#templates).