---
title: List
description: Turn one entry per line into a simple template-driven unlinked bullet list.
sidebar_position: 5
---
# List (content part 4)

> Source: `include/inc_lib/content/cnt4.readform.inc.php`, `include/inc_lib/content/cnt4.sql.inc.php`, `include/inc_tmpl/content/cnt4.inc.php`, `include/inc_front/content/cnt4.article.inc.php`

A flat bullet list: one list entry per line, each rendered through the template's `<li>` markup. There are no nesting levels and no links — for indented or definition lists use [ul-list (cp 100)](06-list-ul.md). The backend labels both IDs "List".

## Backend form

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Template | select | *(empty = default)* | Content part template from `template/inc_cntpart/bulletlist/`. Empty uses the shipped default. |
| List (table) | textarea, 20 rows | — | One entry per line. Empty lines are dropped, each line trimmed. Stored HTML-escaped. |

## Rendering

### Template selection

Renderer order: selected file from `template/inc_cntpart/bulletlist/<name>.tmpl`, else `template/inc_default/bulletlist.tmpl`, else this built-in skeleton:

```html
[TITLE]<h4>{TITLE}</h4>[/TITLE]
[SUBTITLE]<h5>{SUBTITLE}</h5>[/SUBTITLE]
[BULLETLIST]<ul class="bulletlist">{BULLETLIST}<!--BULLETLIST_ITEM_START//-->
    <li>{BULLETLIST_ITEM}</li>
<!--BULLETLIST_ITEM_END//--></ul>[/BULLETLIST]
```

The shipped default `template/inc_default/bulletlist.tmpl` is the same markup with `h3`/`h4` title levels and a plain `<ul>`. The `[BULLETLIST_ITEM]` section is the entry pattern: the renderer repeats it for every non-empty line, filling `{BULLETLIST_ITEM}` with the HTML-escaped line, and removes the section markers from the output.

### Template tags

| Tag | Meaning |
| --- | ------- |
| `[ATTR_CLASS]…[/ATTR_CLASS]` / `{ATTR_CLASS}` | Wrapper class and its conditional branch (`[ATTR_CLASS_ELSE]`). |
| `[ATTR_ID]…[/ATTR_ID]` / `{ATTR_ID}` | Wrapper `id` and its conditional branch. |
| `{TITLE}` / `{SUBTITLE}` | Content part title / subtitle, HTML-escaped. |
| `[BULLETLIST]…[/BULLETLIST]` / `{BULLETLIST}` | The list block holding all entries; removed when no entries. |
| `{BULLETLIST_ITEM}` | Inside the entry section, one list entry. |
| `{ID}` | Content part record ID. |

With no entries the whole `[BULLETLIST]` block is removed.

## Storage

- Entries, LF separated: `phpwcms_articlecontent.acontent_text`
- Template: `acontent_template`

## Related

- Nested and definition lists: [ul-list (cp 100)](06-list-ul.md)
- List of links: [Link list (cp 5)](08-linklist.md)