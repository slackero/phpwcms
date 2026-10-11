---
title: FAQ
description: One question with a rich-text answer and optional image, rendered through an accordion-friendly template.
sidebar_position: 33
---
# FAQ (content part 27)

> Source: `include/inc_lib/content/cnt27.readform.inc.php`, `include/inc_front/content/cnt27.article.inc.php`, `include/inc_tmpl/content/cnt27.inc.php`, `template/inc_cntpart/faq/`

One content part renders one question/answer pair. Stack several of them for a FAQ list; the shipped templates wrap each pair in an accordion item and carry the CP id, so bootstrap accordions and anchor links work per entry.

## Purpose

A structured Q&A block: plain question text for search and headings, rich-text answer, optional image with caption and zoom. Templates decide the final markup.

## Backend form

Fields top to bottom (`include/inc_tmpl/content/cnt27.inc.php`).

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Template | select | *(empty = default)* | File from `template/inc_cntpart/faq/`; ships `BS5-Accordion.tmpl` and `default.tmpl`. Empty falls back to the built-in skeleton in `cnt27.article.inc.php`. |
| Question | textarea | — | Stored as plain text, HTML-escaped at render time. |
| Answer | WYSIWYG | — | Free HTML. |
| Image | file select + delete | none | From the file center; preview thumbnail shown next to the caption field. |
| Max width | number, px | `content_width` | Capped when `RESPONSIVE_MODE` is off or the value is empty. |
| Max height | number, px | — | Proportional when 0. |
| Enlarge | checkbox | off | Links the image to `image_zoom.php`; a caption link wins over the zoom link. |
| Caption | textarea | — | First line prints as caption text, further lines feed the caption URL/title as in image CPs (`getImageCaption()`). |

## Rendering

Built-in fallback skeleton:

```html
<div class="faq">
    <!-- hidden title/subtitle [TITLE]<h3 id="faq_id{FAQ_ID}">{TITLE}</h3>[/TITLE][SUBTITLE]<h4>{SUBTITLE}</h4>[/SUBTITLE] -->
    [FAQ_QUESTION]<h3>{FAQ_QUESTION}</h3>[/FAQ_QUESTION]
    [FAQ_IMAGE]<div class="faq-image">{FAQ_IMAGE}[FAQ_CAPTION]<div class="faq-caption">{FAQ_CAPTION}</div>[/FAQ_CAPTION]</div>[/FAQ_IMAGE]
    [FAQ_ANSWER]<p>{FAQ_ANSWER}</p>[/FAQ_ANSWER]
</div>
```

| Tag | Meaning |
| --- | ------- |
| `[ATTR_CLASS]` `{ATTR_CLASS}` `[ATTR_ID]` `{ATTR_ID}` | Wrapper attributes with else-branches. |
| `[TITLE]` `{TITLE}` `[SUBTITLE]` `{SUBTITLE}` | CP title/subtitle (commented out in the default markup). |
| `[FAQ_QUESTION]` `{FAQ_QUESTION}` | Question text, HTML-escaped. |
| `[FAQ_ANSWER]` `{FAQ_ANSWER}` | Answer HTML. |
| `[FAQ_IMAGE]` `{FAQ_IMAGE}` | Rendered `<img>` (and zoom/caption link when set). |
| `[FAQ_CAPTION]` `{FAQ_CAPTION}` | Caption text. |
| `{FAQ_ID}` | Content part record ID. |

`BS5-Accordion.tmpl` uses the same tags to emit a Bootstrap 5 collapsed accordion item with `data-bs-parent` grouping keyed on `{FAQ_ID}`.

## Storage

| Column | Content |
| ------ | ------- |
| `acontent_text` | Question (plain). |
| `acontent_html` | Answer (HTML). |
| `acontent_form` | Serialized `faq_template`. |
| `acontent_image` | `dbid:filename:hash:ext:width:height:caption(b64):position:zoom`; position is always 0 because the template decides the layout. |

## Related

- Image caption syntax: [Text w/image (cp 1)](01-text-with-image.md).
- Glossary: [Content part](../modules/13-glossary.md#content-part).