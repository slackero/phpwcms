---
title: Code
description: Publish source code snippets escaped for display inside a preformatted template.
sidebar_position: 4
---
# Code (content part 11)

> Source: `include/inc_lib/content/cnt11.readform.inc.php`, `include/inc_lib/content/cnt11.sql.inc.php`, `include/inc_tmpl/content/cnt11.inc.php`, `include/inc_front/content/cnt11.article.inc.php`

Displays source code as source code: the stored text is escaped and, unless the template already wraps it in `<pre>`, gets line break conversion. It never executes the content.

## Backend form

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Template | select | *(empty = default)* | Content part template from `template/inc_cntpart/code/`. Empty uses the shipped default. |
| Code | textarea, 15 rows | — | The code. Edits in the Ace code editor in PHP mode. Stored without trimming, so leading blank lines of the snippet survive. |

## Rendering

### Template selection

Renderer order: selected file from `template/inc_cntpart/code/<name>.tmpl`, else `template/inc_default/code.tmpl`, else this built-in skeleton:

```html
[TITLE]<h3>{TITLE}</h3>[/TITLE]
[SUBTITLE]<h4>{SUBTITLE}</h4>[/SUBTITLE]
[CODE]<code>{CODE}</code>[/CODE]
```

The shipped default `template/inc_default/code.tmpl` wraps the code in `<pre>` instead:

```html
[CODE]<pre>{CODE}</pre>[/CODE]
```

### Template tags

| Tag | Meaning |
| --- | ------- |
| `[ATTR_CLASS]…[/ATTR_CLASS]` / `{ATTR_CLASS}` | Wrapper class and its conditional branch (`[ATTR_CLASS_ELSE]`). |
| `[ATTR_ID]…[/ATTR_ID]` / `{ATTR_ID}` | Wrapper `id` and its conditional branch. |
| `{TITLE}` / `{SUBTITLE}` | Content part title / subtitle, HTML-escaped. |
| `[CODE]…[/CODE]` / `{CODE}` | The escaped code. Removed when empty. |
| `{ID}` | Content part record ID. |

### Output processing

The renderer prepares `{CODE}` as follows:

- HTML-escapes the text (`&`, `<`, `>`, quotes).
- Spaces become `&nbsp;`, tabs four `&nbsp;`.
- Square and curly brackets become numeric entities, so replacement tag syntax in the snippet is never interpreted.
- If the template contains no `<pre` tag, line breaks are converted with `nl2br()`.

## Storage

- Code: `phpwcms_articlecontent.acontent_text`
- Template: `acontent_template`

## Related

- Rendered markup instead of displayed source: [HTML (cp 6)](02-html.md)
- Unformatted text: [Plain text (cp 0)](00-plaintext.md)