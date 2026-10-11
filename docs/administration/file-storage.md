---
title: File storage
description: Filestorage settings, upload limits and validation, FTP takeover and filearchive layout.
sidebar_position: 3
---

# File storage

> Source: `include/inc_lib/general.inc.php` (upload validation), `include/inc_tmpl/files.private.upload.tmpl.php`, `include/inc_tmpl/files.ftptakeover.tmpl.php`, `include/inc_act/act_file.php`, `include/config/dist.conf.inc.php`

All uploaded files live in a single storage directory on the server, referenced by hash inside the database. There is no backend GUI for these values — file storage behaves according to the settings in `include/config/conf.inc.php` (defaults below come from `dist.conf.inc.php`).

## Settings

> Source: `include/config/dist.conf.inc.php`; validation flow in `include/inc_lib/general.inc.php`

| Key | Type | Default | Effect |
| --- | ---- | ------- | ------ |
| `file_path` | string | `'filearchive'` | Filestorage directory name below the document root. |
| `ftp_path` | string | `'upload'` | Directory the [FTP takeover](#ftp-takeover) reads. Must not start or end with a slash. |
| `file_maxsize` | int (bytes) | `52428800` (50 MB) | Per-file upload size cap. Also clamps the guestbook image size. Effective limits are additionally bounded by PHP's `upload_max_filesize` / `post_max_size`. |
| `allowed_upload_ext` | string (comma-separated) | long extension list (jpg, png, webp, pdf, zip, mp4, ...) | Extension whitelist for uploads. A file whose extension is not listed is rejected with HTTP error 415. |
| `multimedia_ext` | string | `'aif,aiff,mov,...'` | Extensions the file browser treats as multimedia entries. |
| `trash_delete_files` | bool | `false` | If `true`, emptying a file trash physically deletes the stored file. If `false`, the maintenance action moves it to `filearchive/can_be_deleted/` instead; see [Admin panel](admin-panel.md#maintenance-actions). |
| `sanitize_dlname` | bool | `0` | Rewrite special characters in `Content-Disposition` filenames on download when browsers choke on them. |
| `inline_download` | bool | `1` | Try to display downloads in the browser (`inline`) instead of forcing `attachment`. |
| `recipient_count` | int | `0` | Legacy key; the setting is no longer read in 2.x code and has no effect. It still exists in `conf.inc.php` written by the setup wizard. |

There is no storage quota system: the only limit is `file_maxsize` per file plus the disk space of the host.

## Upload processing

Every upload — through the file center dialog, drag-and-drop or FTP takeover — passes the same checks in order:

1. Filename sanitization, extension detection.
2. Size check against `file_maxsize`.
3. Target directory creation below the filestorage.
4. Extension check against `allowed_upload_ext` (case-insensitive). Rejected uploads remove the temporary file.
5. For image targets: `getimagesize()` against an allowlist of image types — content sniffing by actual image data, not by client MIME type.

Incoming client MIME types are only trusted when they match a MIME format pattern; otherwise the MIME type is derived from the extension map in `include/inc_lib/mimetype.inc.php`. On download, the `fileinfo` extension re-detects the MIME type of `application/octet-stream`/`force-download` files.

## filearchive layout

The filestorage stores files under their md5 `<hash>.<ext>` names, which the `phpwcms_file` database table maps to original names, folders, categories and permissions. Special entries:

| Entry | Meaning |
| ----- | ------- |
| `<hash>.<ext>` files | Uploaded originals as placed by upload/FTP takeover. |
| `can_be_deleted/` | Landing folder of the *Move deleted files* maintenance action. Never auto-purged; delete manually. |
| `image_placeholder.png` | Serves as fallback when a referenced source image is missing. |
| `image_memoryinfo.png` | Replaces an image when PHP memory is too low to resize it. |

The default `.htaccess`/web-server config should treat the directory as non-executable storage; downloads flow through `download.php` with resumable range support and ETag headers, not direct links.

## FTP takeover

Files uploaded by FTP into `ftp_path` (default `upload/`) appear in **Files → FTP upload**, listing name, size and date with checkboxes. Submitting the form (`include/inc_act/act_ftptakeover.php`) imports the marked files into a chosen file-center folder: they move into the filestorage under hash names and get database rows. The directory is scanned without an extension filter, but each import still passes the [upload validation](#upload-processing) checks.

## Related

- [Admin panel](admin-panel.md) — maintenance actions that clean or purge the filestorage
- [Users and usergroups](users-usergroups.md) — file categories (`p=7`) permission
- [Fresh installation](../installation/fresh-install.md) — writable directories on first install