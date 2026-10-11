---
title: Guestbook/comment
description: Signs, stores, lists and paginates guestbook or comment entries with captcha, ban lists and image upload.
sidebar_position: 28
---
# Guestbook/comment (content part 18)

> Source: `include/inc_lib/content/cnt18.readform.inc.php`, `include/inc_front/content/cnt18.article.inc.php`, `include/inc_front/content/cnt_functions/cnt18.func.inc.php`, `template/inc_default/guestbook.tmpl`

A signable guestbook or comment list. Entries share one storage thread identified by the CP id (or an alias id), so several content parts can feed the same guestbook. The CP supports a WYSIWYG-free entry form, SPAF captcha, word and IP ban lists from the template, flood protection, image upload and per-entry image thumbnails with zoom.

## Purpose

Collect visitor posts and render a paginated, sortable list. Entries are stored in their own table, not in article content, so they survive CP edits and can be moderated in the backend article view (trash flag in `phpwcms_guestbook`).

## Backend form

Fields top to bottom (`include/inc_tmpl/content/cnt18` via `include/inc_tmpl/content/cnt18.inc.php` and readform normalization).

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Template | select | *(empty = default)* | File from `template/inc_cntpart/guestbook/` (`BS5.tmpl` shipped); empty falls back to `template/inc_default/guestbook.tmpl`. |
| Listing | radio + number | All entries | `All entries` renders the complete list without navigation; `Page listing` activates pagination and needs a non-zero entries-per-page value. |
| Sort order | checkbox | — | Descending (newest first, default) when off, ascending when on. |
| Image upload | checkbox | off | Adds the file input; stores to `content/guestbook_<cid>/`, JPG/GIF/PNG only, size-limited. |
| Max image size | text | `file_maxsize` config | Upload size limit, shortened byte string. |
| Banned words | text | — | Space-separated list, merged with the template `BAN` section. |
| Alias ID | number | — | Reuse another guestbook CP's entry thread (`guestbook_cid`). |
| Flood time | number, seconds | — | Minimum seconds between posts. |
| Cookie flood check | checkbox | off | Also sets a `phpwcms_guestbook<cid>` cookie for the flood check. |
| Captcha | checkbox | off | Shows the SPAF captcha input. |
| Captcha max chars | number | 5 | Accepted code length, clamped 1–15. |
| Visible only when logged in | checkbox | off | Non-logged-in visitors see the `LOGIN_INFO` template section instead of form and list. |
| Post only when logged in | checkbox | off | List stays public; the sign form is replaced by the `LOGIN_INFO` section. |
| Check URL | checkbox | off | Tries to open the posted homepage via `fopen` before accepting it. |
| Notify by email | checkbox + text | off | Sends a plain-text notice per entry to the given addresses. Needs the template file `template/inc_cntpart/guestbook/notify_email.txt`; without it a one-line default is sent. |

## Rendering

The template file is split into sections; each section has `<!--SECTION_START//-->` / `<!--SECTION_END//-->` markers.

| Section | Role |
| ------- | ---- |
| `FORM` | Entry form; wrapped in `<form name="sign_guestbook" method="post"` plus `enctype` when uploads are on, closed with the form tracking token. |
| `FORM_ERROR` | Kept only after a failed post. |
| `FORM_SUCCESS` | Confirmation after a stored entry. |
| `NAV` | Pagination, archive and jump controls. |
| `GUESTBOOK` | Outer list wrapper; `{FORM}` and `{NAV}` placeholders, `GUESTBOOK_ENTRY` block per entry. |
| `BAN` / `BAN_REPLACE` / `BAN_IP` | Banned words, replacement (default `***`), banned IPs from the default template. Entries are filtered case-insensitively. |
| `COMMENT` | Wraps inline comments written in messages as `[c]comment[/c]`; default separator `<span class="gbComment">|</span>`. |
| `LOGIN_INFO` | Shown instead of form and/or list when a login mode is active. |

### Form tags

`{EMAIL}` `{NAME}` `{URL}` `{MSG}` refill posted values, `{GBSHOW_0/1/2}` check the "display email" radios (show, hide, obfuscate as `name at host dot tld`), `{CAPTCHA}` prints the captcha image, `[IMAGE_ERROR]{IMAGE_ERROR}[/IMAGE_ERROR]`-style blocks carry the per-error messages: `ERROR_EMAIL`, `ERROR_NAME`, `ERROR_URL`, `ERROR_IMG`, `ERROR_IMGUPLOAD`, `ERROR_IMGFORMAT`, `ERROR_IMGWRITE`, `ERROR_IMGSAVE`, `ERROR_IMGSIZE`, `ERROR_CAPTCHA`. Post fields are `guestbook_email`, `guestbook_name`, `guestbook_url`, `guestbook_msg`, `guestbook_show`, `guestbook_captcha`, `guestbook_image`.

### List and navigation tags

| Tag | Meaning |
| --- | ------- |
| `{EMAIL}` `{NAME}` `{MSG}` | Entry data; message runs through `html()` and `nl2br()`. |
| `{URL:HTML}` | Homepage link, prefixed `http://`. |
| `{ID}` / `{DBID}` | Running entry number / `guestbook_id`. |
| `{TIMESTAMP:m/d/Y H:i}` | Entry date via `date()` format. |
| `[EMAIL]…[/EMAIL]` | Dropped when the visitor chose "hide email"; obfuscated text replaces the mailto in obfuscate mode. |
| `[IMAGE]{IMAGE:50x50x1}[/IMAGE]` | Cached thumbnail; third segment 1 enables the zoom popup via `image_zoom.php`, width/height 0 = proportional. |
| `{FIRST:<h>}` `{BACK:<h>}` `{NEXT:<h>}` `{LAST:<h>}` | Page links, argument is the link text. |
| `{PAGE:5:&nbsp;\|<strong>\|</strong>}` | Page number links; `count`, spacer, active wrap. |
| `{ARCHIVE:%Y/%m\|all entries\|go\|&nbsp;}` | Month/year archive select from `FROM_UNIXTIME(guestbook_created, …)`; label, submit text, spacer arguments; URL params `gbd` (format) and `gbs` (value). |
| `{JUMP:page\|go\|&nbsp;}` | Page jump select, posts `showguestbookpage`. |

Entries whose message contains `[url` are excluded from listing (spam marker). New posts set `?guestbookentry=<id>` internally and redirect to avoid double posts; the created entry is then shown.

### Notify mail

The notify template replaces `{FE_URL}`, `{BE_URL}`, `{IP}` (anonymized in GDPR mode), `{BROWSER}`, `{DATE}`, `{NAME}`, `{EMAIL}`, `{URL}`, `{MESSAGE}`, `{IMG_NAME}`, `{IMG_URL}` and sends from `admin_email`.

## Storage

- Entries: `phpwcms_guestbook` — `guestbook_cid` (thread), `guestbook_msg`, `guestbook_name`, `guestbook_email`, `guestbook_url`, `guestbook_show`, `guestbook_created` (unix time), `guestbook_trashed`, `guestbook_ip` (remote or anonymized IP), `guestbook_useragent` (`MD5` of the user agent), `guestbook_image`, `guestbook_imagename`.
- Configuration: `acontent_form`, serialized.
- Images: `content/guestbook_<cid>/<hash>.<ext>`.

## Related

- Login gating works with the [Frontend login (cp 28)](34-frontend-login.md) CP.
- [Users and usergroups](../administration/users-usergroups.md#backend-vs-frontend-users) — frontend login status drives the visible/post modes.