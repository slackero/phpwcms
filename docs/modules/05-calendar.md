---
title: Calendar
description: Manages dated entries in the backend and renders calendars on the frontend with the CALENDAR replacement tag.
sidebar_position: 5
---
# Calendar

> Source: `include/inc_module/mod_calendar/`

The calendar module stores dated entries — events with start/end, location, description and images — in the backend and renders them on frontend pages through the `{CALENDAR}` replacement tag. Module registration (`module.default.php`): `$_module_name = 'calendar'`, `$_module_type = 0` (backend and frontend), no content part, frontend render hook only.

## Backend

Available to usergroups with the calendar module enabled. URL: `phpwcms.php?do=modules&module=calendar`.

### Listing

`backend.listing.php` shows a month calendar view with prev/next month navigation — the displayed month is kept in the session (`calendardate=MM-YYYY`). A word filter searches title, tag and text; status filter checkboxes limit the view to active or inactive entries. Actions per entry:

| Action | GET parameter | Behavior |
| ------ | ------------- | -------- |
| Activate / deactivate | `verify` | Toggles `calendar_status` between 1 and 0 |
| Edit | `edit` | Opens the entry form; the form's *save entry as duplicate* checkbox stores a copy on save |
| Delete | `delete` | Soft delete — sets `calendar_status = 9` |

The backend loads its CSS from `include/inc_module/mod_calendar/template/backend.calendar.css`.

### Entry form

> Source: `backend.editform.php`, `inc/processing.inc.php`

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Title (what) | text | – | Required; validation error `calendar_title` |
| Start date / time | date pickers | Today / now | `flatpickr` date entry (format `DD.MM.YYYY`), stored as datetime |
| End date / time | date pickers | start + 1 hour | Stored as datetime |
| All day | checkbox | off | Hides the time inputs, sets `calendar_allday` |
| Repeats | select | 0 = Does not repeat | 0–16: daily (1), Mon–Fri (2), Mon/Wed/Fri (3), Tue/Thu (4), weekly (5), monthly (6), yearly (7), individual weekdays (8–14), Wed–Sun (15), weekend (16); values above 16 reset to 0 |
| Repeat period | date range | – | Shown only when a repeat interval is selected; start and end date bound the repeats |
| Where | text, max 220 chars | – | Location; doubles as frontend `place` filter value |
| Calendar (tags) | tag list | – | Comma separated tag list, autosuggest select |
| Language | text | – | Autosuggest, characters outside `a-z` and `-` stripped |
| AID / alias / URL | text, max 500 chars | – | Frontend target for entry links (`calendar_refid`) |
| Teaser text | textarea | – | Short text for list views |
| Description | WYSIWYG | – | Full entry text, edited with the backend WYSIWYG editor |
| Image | image select + zoom/lightbox/caption/link | – | Serialized image data stored in `calendar_object` |
| Status | checkbox | – | Active state (`calendar_status`, 0/1) |
| Save as duplicate | checkbox | – | Stores the entry again as a new copy |

An entry needs start or end within the selected range to appear in frontend queries; the frontend query requires `calendar_status = 1`.

## Frontend

> Source: `frontend.render.php`, `inc/calendar.class.php`

The render hook parses `{CALENDAR:...}` tags in the finished page markup (registered through `$_module_fe_render`).

### Tag syntax

Two syntaxes exist, chosen by whether an `=` appears in the tag body:

```text
{CALENDAR:items = 10
template = calendar.tmpl
place = Town}

{CALENDAR:count, [template], [lang], [href], [tags]: [date_start], [date_end], [place]}
```

The first is INI style with newline separated `key = value` pairs, the second positional — square brackets mark optional values.

### Parameters

