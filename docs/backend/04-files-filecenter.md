---
title: Files — file center
description: Upload, organize, describe and download files from the backend file center.
sidebar_position: 4
---
# Files — file center

> Source: `include/inc_tmpl/files.reiter.tmpl.php`, `include/inc_lib/files.private-functions.inc.php`, `include/inc_lib/files.public-functions.inc.php`, `include/inc_tmpl/files.private.*.tmpl.php`, `include/inc_act/act_file.php`, `include/inc_act/act_download.php`, `include/inc_act/act_multiupload.php`

The **Files** section (`phpwcms.php?do=files`) organizes everything a site uploads. Folders and files are rows of the `phpwcms_file` table (`f_kid = 0` = folder, `f_kid = 1` = file), not filesystem directories; binaries live on disk under the configured `file_path`, stored under an MD5 hash name. The file center nav shows three entries, each behind its own permission: File center (`filecent`), File actions (`fileaction`, `p=4`), Multiple file upload (`fileupload`, `p=8`).

## Tabs

> Source: `include/inc_tmpl/files.reiter.tmpl.php`, `include/inc_tmpl/files.private.trash.tmpl.php`, `include/inc_tmpl/files.search.tmpl.php`

| Tab | URL | Content |
| --- | --- | ------- |
| Private files | `f=0` | Your own folder tree and files; admins see all users' files, others only `f_uid =` their own ID. Upload, new folder, editfile and editdir forms replace the list while open. |
| Public files | `f=1` | Read-only listing of all files with `f_public = 1` and `f_aktiv = 1`; a user drop-down filters by owner. |
| Search | `f=3` | Full-text search over file name, description and tags across the private area. |
| Trash can | `f=2` | Files moved to trash (`f_trash = 9`), restorable from here. |

The toolbar above the tabs is per-tab: upload and new-folder buttons on the private tab, expand/collapse-all, thumbnail on/off (stored per user in `wcs_user_thumb`), and a help dialog listing every icon. A cut file changes the new-folder button into a paste button.

## Folders and files

> Source: `include/inc_lib/files.private-functions.inc.php` (`list_private()`), `include/inc_act/act_file.php`

Folders and files offer:

| Control | On folders | On files |
| ------- | ---------- | -------- |
| Edit (pencil) | Folder name/description form (`editdir`) | File detail form (`editfile`) |
| More actions | Upload into folder, new subfolder, paste (when cut is active), delete when empty | Download (`act_download.php?dl=<id>`), cut/paste between folders, move to trash when not in use |
| Eye icon | Toggles `f_aktiv` | Toggles `f_aktiv` |
| Lock icon | Toggles `f_public` | Toggles `f_public` |

Only the owner or an admin may move a file to trash (`act_file.php?trash=`), which flags the row `f_trash` — the binary stays on disk until an admin runs *Move deleted files* (admin or `filedelete` permission): with `trash_delete_files` off it moves the binaries to `filearchive/can_be_deleted/`, with it on the files are physically unlinked.

The colored dot next to every file is the usage indicator from `files.private-usage.inc.php`: red = currently referenced by content (articles, or calendar/glossary/shop module data), not deletable; yellow = uploaded less than 24 hours ago and not yet used; green = unused and 24 hours or older; black = was referenced before but is no longer. The same status appears in file detail and disables the trash action while content uses the file.

## Upload

> Source: `include/inc_tmpl/files.private.upload.tmpl.php`

