---
title: Plain text
description: Enter unformatted text rendered with plain, Markdown or Textile formatting rules.
sidebar_position: 0
---
# Plain text (content part 0)

> Source: `include/inc_lib/content/cnt0.readform.inc.php`, `include/inc_lib/content/cnt0.sql.inc.php`, `include/inc_tmpl/content/cnt0.inc.php`, `include/inc_front/content/cnt0.article.inc.php`

A text block edited without a rich text editor: plain text in, formatted HTML out. Rendering applies one of three text formats — plain, Markdown or Textile — selected per content part.

## Backend form

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Template | select | *(empty = default)* | Content part template from `template/inc_cntpart/plaintext/`. Empty uses the shipped default. |
| Format | radio buttons | Plain text | Output format: "Plain text", MarkDown or Textile. Invalid values fall back to Plain text. |
| Plain text | textarea, 12 rows | — | The text. Edits in a plain code editor (Ace) whose syntax mode follows the selected format: `text`, `markdown` or `textile`. Stored exactly as typed, without trimming. |

## Rendering

### Template selection

Renderer order: selected file from `template/inc_cntpart/plaintext/<name>.tmpl`, else `template/inc_default/plaintext.tmpl`, else this built-in skeleton:

```html
[TITLE]<h4>{TITLE}</h4>[/TITLE]
[SUBTITLE]<h5>{SUBTITLE}</h5>[/SUBTITLE]
[TEXT]<p>{TEXT}</p>[/TEXT]
```

The shipped default `template/inc_default/plaintext.tmpl` wraps title and subtitle in `h3`/`h4` and outputs the text unwrapped:

```html
[TITLE]<h3>{TITLE}</h3>[/TITLE]
[SUBTITLE]<h4>{SUBTITLE}</h4>[/SUBTITLE]
[TEXT]{TEXT}[/TEXT]
```

### Template tags

| Tag | Meaning |
| --- | ------- |
| `[ATTR_CLASS]…[/ATTR_CLASS]` | Present when the wrapper class field is set; `{ATTR_CLASS}` prints its value. `[ATTR_CLASS_ELSE]` marks the branch without class. |
| `[ATTR_ID]…[/ATTR_ID]` | Same mechanism for the wrapper `id` (`{ATTR_ID}`). |
| `{TITLE}` / `{SUBTITLE}` | Content part title / subtitle, HTML-escaped. |
| `[TEXT]…[/TEXT]` / `{TEXT}` | The formatted text. Removed when empty. |
| `{ID}` | Content part record ID. |

### Text conversion

| Format | Renderer |
| ------ | -------- |
| Plain text | `plaintext_htmlencode()`: single line breaks become `<br />`, blank lines separate `<p>` paragraphs, HTML is escaped, basic BBCode renders (`[b]`, `[i]`, `[u]`, `[s]`, `[strong]`, `[em]`, `[p]`, `[br]`, `[nowrap]`). |
| MarkDown | `parse_markdown()`. |
| Textile | `parse_textile()`. |

## Storage

- Text: `phpwcms_articlecontent.acontent_text` (`mediumtext`), stored as typed
- Template: `acontent_template`
- Selected format: serialized `ctext_format` in `acontent_form`

## Related

- Rich text alternatives: [Text w/image (cp 1)](01-text-with-image.md), [WYSIWYG HTML (cp 14)](03-wysiwyg-html.md), [HTML (cp 6)](02-html.md)
- Formatted code output: [Code (cp 11)](04-code.md)