---
title: WYSIWYG HTML
description: Edit formatted rich text with the configured WYSIWYG editor and optional custom fields.
sidebar_position: 3
---
# WYSIWYG HTML (content part 14)

> Source: `include/inc_lib/content/cnt14.readform.inc.php`, `include/inc_lib/content/cnt14.sql.inc.php`, `include/inc_tmpl/content/cnt14.inc.php`, `include/inc_front/content/cnt14.article.inc.php`

The rich text block edited in the WYSIWYG editor configured per backend user — CKEditor by default, TinyMCE as an alternative, or a plain textarea. The editor produces HTML, stored and output as entered. On top of the editor you can activate freely defined custom fields per template — the mechanism that turns a text part into a small structured template.

## Backend form

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Template | select | *(empty = default)* | Content part template from `template/inc_cntpart/wysiwyg/`. Changing it reloads the dialog when template-bound field groups exist. |
| HTML text | WYSIWYG editor, 15 rows | — | Main content, edited with the user's configured editor, 400 px height. |
| Custom fields | mixed | — | Optional. Each field renders from the definition in `$template_default['settings']['wysiwyg_custom_fields']` (`include/config/conf.template_default.inc.php`). Supported types: `str`, `textarea` (optionally with `render: 'wysiwyg'`), `option`, `select`, `int`, `float`, `bool`. Common definition keys: `legend`, `placeholder`, `default`, `class`, `hr`; textarea adds `rows`/`height`; `int`/`float` add `min`/`max`/`step`; `option`/`select` carry a `values` list where the `empty` key means "no value". |

### Custom fields configuration

Field groups are defined in `include/config/conf.template_default.inc.php` under `$template_default['settings']['wysiwyg_custom_fields']`; the shipped definitions are commented out, so the dialog shows only the editor until you enable one. A group is bound to a content part template through its `template` key, or to all templates with `template => 'default'`. Values survive in the dialog even when the group definition moves to another template: unbound stored values travel as hidden inputs.

## Rendering

### Template selection

Renderer order: selected file from `template/inc_cntpart/wysiwyg/<name>.tmpl`, else `template/inc_default/wysiwyg.tmpl`, else this built-in skeleton:

```html
[TITLE]<h3>{TITLE}</h3>[/TITLE]
[SUBTITLE]<h4>{SUBTITLE}</h4>[/SUBTITLE]
[TEXT]{TEXT}[/TEXT]
```

### Template tags

| Tag | Meaning |
| --- | ------- |
| `[ATTR_CLASS]…[/ATTR_CLASS]` / `{ATTR_CLASS}` | Wrapper class and its conditional branch (`[ATTR_CLASS_ELSE]`). |
| `[ATTR_ID]…[/ATTR_ID]` / `{ATTR_ID}` | Wrapper `id` and its conditional branch. |
| `{TITLE}` / `{SUBTITLE}` | Content part title / subtitle, HTML-escaped. |
| `[TEXT]…[/TEXT]` / `{TEXT}` | The editor HTML, output as stored. |
| `{WYSIWYG_<FIELD>}` | Each custom field value by its uppercase field key. |
| `[WYSIWYG_<FIELD>]…[/WYSIWYG_<FIELD>]` | Conditional block, kept when the field has a value. |
| `[WYSIWYG_<FIELD>_ELSE]…[/WYSIWYG_<FIELD>_ELSE]` | Inverse conditional block. |
| `[WYSIWYG_<FIELD>_<OPTION>]…[/WYSIWYG_<FIELD>_<OPTION>]` | For `option`/`select` fields: the block of the selected value's key. |
| `{ID}` | Content part record ID. |

How values render depends on the field definition: `bool` toggles the conditional block; `int`/`float` print the number; `render: 'html'` prints the stored markup; `render: 'markdown'` runs `parse_markdown()`; `render: 'plain'` runs `plaintext_htmlencode()`; `render: 'wysiwyg'` prints as stored; everything else prints HTML-escaped with line breaks converted.

## Storage

- Editor content: `phpwcms_articlecontent.acontent_html`
- Template: `acontent_template`
- Custom fields: serialized `cnt_fieldgroup` and `cnt_fields` in `acontent_form`

## Related

- Field-rich alternative text parts: [Text w/image (cp 1)](01-text-with-image.md)
- Raw markup alternative: [HTML (cp 6)](02-html.md)
- The same custom-field mechanism for image parts: [Images special (cp 31)](11-images-special.md)