| Parameter | Value | Description |
| --------- | ----- | ----------- |
| `items` | int | Maximum entries (positional variant: `count`) |
| `template` | text | Template file name, see [Templates](#templates) |
| `lang` | text | Restrict to one language |
| `tag` / `tagmode` | text / `OR`,`AND` | Filter by tag and match mode |
| `href` | text | Fallback link target |
| `place` | text | Restrict to entries with this `where` value |
| `gettype` | text | Character that splits `calendar_title` into `{TITLE}` and `{TYPE}` |
| `teaserwords` | int | Truncate teaser to this word count |
| `width`, `height`, `crop` | int / int / int | Image dimensions for the entry image |
| `expired` | `hide`, `top`, `bottom` | Where expired entries go |
| `expired_date` | `START`, `END` | Which date decides expiration |
| `expired_prefix`, `expired_suffix` | text | Markup wrapped around expired entries |
| `date_start`, `date_end` | see below | Date range for the query |

Date values for `date_start` accept `TODAY`, `WEEKSTART`, `MONTHSTART`, `YEARSTART` or any `strtotime` string; `date_end` accepts an integer (days added to start), `TODAY`, `WEEKEND`, `MONTHEND`, `YEAREND`, or a relative value like `7 DAYS`, `2 WEEKS`, `3 MONTHS`.

Site visitors can change the range: GET, POST or session parameters with the `pcal_` basis (`pcal_limit`, `pcal_place`, `pcal_start`, `pcal_end`) override the tag values for the request (session `$_SESSION['pcal']`); the `{CALENDAR_RESET}` tag link clears them.

The query selects entries with status 1 where the repeat range start is not after the requested period end and the repeat range end is not before the period start, ordered by `calendar_start` ascending.

### Item template tags

| Tag | Contents |
| --- | -------- |
| `{ID}`, `{GROUP}`, `{URL}`, `{TARGET}`, `{TITLE}`, `{TYPE}` | Entry id, repeat group, link, title parts (see `gettype`) |
| `{TEASER}`, `{TEXT}`, `{PLACE}`, `{ALLDAY}` | Text fields and all-day flag |
| `{IMAGE}` and `{IMAGE_ID}`, `{IMAGE_NAME}`, `{IMAGE_ALT}`, `{IMAGE_TITLE}`, `{IMAGE_TEXT}`, `{IMAGE_COPYRIGHT}`, `{IMAGE_URL}`, `{IMAGE_URL_TARGET}`, `{IMAGE_LIGHTBOX}`, `{IMAGE_LINK}`, `{IMAGE_LINK_TARGET}` | Image fields |
| `{RANGEDATE}`, `{STARTDATE}`, `{ENDDATE}` | Dates; note `{STARTDATE`/`{ENDDATE` written without closing brace in template files are remapped to the date renderer |
| `{CALENDAR_RESET}` | Link resetting visitor range selections |

### Templates

> Source: `inc/calendar.class.php`

Template files resolve in this order: first `include/inc_module/mod_calendar/template/<name>`, then `template/calendar/<name>` in the site layout directory. A sample ships as `include/inc_module/mod_calendar/template/calendar.tmpl`; when no template is set, a hardcoded default markup renders the list.

## Storage

> Source: `setup/default_sql/phpwcms_init.sql` line 274

Entries live in `phpwcms_calendar`: `calendar_id`, `calendar_created`, `calendar_changed`, `calendar_status` (0 inactive, 1 active, 9 deleted), `calendar_start`, `calendar_end`, `calendar_allday`, `calendar_range` (0–16 repeat interval), `calendar_range_start`, `calendar_range_end`, `calendar_title`, `calendar_where`, `calendar_teaser`, `calendar_text`, `calendar_tag`, `calendar_object` (longtext, serialized image data), `calendar_refid`, `calendar_lang`.

Tags are stored as entries in the core category tree `phpwcms_categories` with `cat_type = 'calendar'`; the entry id is the parent id of the tag rows. There is no dedicated calendar category table and no separate categories management UI — tags are managed on the entry form.

## Configuration

The module registers no own configuration keys. Backend access follows the module group in the usergroup editor — see [Modules overview](index.md).

## Related

- [Modules overview](index.md) — architecture and activation
- [Glossary](13-glossary.md) — the other module with frontend replacement rendering beyond content parts