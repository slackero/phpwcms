---
title: Poll
description: Runs a one-choice vote per client IP and shows results as percentage bars.
sidebar_position: 31
---
# Poll (content part 89)

> Source: `include/inc_lib/content/cnt89.readform.inc.php`, `include/inc_front/content/cnt89.article.inc.php`, `include/inc_tmpl/content/cnt89.inc.php`

A simple voting widget. Each choice is a text line, optionally paired with an image. One vote per client IP; the vote and its result live inside the content part row itself, so no extra tables are involved. The CP ships with template classes (`pollBarCell`, `pollBarMain`, `pollBarPercent`, `pollRadioCell`, `pollInfo`, `pollSubmitButton`) instead of a selectable template.

## Purpose

Quick opinion polls: the visitor picks a radio, submits, and afterwards sees the accumulated result with bar-percentage output. Results update the stored CP configuration directly.

## Backend form

Fields top to bottom (`include/inc_tmpl/content/cnt89.inc.php`).

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Attribute class | text | — | Extra CSS class merged into the CP wrapper class; empty falls back to `defaultPollClass` at render time. |
| Button text | text | *(empty)* | Submit label; the button always gets `class="pollSubmitButton"`. |
| Choices | textarea, one line each | — | Choice texts; the line index pairs with the image at the same position. Line order can be changed by re-editing the CP. |
| Image | multi-select | none | Optional image per choice from the file center, order via up/down buttons. |
| Max width | number, px | `content_width` | Choice image width; capped when `RESPONSIVE_MODE` is off or empty. |
| Max height | number, px | — | Choice image height, proportional when 0. |
| Enlarge | checkbox | off | Stored per image list; rendered images use the cached thumbnail values (the zoom view is only evaluated when the image renderer builds it). |

## Rendering

Two states, keyed on the client IP (anonymized under `PHPWCMS_GDPR_MODE`):

**Vote form** — radio button per choice with the paired image below, submit button, and a hidden `hidden_acontent_id` identifying this CP. Posted to the current frontend URL.

**Result table** — shown to IPs found in the stored vote list. Each choice prints its text, optional image, a result bar and the percentage:

```html
<span class="pollBarBegin"></span>
<span class="pollBarMain" style="width:<percent>px;"></span>
<span class="pollBarPercent"><percent>%</span>
```

Bar width is `round(votes / total * 100)` in px. The vote is written by an `UPDATE` of this content part's `acontent_form`, incrementing the choice counter and registering the IP — there is no history of individual votes.

## Storage

- `acontent_form` — serialized `choice` (text lines), `count` (votes per choice index, seeded 0) and `ip` (voted IPs or anonymized IPs).
- `acontent_image` — serialized `images`, `width`, `height`, `zoom` for the choice images.
- `acontent_text` — serialized `poll_buttontext` and `poll_buttonstyle`.

## Related

- GDPP/IP handling conventions: [Core concepts](../general/concepts.md#where-the-data-lives).
- Glossary: [Content part](../modules/13-glossary.md#content-part).