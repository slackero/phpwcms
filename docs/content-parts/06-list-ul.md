---
title: ul-list
description: Build nested ul, ol or dl lists from tilde indent markers in plain text.
sidebar_position: 6
---
# ul-list (content part 100)

> Source: `include/inc_lib/content/cnt100.readform.inc.php`, `include/inc_lib/content/cnt100.sql.inc.php`, `include/inc_tmpl/content/cnt100.inc.php`, `include/inc_front/content/cnt100.article.inc.php`

Builds a nested HTML list from plain text: one list entry per line, nesting depth marked by leading tildes. The output markup is hard-coded — this content part has no template.

## Backend form

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| List type | radio group | `<ul>` | Output element: `<ul>` for an unstyled unordered list, `<ol>` for a numbered list, `<dl>` for a definition list. |
| List | textarea, 20 rows | — | One entry per line. `~` = first level, `~~` = second level, and so on; the first line always starts with a tilde. For `<dl>`, a `|` separates definition term and description. |

The backend hint reads "~ = 1st level, ~~ = 2nd level, etc.". A leading tilde is added automatically if missing; an empty list content renders in a plain text paragraph instead.

## Rendering

### Output markup

Depth is computed from the tilde count of every line and converted into nested elements without CSS classes:

```html
<ul>
<li>first level entry</li>
<ul>
<li>second level entry</li>
</ul>
</ul>
```

- `<ul>` (default) and `<ol>`: each line is one `<li>`; entry text passes `plaintext_htmlencode()`, so it is HTML-escaped, renders basic BBCode (`[b]`, `[i]`, `[u]`, `[s]`, `[strong]`, `[em]`, `[nowrap]`) and keeps single line breaks as `<br />`.
- `<dl>`: splits each line at `|` into one `<dt>` and, when a description follows, one `<dd>`.
- Without any `~` in the text, the raw content renders as escaped paragraph text in the default article text wrapper instead of a list.

## Storage

- List text: `phpwcms_articlecontent.acontent_text` (`mediumtext`), stored HTML-escaped
- List type: serialized `list_type` (0 = `<ul>`, 1 = `<ol>`, 2 = `<dl>`) in `acontent_form`

## Related

- Flat template-driven list: [List (cp 4)](05-list.md)
- Linked entries instead of plain text: [Link list (cp 5)](08-linklist.md)