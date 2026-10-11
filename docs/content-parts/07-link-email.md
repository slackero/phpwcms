---
title: Link &amp; email
description: Output one direct link or mailto address as a single anchor.
sidebar_position: 7
---
# Link &amp; email (content part 3)

> Source: `include/inc_lib/content/cnt3.readform.inc.php`, `include/inc_lib/content/cnt3.sql.inc.php`, `include/inc_tmpl/content/cnt3.inc.php`, `include/inc_front/content/cnt3.article.inc.php`

Renders a single link — an external URL or a `mailto:` email address — as one anchor line. Link text and `href` are the same value, so for external URLs the address doubles as the label.

## Backend form

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Template | select | *(empty = default)* | Lists files from `template/inc_cntpart/linkemail/`. The default directory is empty and the renderer does not use content part templates, so the selection has no effect on the output. |
| Direct link | text | — | The URL or `mailto:` address. |
| Target | select | *(blank)* | Where the link opens: blank (same), "In a new window" (`_blank`), "In parent frame of the window" (`_parent`), "In same window without frames" (`_top`), "In the same frame or window" (`_self`). |

## Rendering

The output is fixed markup, not template driven — the anchor is wrapped by the layout defaults `$template_default['article']['link_email_before']` / `link_email_after` from `include/config/conf.template_default.inc.php`:

```html
<div class="link-email"><a href="mailto:name@example.com">name@example.com</a></div>
```

- The `href` is the stored link as typed; the visible link text is the link with any `mailto:` prefix removed, HTML-escaped.
- The `target` attribute is emitted only when a target is selected.

## Storage

- Link and target: `phpwcms_articlecontent.acontent_redirect`, stored as one string `<link> <target>`; split on rendering and on reopening the dialog
- Template: `acontent_template`

## Related

- A list of links: [Link list (cp 5)](08-linklist.md)
- Page-internal teasers with links: [Teaser/link article (cp 8)](17-teaser-link-article.md)