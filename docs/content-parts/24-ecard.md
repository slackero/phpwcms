---
title: E-card
description: Sends an image and personal message as an HTML e-card to a recipient chosen by the visitor.
sidebar_position: 24
---
# E-card (content part 16)

> Source: `include/inc_lib/content/cnt16.readform.inc.php`, `include/inc_front/content/cnt16.article.inc.php`, `include/inc_tmpl/content/cnt16.inc.php`

The visitor picks one image from the CP gallery, fills in sender, recipient and an optional message, and the content part mails the result as an HTML e-mail to the recipient. Everything is configured inside the content part dialog; the CP has no template selection.

## Purpose

A pick-and-send greeting card form. When the form is posted with valid e-mail addresses, phpwcms sends the mail immediately and prints the send confirmation; otherwise the chooser image list and the form are shown again with validation. The CP uses `PhpwcmsMailer` with the site SMTP settings.

## Backend form

Fields top to bottom (`include/inc_tmpl/content/cnt16.inc.php`).

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Subject | text, max 250 | *(empty)* | Mail subject. Empty subject falls back to `E-Card: <caption>` where `<caption>` is the text line of the chosen image. |
| Chooser | radio | Radiobutton | How the visitor picks the card: `Radiobutton` (radio list) or `JavaScript` (table cells with your JS event handlers plus a hidden `ecard_chooser` input). |
| Onmouseover | text | — | JS snippet appended as `onmouseover` on each chooser caption cell of the chooser. JavaScript mode only. |
| Onclick | text | — | JS snippet appended as `onclick` on the same cell. Trailing semicolons are normalized automatically. |
| Onmouseout | text | — | JS snippet appended as `onmouseout` on the same cell. |
| Image | multi-select | none | Images from the file center (`filebrowser.php?opt=3`). Order is set with the up/down buttons; thumbnails are listed below the select. |
| Alignment | select | Left | Chooser table alignment: `0` left, `1` center, `2` right. |
| Max width | number, px | — | Chosen image width. Capped to `(content_width − (columns − 1) × space) / columns` when `RESPONSIVE_MODE` is off or the value is empty. |
| Max height | number, px | — | Chosen image height, proportional when the other value is 0. |
| Columns | select 1–25 | 1 | Chooser table columns; reduced to the image count when larger. |
| Image space | number, px | — | Horizontal space between chooser images; factored into the width cap above. |
| Enlarge | checkbox | off | Stored as `zoom`; the frontend renderer (`cnt16.article.inc.php`) does not read this flag. |
| Card texts | textarea, one line per image | — | One line per image, mapped to the image at the same position. A line starting with `~` shows the text (without the `~`) as caption only and the image is not offered as a card. A second segment after `\|` keeps the caption part for the entry list. |

## Rendering

The three HTML blocks are single storage fields, not template files.

**Chooser list** — built by `imagelisttable()` (`include/inc_front/img.func.inc.php`) with `type=1`: each card gets a caption cell with a radio (`ecard_chooser_<index>`) or the JS handlers in JavaScript mode. Cell classes come from `template_default['article']['ecard_chooser_css']` (`class="my-1"` by default) and `ecard_chooser_text` (`class="fw-bold"`). The list replaces `###ECARD_CHOOSER###` in the form template.

**Form** — the renderer strips any `<form>…</form>` wrapper from your markup (readform) and wraps everything itself:

```html
<form action="<REQUEST_URI>" method="post" name="send_ecard">…</form>
```

| Placeholder | Meaning |
| ----------- | ------- |
| `###ECARD_CHOOSER###` | The chooser list. |
| `###SENDER_NAME###` `###SENDER_EMAIL###` `###RECIPIENT_NAME###` `###RECIPIENT_EMAIL###` `###SENDER_MESSAGE###` | Value re-fill after a failed post. A `name="###SENDER_NAME###"` etc. attribute is rewritten to `ecard_sender_name`, `ecard_sender_email`, `ecard_recipient_name`, `ecard_recipient_email`, `ecard_sender_msg`. |
| `###ECARD_SUBJECT###` | Prefilled subject. |
| `<!--FORM_ERROR_START-->…<!--FORM_ERROR_END-->` | Kept only when validation failed (invalid sender or recipient address). |
| `###BUTTON###` | Not replaced by the renderer; the shipped default form carries it inside `name="###BUTTON###"`. The submit posts with `ecard_chooser` as identifier. |

**Send confirmation** — printed after a successful post. Placeholders: `###ECARD_TITLE###` (image caption), `###ECARD_IMAGE###` (thumbnail at the stored card size), `###RECIPIENT_NAME###`, `###RECIPIENT_EMAIL###`, `###SENDER_MESSAGE###`, `###ECARD_SUBJECT###`.

**Mail template** — HTML mail body when you provide one. Same placeholders plus `###SENDER_NAME###` and `###SENDER_EMAIL###`; `###ECARD_IMAGE###` here is a site-absolute `<img>` at `img_prev_width`/`img_prev_height`. Without a mail template phpwcms uses a built-in layout: title, sender line, image, message and a link back to `$phpwcms['site']`.

## Storage

One serialized value; no separate template column.

- `acontent_form` — `serialize($content['ecard'])` with `images[]` as `f_id : name : hash : ext : width : height : caption` rows, `form`, `send`, `mail`, and all chooser settings.

## Related

- Every CP shares title/subtitle, block and attribute fields: see [Core concepts](../general/concepts.md#the-content-hierarchy).
- Form handling with result storage lives in the Form CP (cp 23); this CP mails only.
- Glossary entries for terms used above: [Glossary](../modules/13-glossary.md#content-part).