---
title: Form
description: Builds a form from configurable fields, validates it, mails the result and can store it in the database.
sidebar_position: 25
---
# Form (content part 23)

> Source: `include/inc_lib/content/cnt23.readform.inc.php`, `include/inc_front/content/cnt23.article.inc.php`, `include/inc_tmpl/content/cnt23.inc.php`, `include/inc_lib/general.inc.php`

The general-purpose form builder, labeled `Form` in the backend. You add any number of fields, phpwcms renders them, validates the post and delivers the values as text or HTML mail to one or more recipients. Form tracking, spam protection, file uploads, a newsletter opt-in and double opt-in are built in.

## Purpose

One CP covers contact forms, feedback forms, "send a friend" forms and newsletter sign-up forms. Posts can additionally be stored as result sets that you download from the backend. The CP disables switching it to another type (`target_ctype` is set to 23 and disabled).

## Backend form

The dialog has five tabs (`include/inc_tmpl/content/cnt23.inc.php`).

> Source for the following three tables: Tab 1 and tab 2 fields, `include/inc_tmpl/content/cnt23.inc.php`.

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Subject | select + text | *(empty)* | Mail subject. The select maps the subject onto a form field (`formfield_<name>`); its posted value is appended to the typed subject. Empty subject falls back to `Webform: <site domain>`. |
| Recipient | select + text | Email | `Email` plus addresses (`;`-separated, lowercased, validated) or one email-typed form field (`emailfield_<name>`); the field value becomes the recipient. Empty recipient falls back to `SMTP_FROM_EMAIL`. |
| Sender email | select + text | Email | Sender address: typed address, system `SMTP_FROM_EMAIL`, or an email field used at post time. |
| Sender name | select + text | Custom | Sender display name: typed, system `SMTP_FROM_NAME`, or a text field used at post time. |
| Send copy to | checkbox + select + text | off | Adds a copy of the mail. The select picks any text field whose value receives the copy at post time; the text field accepts static CC addresses (`;`-separated, validated). A `checkboxcopy` field lets the visitor decline the copy. |
| Recipient differs from sender | checkbox | off | Rejects the post when copy address equals a recipient address. |
| Startup text | textarea, Text/HTML | — | Shown above the form until it is posted. |
| On success | textarea, Text/HTML/Redirect | — | Response after a valid post; HTML is printed, Redirect jumps to the given URL. Field placeholders `{<field>}`, `{REMOTE_IP}`, the `[EMAIL_COPY]…[/EMAIL_COPY]` block (copy address, URL-encoded in redirect mode) and `{SITE}` (redirect mode only) are replaced. |
| On error | textarea, Text/HTML/Redirect | — | Same modes for a failed validation. |

### Form fields

> Source: field loop in `include/inc_tmpl/content/cnt23.inc.php` and `include/inc_lib/content/cnt23.readform.inc.php`.

Every field card carries: Type, Name, Label, S/C (`size`: size, select columns or min value), M/R (`max`: maxlength, textarea rows, select max or step), Required, Delete, Value, Placeholder, Error text, CSS class, CSS style, and Sort handle. Types, in select order:

`text`, `email`, `tel`, `url`, `number`, `date`, `time`, `color`, `range`, `textarea`, `special`, `hidden`, `password`, `selectemail`, `select`, `country`, `list`, `newsletter`, `checkbox`, `checkboxcopy`, `radio`, `upload`, `reCAPTCHA`, `Invisible reCAPTCHA`, `captcha`, `captcha image`, `math spam protect`, `send button`, `reset button`, `break`, `break text`.

Type-specific notes verifiable in code:

- `email` — validated with `is_valid_email()`; can act as recipient, sender or sender-name source.
- `special` — value is an INI-style list: `type=` (`MIX`, `INT`, `FLOAT`, `DEC`, `IDENT`, `STRING`, `DATE`, `A-Z`, `a-Z`, `a-z`, `0-9`, `WORD`, `LETTER+SPACE`, `PHONE`, `REGEX`), `default=`, `dateformat=` (default `Y-m-d`), `validatedateformat=`, `pattern=`; `default=NOW` fills the current date in date mode. `IDENT` compares posted value with the default.
- `select` / `selectemail` / `country` / `list` — one option per line, `OPTGROUP <label>` / `/OPTGROUP` group rows, `label-|-value` pairs; a row ending in ` selected` preselects, an option value ending in ` -` renders an empty first option. `selectemail` encrypts posted values with `phpwcms_encrypt()`; `country` takes `lang=`, `default=`, `prepend=`, `first=` INI values and uses `list_country()`.
- `checkbox` / `radio` / `checkboxcopy` — `M/R` accepts `B3`, `B4`, `B5` to switch to Bootstrap form-check markup; multiple value lines build a group and post as array.
- `newsletter` — value is an INI list: `all=`, `email_field=`, `name_field=` (`+`-joined field names), `sender_email=`, `sender_name=`, `url_subscribe=`, `url_unsubscribe=`, `double_optin=`, `optin_template=`, `subject=` and numeric subscription IDs (`<id>=<label>`). Requires a valid `email_field`. A newsletter field shows a `verifyemail` textarea as the opt-in mail template.
- `upload` — value INI list: `maxlength=`, `folder=` (default `content/form/`, must exist and be writable), `accept=` (replaces `exclude=`), `exclude=` (long script/config blocklist by default), `attachment=` (attach to mail instead of link), `text_no_upload=`. Files are renamed `<time>_<sanitized name>`; error text placeholders `{MAXLENGTH}`, `{FILESIZE}`, `{FILENAME}`, `{FILEEXT}`.
- `captcha` / `captcha image` — SPAF captcha (`img/captcha.php`). The backend disables adding a second `captcha`, `captcha image`, `math spam protect`, `reCAPTCHA`, invisible reCAPTCHA or `submit` row once one of each exists in the form.
- `math spam protect` — value INI list of operators text: `+`, `-`, `*`, `:`, `calc`; the question is randomized, the result is posted as MD5 hash of a salted calculation.
- `reCAPTCHA` — INI values `site_key=`, `secret_key=` (aliases `public_key=`, `private_key=`), `lang=`, `theme=`, `type=`, `size=`; missing keys fall back to user reCAPTCHA settings.
- `text`–`radio` single values and `textarea`/`list`/`checkbox` get a "Store in profile" select when "Store in profile" is on.
- Fields whose name, label, value, error and style all stay empty are dropped. Names are cleaned with `attribute_name_clean()`; `submit` and `reset` names get an `It` suffix; duplicate base names get numeric suffixes.
- Newsletter field row 5 also carries the opt-in mail template (`cform_field_verifyemail`).

