---
title: File list
description: Publish selected files from the file center as a formatted download list.
sidebar_position: 16
---
# File list (content part 7)

> Source: `include/inc_lib/content/cnt7.readform.inc.php`, `include/inc_front/content/cnt7.article.inc.php`, `include/inc_tmpl/content/cnt7.inc.php`

Publishes files picked from the file center as an ordered download list. Each file can carry its own display name, title, target, thumbnail size and copyright line. Only files with `f_public=1`, `f_aktiv=1`, `f_kid=1` (public category) end up selectable in the browse dialog.

## Backend form

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Template | select | *(empty = default)* | Template from `template/inc_cntpart/filelist/`. Empty uses the shipped default. The preset `download-inline` loads `template/inc_default/filelist_inline.tmpl`. |
| Direct download | checkbox | off | Link straight to the file in `/filearchive` instead of through `download.php`. Disabled when `filearchive/.htaccess` contains a `deny` directive. |
| Files | multi-select + sort up/down + delete | empty | Chosen files, in list order. |
| Description | textarea | — | One line per file, pipe separated: `description \| display name \| title \| target \| imagesize \| copyright \| custom URL`. |
| Text | WYSIWYG / HTML | — | Additional text above the list. |

The description hint under the textarea lists the columns in order. `target` accepts `_blank`, `_parent`, `_self` (default `_blank` when empty) — any other value acts as custom URL, with the following space separate value as its target.

## Rendering

### Template selection

Template source order: selected `template/inc_cntpart/filelist/<name>.tmpl`, else `template/inc_default/filelist.tmpl`, else the built-in skeleton:

```html
[HAS_FILES][ATTR_CLASS]<div class="{ATTR_CLASS}"[ATTR_ID] id="{ATTR_ID}"[/ATTR_ID]>
[TITLE]<h4>{TITLE}</h4>[/TITLE][SUBTITLE]<h5>{SUBTITLE}</h5>[/SUBTITLE][TEXT]{TEXT}[/TEXT]
<ul class="fileDownload">
  <li><a href="{FILE_LINK}"{FILE_TARGET}>{FILE_NAME}</a></li>
</ul>
[/HAS_FILES]
```

The per-file markup lives in the `[FILE_ENTRY]…[/FILE_ENTRY]` template section; `[HAS_FILES]` wraps the whole output so nothing renders when the file stack is empty.

### Template tags

Wrapper: `{ATTR_CLASS}`, `{ATTR_ID}`, `{TITLE}`, `{SUBTITLE}`, `{TEXT}`, `{FILE_COUNT}`.

Per file entry:

| Tag | Meaning |
| --- | ------- |
| `{FILE_ID}` | File ID. |
| `{FILE_LINK}` | Download URL via `rel_download()` (or direct file URL with "Direct download" on). |
| `{FILE_NAME}` | Display name — description column 2, else the real file name. |
| `{FILE_EXT}` | File extension. |
| `{FILE_TARGET}` | Target attribute from description column 4. |
| `{FILE_SIZE}` | Human-readable size, rounding + separator from settings. |
| `{FILE_DATE}` | File creation date, strftime format from settings. |
| `{FILE_DOWNLOADS}` | Download counter. |
| `{FILE_ICON}` | Icon markup — `icon_path`/`icon_name` settings, default system icon. |
| `{FILE_IMAGE_WxH}` … | Thumbnail of image files sized `WxH` when description column 5 says so; lightbox-ready with `lightbox_init = 1`. |

### FILE_SETTINGS (INI section)

```ini
[FILE_SETTINGS]
icon_path        =
icon_name        =
thumbnail        = 0
thumbnail_width  = 50
thumbnail_height = 50
thumbnail_crop   = 1
lightbox_init    = 0
file_size_round  = 3
file_size_space  = " "
date_format      = %m/%d/%y
set_locale       =
inline_download  = (config key `inline_download`)
```

`set_locale` applies a PHP locale for the strftime date output. `inline_download` forces `Content-Disposition: inline` for displayable types; the key comes from `config.inc.php` `$phpwcms['inline_download']` by default.

## Storage

- Selected file IDs: `acontent_form` (`file_list`)
- Per-file info lines: joined with `|` into `file_info` in `acontent_form`
- Template name: `acontent_template`
- Text / description / direct download flag: `acontent_text`, `file_descr` + `file.direct_download` in `acontent_form`

## Related

- [Link list (cp 5)](08-linklist.md) — links, not downloads
- [File center](../backend/04-files-filecenter.md)
- [File storage settings](../administration/file-storage.md)