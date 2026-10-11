---
title: Updating
description: In-app system update, standalone upgrade script, manual archive upgrade and 1.x migration.
sidebar_position: 4
---

# Updating

> Source: `include/inc_lib/update/update.php`, `update.api.php`, `update.backup.php`, `include/inc_tmpl/admin.update.tmpl.php`, `setup/upgrade.php`, `include/inc_lib/revision/` (`r401.php`–`r561.php`, `revision.php`), `README.md` (Upgrading), `CHANGELOG.md`

Always back up the database and files before updating, and confirm the host runs PHP 8.2+. Three paths cover every situation; all of them run the same database revision chain afterward.

**Back up before every update — no exceptions.** A full backup means both:

1. **Database dump** — complete, not the pre-update engine dump alone (`mysqldump --single-transaction <db> | gzip > backup.sql.gz` or phpMyAdmin "quick export" as gzip).
2. **Files** — at minimum `include/config/conf.inc.php`, `template/`, `filearchive/`, `content/images/`, `content/tmp/` (session/scratch), plus the current directory listing (`ls -laR > filelist.txt`) to prove what you had.

Store the backup off the web server — the built-in dumps land under `content/backup/` on the same hosting and die with it if the update or the server fails. Downloads, uploads, or `rsync` to a second host all count; a snapshot on the same disk is last resort only. Never start an update with unverified backups.

## In-app system update