### Templates tab

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Custom HTML | textarea, ACE | — | Full custom layout, active only when Label position is `Custom`. Placeholder menu offers `{<field>}`, `{ERROR:<field>}`, `{LABEL:<field>}`. |
| Recipient template | Text/HTML radio | Text | Mail body. Text keeps raw values; HTML html-escapes strings and links uploads instead of printing the URL. WYSIWYG editor in HTML mode. |
| PHP function | text | — | Callback for the recipient mail, called before sending with `($POST_savedb, $cnt_form, $mail)`. Stored names that are already callable get a `_Proof_` prefix. |
| Copy template | checkbox "same as recipient" + text/HTML | equal | Separate body for the copy mail. |

### Result tab

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Save results in DB | checkbox | off | Writes each successful post to `phpwcms_formresult`. |
| Store in profile | checkbox | off | Enables per-field "Store in profile" selects for frontend user data. |
| Disable form tracking | checkbox | off | Omits the form tracking token; recommended for "send a friend" forms. |
| Download button | button | — | Shown when results exist; opens `include/inc_act/act_export.php?…action=exportformresult&fid=<CP id>` with a CSRF token and prints the stored row count. |
| Direct download | checkbox + 16-char key + reset/copy | off | Public export URL with `apikey` parameter; the key is regenerated server-side whenever it does not match `^[a-zA-Z0-9]{16}$`. |

> Source: result tab and `include/inc_act/act_export.php`.

The default form settings (`subject`, `label_wrap` = `|:`, `error_class` = `error`, required mark `*`, `template_equal` = 1) come from the defaults block in `include/inc_tmpl/content/cnt23.inc.php`.

## Rendering

### Label position

Four modes selected in the fields tab; `0` = table rows label/field, `1` = table rows field only (label above), `3` = DIV-based modern markup, `2` = your custom template. Required fields get the required mark appended to the label and a `required` class; each row gets `form-type-<type>` and optional `ftc-<class>`. The form element carries `id="phpwcmsForm<CP id>"`, `data-bs-toggle="validator"`, `novalidate` when set, `enctype="multipart/form-data"` with an upload field, a hidden `cpID<CP id>` identity field, all hidden fields and the form tracking token; `anchor_off`/`anchor_name` control the form anchor.

### Custom HTML tags

| Tag | Meaning |
| --- | ------- |
| `{<field name>}` | The rendered field control. |
| `{LABEL:<field name>}` | The label text. |
| `{ERROR:<field name>}` | Field error text; empty when valid. |
| `[IF_ERROR:<name>]…[/IF_ERROR]` | Kept when that field failed validation. |
| `[ELSE_ERROR:<name>]…[/ELSE_ERROR]` | Kept when it passed. |
| `[IF_ERROR]…[/IF_ERROR]` `[ELSE_ERROR]…[/ELSE_ERROR]` | Overall validation state. |
| `data-recaptchainv-submit` | Rewritten to the invisible reCAPTCHA button attributes or stripped when the form has no invisible reCAPTCHA. |

Mail placeholders: `{<field>}`, `{FORM_URL}` (the page URL), `{REMOTE_IP}` (anonymized IP in GDPR mode), `{DATE:<format>}`. Unknown placeholders are stripped after replacement.

### Processing order

A post matching `cpID<id>` runs field validation, then your custom validate function (`function validate($POST_val, $fields, $CP id)`, returning `FALSE` fails the form), then spam tracking, then mail delivery in this order: double-opt-in mail (when configured, without sending the main mail) or copy mail, then recipient mail with attachments. Uploads are deleted when validation fails. On success the startup text is replaced by the success response; on error the error response plus per-field messages (`error_class`, default `error`) precede the form again.

## Storage

- Configuration and fields: `acontent_form`, serialized (max packet raised to 16 MB in `cnt23.sql.inc.php`).
- Stored results: `phpwcms_formresult` rows `formresult_pid`, `formresult_ip`, `formresult_content` (serialized field values plus upload file URLs; double opt-in adds `hash`). Export by `exportformresult` and `exportformresultdetail` in `include/inc_act/act_export.php`.
- Form tracking tokens: `phpwcms_formtracking` (`formtracking_hash` = MD5 of IP + salt + hour, `formtracking_ip`, `formtracking_sent`); see `getFormTrackingValue()` / `checkFormTrackingValue()` in `include/inc_lib/general.inc.php`. Not written in GDPR mode.
- Uploads land in the configured folder, default `content/form/`.
- Newsletter opt-ins are inserted into `phpwcms_address` like the newsletter CP does.

## Related

- Newsletter subscription forms: see [Newsletter (cp 12)](27-newsletter-cp.md).
- [Core concepts](../general/concepts.md#rendering-model) explains how posts reach the CP renderer.