---
title: Admin panel
description: Backend administration entry points, system update panel, backups, phpinfo and maintenance actions.
sidebar_position: 1
---

# Admin panel

> Source: `phpwcms.php` (admin navigation, `do=admin` switch), `include/inc_tmpl/admin.update.tmpl.php`, `include/inc_lib/update/update.php`, `include/inc_lib/update/update.backup.php`, `include/inc_act/ajax_connector.php`, `include/inc_act/act_file.php`

The admin panel is the backend menu section **Admin**; the pages live under `phpwcms.php?do=admin` with a page ID (`p=`) each. It appears only to admin users, and each sub-page is additionally gated by a named permission. The full page map with page IDs and permission keys is documented in [Users and usergroups](users-usergroups.md) — see its [Admin panel reference](users-usergroups.md#admin-panel-reference) table. This page covers the entries without a dedicated page: System update, phpinfo, and the two maintenance actions.

## System update

Available to super admins (`usr_admin`) as **Admin → System Update**, page ID `p=18`. The panel is backed by the self-update engine in `include/inc_lib/update/update.php`; a standalone alternative and the full update paths are described in [Updating](../installation/updating.md).

The panel shows:

| Element | Behavior |
| ------- | -------- |
| Installed version | Version, release date and database revision (`r` value) of the current install. |
| Available version | Fetched from the GitHub releases API on first visit or via *Check for update*; the result is cached in the session. |
| Release notes | Rendered from the release body with a restricted Markdown subset (inline code, bold, http(s) links, lists); the text is HTML-escaped first. |
| Backup & update button | Starts an update run; requires CSRF token and POST. |
| Update history | Table of runs from the `phpwcms_update_log` database table with status badge and file count. |
| Rollback button | Offered per history row whenever a file backup exists — including rows stuck in `running`. |

A new run is blocked while database migrations are pending or failed (`phpwcms_revision_check_temp()`) and when the installed base version is `<= 2.0.0` — older installs must use [setup/upgrade.php](../installation/updating.md#standalone-upgrade-script) instead.

### Backup handling

Before touching any file, the engine writes a run directory `content/backup/<YYYY-MM-DD-HHMMSS>/`:

| Backup artifact | Content |
| --------------- | ------- |
| `database.sql.gz` | Gzipped dump (pure PHP, no `mysqldump`) of every table whose name starts with the configured table prefix: `DROP TABLE` + `CREATE TABLE` + row INSERTs per table. Written before the release zip is downloaded; a dump failure aborts the run. |
| `files/` | Mirror of every file the run will modify or delete, in document-root-relative paths. |
| `changed-files.txt` | Per-run report of added/modified/deleted files with sha256 hashes. |

The run directory gets a generated `.htaccess` (`Require all denied` / `Deny from all`). Free disk space below 10 MB aborts the update during preflight.

### Maintenance mode and rollback

While files are applied, a flag file `content/tmp/update/maintenance.flag` marks maintenance mode; the flag auto-expires after 15 minutes (stale flags mean the frontend serves normally) and can be cleared with the panel's *Clear maintenance mode* button. On any apply failure the flag stays active so a broken state is not exposed.

Rollback restores the `files/` mirror of the chosen run, marks the run `rolled_back` in the log and removes the document-root `.update-manifest` so the next update treats the install as manifest-less.

## phpinfo

**Admin → phpinfo()** (`p=15`) embeds `phpinfo()` output as HTML under the backend header, reusing the generated CSS while overriding the default body styling. The navigation link shows only for super admins; the page itself requires any `adm` admin permission. Use it to verify PHP version, extensions (`intl`, `gd`, `imagick`, `curl`, ...), loaded INI values and environment variables on a live install — the same data the setup wizard checks, but read-only.

## Maintenance actions

Both entries render for every member of the `adm` admin group in the sidebar. Execution is additionally checked per request:

| Action | Implementation | Permission | Effect |
| ------ | -------------- | ---------- | ------ |
| Flush image cache | `include/inc_act/ajax_connector.php?action=flush_image_cache` (CSRF token required) | `adm` | Deletes every `jpg`, `png`, `gif`, `svg` and `webp` file in `content/images/` — the generated resized/cropped versions. Source files in the filestorage are untouched; images regenerate on the next request. Reports the deleted file count. |
| Move deleted files | `include/inc_act/act_file.php?movedeletedfiles=<user id>` with a confirm dialog | `adm` or `filedelete` | Finds file rows flagged `f_trash = 9` (deleted but not yet purged) and either deletes the stored file or moves it to `filearchive/can_be_deleted/`, then marks the row `f_trash = 8`. Which of the two happens is controlled by `trash_delete_files`. |

After *Move deleted files*, clean up `filearchive/can_be_deleted/` manually — phpwcms never deletes the directory itself. Also flush the image cache after changing [image settings](image-processing.md); otherwise stale resized files keep serving old dimensions.

## Related

- [Users and usergroups](users-usergroups.md) — page map and permission keys
- [Updating](../installation/updating.md) — update paths, revision chain, 1.x migration
- [Image processing](image-processing.md) — what the image cache contains
- [File storage](file-storage.md) — `trash_delete_files`, filearchive layout