**Admin → System Update** (`phpwcms.php?do=admin&p=18`) — for self-hosted installs above version 2.0.0; see [Admin panel](../administration/admin-panel.md#system-update) for the panel itself and its backup layout. The engine:

1. Fetches the latest release from the GitHub API (`slackero/phpwcms`), requires the PHP `zip` extension and writable `content/backup/`.
2. Dumps the database (pure PHP gzip dump) **before** downloading anything — dump failure aborts the run.
3. Downloads the release zip from GitHub hosts only, verifies the `.update-manifest` sha256 list per file, and stages the release extract.
4. Checks out a plan of added/modified/deleted files against the previous manifest; skips `include/config/`, `filearchive/`, `content/`, `upload/`, `setup/`, `.htaccess`, `robots.txt`. Your configuration, storage and templates survive every update.
5. Applies the plan under a maintenance flag (auto-expiring after 15 minutes, clearable in the panel) and writes the new `.update-manifest`.
6. Runs pending database revisions inline, records the run (status, file count, backup path, error) in `phpwcms_update_log`.

The built-in dump is a convenience for rollback, not your backup: it lives under `content/backup/` on the same server. Export the database separately and pull `content/backup/` off the host before you update.

Updates are blocked when a newer release would install on a half-migrated schema — run pending revisions first (a backend login triggers `phpwcms_revision_check()`). Rollback restores the pre-update file mirror per run. Older installs (2.0.0 or less) must use the standalone script or manual path.

## Standalone upgrade script

`setup/upgrade.php` is a self-contained CLI and browser updater that runs independently of the main application (own DB connection, pure-PHP dump, ZipArchive). Download it into an existing installation:

```bash
mkdir -p setup && curl -fsSL -o setup/upgrade.php \
  https://raw.githubusercontent.com/slackero/phpwcms/v2-dev/setup/upgrade.php
```

Run it either way:

```bash
php setup/upgrade.php                 # interactive CLI (prompts admin login)
# or open https://your-domain.com/setup/upgrade.php in the browser
```

Behavior:

- **Same backup rule as above, before you download anything: full database dump plus the protected files (`conf.inc.php`, `template/`, `filearchive/`, `content/images/`), stored off the server.** The script's own dump/zip under `content/backup/` covers rollback on the same host only.

- Requires PHP 8.2+ and an installed revision of `r401` or newer (the revision tracking was introduced in r401); upgrades from phpwcms 1.x without a manifest need the `--allow-unverified` flag.
- Authentication uses your admin backend credentials, including Two-Factor (TOTP or backup codes) when enabled; database migrations are gated behind that login.
- Detects the actual database table prefix (including whitelabel/`cmsgo` brand prefixes) and repairs missing whitelabel config lines in `conf.inc.php` when needed (`conf.inc.cmsgo-backup.php` keeps the original).
- Dumps the database and builds a files zip below `content/backup/<timestamp>/` (excluding `content/images/`, `content/tmp/`, `content/cache/`, `content/rss/`, `content/backup/` itself and `.git*` from the archive), then applies the release zip with the same preserved-paths rules as the in-app engine.
- Runs `phpwcms_revision_check()` up to the staged release revision, bringing the schema to r561 at the time of writing.
- CLI options: `--reinstall` or `--force` (overwrite the currently installed version again), `--allow-unverified` (upgrade legacy packages without a manifest).

## Manual upgrade

1. Back up the database **and** the protected files (`conf.inc.php`, `template/`, `filearchive/`, `content/images/`, `content/tmp/`) and store both copies off the web server — nothing in this path does it for you.
2. Extract the new release over the document root, **never overwriting** `include/config/conf.inc.php` and never deleting content in `filearchive/`, `content/images/` or `template/`.
3. Run the database migration: log into the backend once (runs pending revisions) or run `php setup/upgrade.php`; the revision chain detects outdated revisions automatically.
4. Empty `content/tmp/` and `content/cache/` so new templates and assets take effect, and flush the image cache when image handling changed ([Admin panel](../administration/admin-panel.md)).

## Database revision mechanism

`include/inc_lib/revision/revision.php` defines `PHPWCMS_REVISION`; the chain `r401.php` … `r561.php` contains the schema and data migrations since the revision tracking was introduced (r401). `phpwcms_revision_check()` compares the installed revision and runs each missing revision script in order, recording failures. The in-app updater refuses new runs until a failed migration is resolved; backend login or `setup/upgrade.php` retries the chain.

Database revisions are one-way transformations. If a revision fails half-applied, restore the database **and** files from your pre-update backup (see the backup rule at the top), then retry with the cause fixed.

## phpwcms 1.x → 2.x migration

This is the riskiest update path — legacy schema, version jumps, PHP changes. Treat the backup rule as mandatory: full database dump + all protected files, off the server, before you touch anything.

- **PHP baseline moved to 8.2.** 1.x installs run on PHP 7.4/8.0/8.1-compatible code; 2.x requires PHP 8.2 or newer — upgrade PHP first.
- **GD 1.x support removed**; pick a modern engine from the setup ([Image processing](../administration/image-processing.md)).
- The revision system starts at r401: upgrading from a 1.x state without revisions uses `setup/upgrade.php` (possibly with `--allow-unverified`) or the manual path; the in-app engine remains locked at versions 2.0.0 and below.
- Table prefixes: legacy `cmsgo`-style installs are detected and migrated automatically by the standalone script.
- Breaking changes and new features per release are listed in `CHANGELOG.md`; check it before updating production installs.

### Recommended workflow: upgrade locally, swap folders live

The production database and the old files folder are never touched during the upgrade itself. The migrated database lands under a **new table prefix** next to the old one, so the live switch is a folder swap with a rollback path that costs one folder restore.

1. **Back up the production database** — full dump (rule at the top of this page), plus the protected files list. From here on the production box stays untouched until the final switch.
2. **Install the production database locally for updating**: import the production dump into a local database. Everything from now on happens locally; only this DB copy is modified.
3. **Rename the copied tables to a new table prefix** so the migrated set can live beside the original when it comes back to the server. Generate the rename statements for every `phpwcms_*` table instead of hand-listing them:

   ```sql
   -- <NEW>_ = new prefix, e.g. cms2026_ — tables become <NEW>_phpwcms_…
   SELECT CONCAT('RENAME TABLE `', table_name, '` TO `<NEW>_', table_name, '`;')
   FROM information_schema.tables
   WHERE table_schema = DATABASE() AND table_name LIKE 'phpwcms\_%';
   ```

   Run the query, check the generated `RENAME TABLE` list twice against `SHOW TABLES;`, then execute it on the **local** copy. A spreadsheet-based variant of this recipe builds the same statements from a `SHOW TABLES;` paste; a static list can miss module tables, so prefer the generated form.
4. **Update with the new prefix**: set the new prefix (`db_prepend`) in the local `conf.inc.php` (or during local setup) and run the local 2.x application — `setup/upgrade.php` auto-detects the prefix and runs the revision chain on the renamed, data-carrying tables. Test content, modules, forms and image handling locally.
5. **Bring it live**: import the migrated database to the remote host. The remote database then holds both sets side by side — original `phpwcms_*` (old, untouched) and `<NEW>_phpwcms_*` (migrated). The remote deployment itself is mostly a **folder swap**: replace the application folder with the new 2.x build whose `conf.inc.php` points at the new prefix. Old code folder kept aside = instant rollback (point it back, and the old install still runs against its own tables).

   **Subdomain variant** — for zero-downtime switchovers, point a subdomain (e.g. `v2.example.com`) at the folder holding the new 2.x build before touching the main domain:
   - Give the subdomain its own web server section (`VirtualHost` for Apache, server block + `try_files` per the shipped `nginx.conf` for Nginx). The shipped root rewrite files (`.htaccess` / `nginx.conf`) work as-is under a subdomain document root.
   - Import the migrated database and set the new prefix in the subdomain build's `conf.inc.php`.
   - Test on the subdomain against real URLs. Because level aliases and article aliases are stored data, output matches production as soon as both builds read the same migrated set — check forms, images and shop flows, which depend on config and the migrated schema rather than aliases.
   - Switch: change the main domain's document root (or move the new build's folder) to the 2.x installation. Rollback = point the document root back to the old build; the old install still runs against its untouched `phpwcms_*` tables.

**Rollback semantics:** database revisions are one-way — the migrated set cannot be un-migrated. Rollback therefore means the folder swap back (the old `phpwcms_*` tables on the server were never modified), not reverting the migrated set. If the migrated set itself is the problem, restore it from the step-1 backup.

## Related

- [Admin panel](../administration/admin-panel.md) — panel details, maintenance flag, rollback
- [Fresh installation](fresh-install.md) — prerequisites
- [Configuration file](config-file.md) — what stays untouched during updates