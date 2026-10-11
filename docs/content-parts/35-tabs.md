---
title: Tabs
description: Renders tab or accordion blocks from multiple titled sub-tabs, plus the content-part tab grouping feature.
sidebar_position: 35
---
# Tabs (content part 32)

> Source: `include/inc_lib/content/cnt32.readform.inc.php`, `include/inc_front/content/cnt32.article.inc.php`, `include/inc_tmpl/content/cnt32.inc.php`, `template/inc_default/tabs.tmpl`, `include/inc_front/content.article.inc.php`

Two related mechanisms share this page: the `Tabs` content part (several sub-tabs inside one CP, rendered from a template file) and the per-CP "Subsection" setting that groups several content parts of an article into a tab or accordion block (`$content['cptab']` in `include/inc_front/content.article.inc.php`).

## Purpose

The CP builds tabbed or accordion content without page reloads; every sub-tab carries title, optional headline, WYSIWYG text, optional link and an active flag. Tab content feeds the article search index automatically.

## Backend form

Fields top to bottom (`include/inc_tmpl/content/cnt32.inc.php`).

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Template | select | *(empty = default)* | File from `template/inc_cntpart/tabs/` (`Accordion.tmpl`, `BS5-Tabs.tmpl`, `simplegmaps.tmpl`). Empty falls back to `template/inc_default/tabs.tmpl`; when no template resolves, the CP prints a plain `<dl>` outline. |
| No WYSIWYG editor | checkbox | off | Disables the editor for tab texts. |
| Tabs | repeat list | 1 empty tab | Add, sort and delete tabs. Per tab: title (default `Tab #<n>`, `-` hides the title), headline, text (WYSIWYG; plain text input without a WYSIWYG editor), link (redirect link, same syntax as article link targets, with optional target), and an active checkbox — an inactive tab is hidden but keeps its data. |
| Custom fields group | select (visible when defined) | — | Fieldgroup from `template_default['settings']['tabs_custom_fields']`; each tab gains the group's extra inputs. |

Without a WYSIWYG editor, tab text lacking `<` is converted with `plaintext_htmlencode()` at save time.

## Rendering

Template resolution order: selected `template/inc_cntpart/tabs/<file>`, else `template/inc_default/tabs.tmpl`. The template uses two sections, `TABS` (wrapper) and `TABS_ENTRY` (per tab).

| Tag | Meaning |
| --- | ------- |
| `[ATTR_CLASS]` `{ATTR_CLASS}` `[ATTR_ID]` `{ATTR_ID}` `[TITLE]` `{TITLE}` `[SUBTITLE]` `{SUBTITLE}` | Wrapper, CP title/subtitle. |
| `[TABS_ENTRIES]` `{TABS_ENTRIES}` | All rendered entry blocks. |
| `{TAB_COUNT}` | Number of rendered (active) tabs. |
| `{ID}` | Content part record ID; default markup builds `id="tab{ID}"`. |
| `{TABID}` | Running tab number starting at 1. |
| `[FIRST]` `[LAST]` | Present on first/last entry. |
| `[TABTITLE]` `{TABTITLE}` `[TABTITLE_ELSE]` | Title text; else-branch runs for empty titles, `-` suppresses the whole block. |
| `[TABCONTENT]` | Present when headline or text is non-empty. |
| `[TABHEADLINE]` `{TABHEADLINE}` `[TABTEXT]` `{TABTEXT}` | Headline (escaped) and body (WYSIWYG HTML). |
| `[TABLINK]` `{TABLINK}` `{TARGET}` | Read-more link from the tab link field; `{TARGET}` is the link target attribute. |
| `[TAB_<CUSTOM_FIELD>]` | Custom field value, uppercased key; `bool` fields render the block as flag, `option`/`select` fields add `[TAB_<FIELD>_<OPTION>]` variants, `file` fields render the file list markup of cp 7, `markdown`/`plain`/`html` renders honor the render mode. |

Fallback without any template: `headline()` plus the auto-built `<dl>` of titles and texts stored in `acontent_html`.

## Storage

| Column | Content |
| ------ | ------- |
| `acontent_form` | Serialized tab array (per tab: title, headline, text, link, active, custom fields) plus `tabwysiwygoff` and `tab_fieldgroup`. |
| `acontent_template` | Template file name. |
| `acontent_text` | Search string: stripped title + headline + text + custom field values of all tabs. |
| `acontent_html` | Fallback `<dl>` outline. |

## Content part tab grouping

> Source: `include/inc_lib/article.readform.inc.php`, `include/inc_front/content.article.inc.php`, `include/config/conf.template_default.inc.php`

The common CP settings (article content dialog) offer a "Subsection" select: Off, `Tabs`, `Group (accordion)` and any custom group defined in `$template_default['attributes']['cpgroup_custom']`. Stored in `acontent_tab` as `<number>|<type>_<title>` (number = grouping index, title optional).

At render time (`content.article.inc.php`) content parts sharing the same block and number are collected into `$content['cptab']`; the first CP of each group leaves a `<!-- tab-<hash> -->` or `<!-- cpgroup-<hash> -->` comment placeholder that later receives the assembled block. The assembled markup depends on type:

- **Tabs (type 1)** — `<div id="tab-<hash>">` with a navigation `<ul>` (`tab-navigation`, `tab-item-<n>`, `tab-first`/`tab-last` classes) linking to per-tab `<div id="tab-<title-hash>-<n>">` content containers (`tab-content`, `tab-content-item`).
- **Group/accordion (type 2)** — `<div id="cpgroup-<hash>">` containing per-item heading `<h3>` (`cpgroup-title`) with either a `<span data-cpgroupid="…">` or a link (attribute `cpgroup = 'href'`), and content wrappers (`cpgroup`, `cpgroup-<n>`, `cpgroup-content`, first/last classes).
- **Custom group types** — wrap each part with the `prefix`/`suffix` patterns of `$template_default['attributes']['cpgroup_custom'][<type>]`, where `%1$s` is the group title and `%2$s` the group id.

## Related

- Content part templates and template settings: [Core concepts](../general/concepts.md#templates).
- Glossary: [Content part](../modules/13-glossary.md#content-part).