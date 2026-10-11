---
title: HTML
description: Output a raw HTML block edited in a source code editor.
sidebar_position: 2
---
# HTML (content part 6)

> Source: `include/inc_lib/content/cnt6.readform.inc.php`, `include/inc_lib/content/cnt6.sql.inc.php`, `include/inc_tmpl/content/cnt6.inc.php`, `include/inc_front/content/cnt6.article.inc.php`

A pass-through block for your own HTML. You edit the markup in a source editor; the frontend outputs it unchanged — no formatting, no sanitizing, no rich text helper.

## Backend form

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Template | select | *(empty = default)* | Content part template from `template/inc_cntpart/html/`. Empty uses the shipped default. |
| Plain html | textarea, 20 rows | — | The HTML source. Edits in the Ace code editor in HTML mode. |

Trailing `<br>`, `<br />` and empty `<p></p>` editor artifacts are stripped from the end of the input; the remainder is stored as typed.

## Rendering

### Template selection

Renderer order: selected file from `template/inc_cntpart/html/<name>.tmpl`, else `template/inc_default/html.tmpl`, else this built-in skeleton:

```html
[TITLE]<h3>{TITLE}</h3>[/TITLE]
[SUBTITLE]<h4>{SUBTITLE}</h4>[/SUBTITLE]
[HTML]{HTML}[/HTML]
```

### Template tags

| Tag | Meaning |
| --- | ------- |
| `[ATTR_CLASS]…[/ATTR_CLASS]` | Present when the wrapper class field is set; `{ATTR_CLASS}` prints its value. `[ATTR_CLASS_ELSE]` marks the branch without class. |
| `[ATTR_ID]…[/ATTR_ID]` | Same mechanism for the wrapper `id` (`{ATTR_ID}`). |
| `{TITLE}` / `{SUBTITLE}` | Content part title / subtitle, HTML-escaped. |
| `[HTML]…[/HTML]` / `{HTML}` | The stored markup, output as is. Removed when empty. |
| `{ID}` | Content part record ID. |

## Storage

- Markup: `phpwcms_articlecontent.acontent_html` (`mediumtext`)
- Template: `acontent_template`

## Related

- Editor-assisted variant of the same field: [WYSIWYG HTML (cp 14)](03-wysiwyg-html.md)
- Text without markup: [Plain text (cp 0)](00-plaintext.md)
- Code displayed as code, not rendered: [Code (cp 11)](04-code.md)