The single-file upload form (`f=0&upload=0` or `&upload=<folder_id>`) posts one file plus metadata:

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Directory (`file_pid`) | select | root | Target folder. |
| File (`file`) | file | — | One file per submit; client-side size check against the effective max (PHP `upload_max_filesize` / `post_max_size` / `file_maxsize`, lowest wins), plus the `file_maxsize` config limit of 52428800 bytes (50 MB). |
| Use IPTC data as caption (`file_iptc_as_caption`) | checkbox | `iptc_as_caption` config | Fills title, description, copyright and alt from the image IPTC block; tags preview shows below the field while a file is selected (via `exif.min.js`). |
| Title / Description / Copyright / Alt | text | — | One set per active language when `allowed_lang` holds more than one (`f_vars` per-language storage); IPTC fallback fills every language when `iptc_as_caption_all_lang` is set. |
| Keywords (`file_keywords`) | selects | — | Predefined file categories/keys from `phpwcms_filecat` / `phpwcms_filekey`; "needed" categories must select a value. |
| Additional info (`file_shortinfo`) | text | — | Short line, max 750 chars. |
| Tags (`file_tags`) | tag input | — | Autosuggest over existing file tags; saved into the tag table. |
| Sorting (`file_sort`) | number | 0 | Order inside the folder, before `f_name`. |
| Active (`file_aktiv`) | switch | `set_file_active` config | File is visible in listings. |
| Granted download (`file_granted`) | switch | 0 | See download protection below. |
| Public (`file_public`) | switch | `set_file_active` config | File appears in the public tab / public listing. |
| Gallery download (`file_gallerydownload`) | switch | 0 | Marks the file for gallery download handling. |

Forbidden names (`.htaccess`, `web.config`, `lighttpd.conf`, `nginx.conf`, leading dot) and non-whitelisted extensions (`allowed_upload_ext`) are rejected; SVG dimensions are read by an SVG metadata parser.

## Multiple file upload and FTP takeover

> Source: `include/inc_tmpl/files.ftptakeover.tmpl.php`, `include/inc_act/act_multiupload.php`, `include/inc_act/act_ftptakeover.php`

`do=files&p=8` combines two import paths in one page:

1. **Dropzone multi-upload** — drag-and-drop many files at once into the configured folder; each file becomes a normal file row (`f_aktiv = 1`, `f_public = 1`).
2. **FTP takeover** — lists files found in the configured `ftp_path` directory on the server; checkboxes select them, and a form applies directory (or a new folder), keywords, metadata and status switches to every marked file before it is imported into the file center.

## File detail

> Source: `include/inc_tmpl/files.private.editfile.tmpl.php`, `include/inc_lib/files.private-usage.inc.php`

The file edit form shows ID, hash, size, extension, image width/height in pixels, creation date and the usage indicator, plus all fields of the upload form. Editing notes:

- The file name may change; a missing extension is appended automatically. Renaming the alias deletes cached derived images in `content/images/` that used the old alias.
- The binary itself is not replaceable through this form — the hash never changes; replace a file by uploading a new one and updating references.
- IPTC data of the stored image is listed and can be applied as title/description/copyright/alt with one checkbox.

## Download protection

> Source: `include/inc_act/act_download.php`

Backend downloads always pass through `include/inc_act/act_download.php`. Without it the physical file stays in `filearchive/` under its hash name. Access rules:

| File state | Who can download |
| ---------- | ---------------- |
| `f_public = 0` (`private`) | Owner and admins only |
| `f_public = 1`, `f_aktiv = 1` | Any authenticated backend user; with `pl` parameter also frontend flows |

`f_granted` ("Secured frontend download only") marks a file that frontend file resolution (`_getFileInfo()`) skips for visitors without a frontend login — such files resolve and download only while a frontend user is logged in. Backend downloads always pass through `include/inc_act/act_download.php` and stay reachable for the owner and admins regardless:

| File state | Who can download from the backend |
| ---------- | --------------------------------- |
| Listed from the private tree (`dl` without `pl`) | Owner and admins only |
| Listed from the public tab (`dl` + `pl`) | Any backend user for `f_public = 1` files; own files in any state |

## File actions

> Source: `include/inc_tmpl/files.actions.tmpl.php`

The bulk page (`p=4`, permission `fileaction`) works on one selected folder: it lists the files with checkboxes and applies one action to all marked files — delete to trash, move to another folder, set active/public status, or reassign owner (admin use). Below it, an *unused files* search lists files with no content references; their trash action stays disabled while the usage check still reports references.