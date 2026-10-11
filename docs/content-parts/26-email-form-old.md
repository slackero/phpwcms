---
title: Email form generator
description: Legacy content part that renders a form from pipe separated field codes and posts it to the standalone formmailer endpoint.
sidebar_position: 26
---
# Email form generator [old] (content part 10)

> Source: `include/inc_lib/content/cnt10.readform.inc.php`, `include/inc_front/content/cnt10.article.inc.php`, `include/inc_tmpl/content/cnt10.inc.php`, `include/inc_act/act_formmailer.php`

**Deprecated.** The backend lists this type as `Email form generator [old]` (`$wcs_content_type[10]` in `include/inc_lib/article.contenttype.inc.php`). It has no template support, no per-field errors, no result storage and no double opt-in. Use the Form CP (cp 23) for new forms.

## Purpose

A quick contact form defined as one text block. The post target is the standalone endpoint `include/inc_act/act_formmailer.php`, which validates, rate-limits and mails the values as plain text.

## Backend form

Fields as in the dialog, top to bottom (`include/inc_tmpl/content/cnt10.inc.php`).

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Subject | text, max 250 | `Online webform email message` | Mail subject. |
| Recipient | text | — | Single, address-validated recipient. Empty or invalid blocks the save with a backend error message. |
| Button text | text, max 35 | `Send` | Submit button label. |
| Send as | radio | Text | `Text` or `HTML` mail type; posted as the hidden field `type`. |
| Form fields | textarea, one field per line | — | Field codes, see table below. |

### Field code syntax

One line per field: `TYPE|name|required|label|length,maxheight|value or options|width(px)`.

| Code | Control | `length,maxheight` | Segment 5 |
| ---- | ------- | ------------------ | --------- |
| `IT` | Text input | size, maxlength | Prefilled value |
| `IN` | Text input, numeric key filtering | size, maxlength | Prefilled value |
| `IP` | Password input | size, maxlength | — |
| `IH` | Hidden input | — | Hidden value |
| `TA` | Textarea | cols, rows (default 3) | Content |
| `SM` | Select menu | — | Options split by `#`; `value%text` pairs |
| `SL` | Select list | size, multiple flag | Options like `SM` |
| `IC` | Checkbox group | columns per row | Options like `SM`; value defaults to 1 |
| `IR` | Radio group | columns per row | Options like `SM`; value defaults to 1 |
| `SC` | Country select | — | Default country code |
| `CA` | Captcha input plus image | size, maxlength | — |

A `required` value of 1 adds the field name to the hidden `required` list posted to the formmailer; `CA` fields are forced to required (readform sets `name=Captcha_Validation`, `required=1`). Lines that do not start with a known code are dropped; an empty name becomes `field_<random>`.

## Rendering

A hard-coded table inside `<form action="include/inc_act/act_formmailer.php" method="post">`; labels get `class="formLabel"` and a filled-marker for required fields, controls get `inputText`, `formTextArea`, `selectMenu`, `selectList`, `formButton` classes. The renderer appends the hidden fields `subject`, `recipient`, `type`, the `required` list, and the form tracking token from `getFormTrackingValue()`.

### Formmailer endpoint behavior

`include/inc_act/act_formmailer.php` accepts the CP post plus optional hidden fields you may add manually to the field codes: `language` (2-letter formmailer language, default `EN`), `charset` (default `utf-8`), `recipient_name`, `label` (`name|label` pairs for error messages), `redirect`, `redirect_template`, `redirect_error`, `redirect_error_template`, and `send_copy` (honored only when `$phpwcms['formmailer_set']['allow_send_copy']` is on; the recipient is overridden by `$phpwcms['formmailer_set']['global_recipient_email']` when configured). The endpoint checks the HTTP referrer, enforces 10 posts per 60 seconds per IP file-based, verifies the form tracking value, validates captcha and required fields and answers failures with the translated error page from `include/inc_lang/formmailer/` or your error redirect. A valid post is mailed as plain text table to the recipient.

## Storage

`acontent_form` holds a `#:#`-joined, non-serialized string: `base64(field lines) #:# subject #:# recipient #:# button text #:# mail type`. Nothing else is written; posts are never stored.

## Related

- Replacement: [Form (cp 23)](25-form.md) covers the current form builder with templates, storage and opt-ins.
- [Templates](../general/concepts.md#templates) — this CP ignores CP template selection.