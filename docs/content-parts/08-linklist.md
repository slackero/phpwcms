---
title: Link list
description: Render one entry per line as a template-driven list of links with targets and titles.
sidebar_position: 8
---
# Link list (content part 5)

> Source: `include/inc_lib/content/cnt5.readform.inc.php`, `include/inc_lib/content/cnt5.sql.inc.php`, `include/inc_tmpl/content/cnt5.inc.php`, `include/inc_front/content/cnt5.article.inc.php`

A list of external links edited as plain text: one entry per line, each line a name, a URL, and optionally a tooltip title. The template defines the entry markup, so you can turn the list into any HTML shape the entry pattern can express.

## Backend form

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Template | select | *(empty = default)* | Content part template from `template/inc_cntpart/linklist/`. Empty uses the shipped default. |
| Linklist | textarea, 20 rows | — | One entry per line: `name | link | title`. |

### Line syntax

Fields are separated by `|`; the title field is optional.

```text
phpwcms home | https://www.phpwcms.org/ | The phpwcms website
Support forum | https://forum.phpwcms.org/ | EN and DE boards
https://www.phpwcms.org/
```

- A line with the URL only: the URL becomes the link value, and the link text falls back to the URL as well.
- Title empty: no `title` attribute.
- Multiple spaces collapse to one before storage; the second whitespace-separated word of the link field is the frame target (for example `https://example.org/ _blank`).

## Rendering

### Template selection

Renderer order: selected file from `template/inc_cntpart/linklist/<name>.tmpl`, else `template/inc_default/linklist.tmpl`, else this built-in skeleton:

```html
[TITLE]<h4>{TITLE}</h4>[/TITLE]
[SUBTITLE]<h5>{SUBTITLE}</h5>[/SUBTITLE]
[LINKLIST]<ul class="linklist">{LINKLIST}</ul>[/LINKLIST]
```

The entry pattern comes from the template's `[LINKLIST_ENTRY]` section:

```html
[LINK]<li><a href="{LINK}"{TARGET}{LINKTITLE}>{LINKNAME}</a></li>[/LINK]
```

### Template tags

| Tag | Meaning |
| --- | ------- |
| `[ATTR_CLASS]…[/ATTR_CLASS]` / `{ATTR_CLASS}` | Wrapper class and its conditional branch (`[ATTR_CLASS_ELSE]`). |
| `[ATTR_ID]…[/ATTR_ID]` / `{ATTR_ID}` | Wrapper `id` and its conditional branch. |
| `{TITLE}` / `{SUBTITLE}` | Content part title / subtitle, HTML-escaped. |
| `[LINKLIST]…[/LINKLIST]` / `{LINKLIST}` | The list block holding all entries; removed when empty. |
| `[LINK]…[/LINK]` | The entry pattern inside the `[LINKLIST_ENTRY]` section. |
| `{LINK}` | The URL, HTML-escaped. `#` when no link value exists. |
| `{TARGET}` | `target="…"` attribute from the link field's second word, empty otherwise. |
| `{LINKNAME}` | Link text: the name, or the link value when no name is given. HTML-escaped. |
| `{LINKTITLE}` | `title="…"` attribute from the third field, empty otherwise. |
| `{ID}` | Content part record ID. |

The section between `<!--LINKLIST_SPACE_START//-->` and `<!--LINKLIST_SPACE_END//-->` renders between entries; both markers sit together in the shipped default and are invisible there.

## Storage

- Normalized entries, one `name | link | title` line each, LF separated: `phpwcms_articlecontent.acontent_text`
- Template: `acontent_template`

## Related

- Single link output: [Link &amp; email (cp 3)](07-link-email.md)
- Downloads as a list: [File list (cp 7)](16-filelist.md)