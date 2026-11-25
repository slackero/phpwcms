---
name: phpwcms-dev-workflow
description: Local development environment, MAMP PHP binaries per branch, PHPStan execution, dev-site rsync (make sync / make minify-sync), git hygiene, and sandbox rules for phpwcms. Use whenever running PHP CLI, linting, running PHPStan, syncing to the test server, or performing git operations.
---

# phpwcms Development Workflow Skill

Use this skill whenever running PHP commands, static analysis (PHPStan), syncing changes to the local test web server, or managing git state.

## 1. Branch-to-PHP Binary Mapping
Always use the PHP command matching the active branch (plain `php` may point to a missing or outdated version). These are executables symlinked into `~/.local/bin`, so they resolve in any shell, interactive or not:

| Branch | Minimum PHP | Command |
|---|---|---|
| `v2-dev` | **PHP 8.4+** | `php84` |
| `v1.10-dev`, `master` (1.12.x) | **PHP 8.2+** | `php82` |
| `v1.9-dev`, `phpwcms-legacy` (1.9.x) | **PHP 7.4** | `php74` |

- Forward-compatibility syntax checks can also be run with `php85` or `php86`.
- Always run `<php-command> -l <file.php>` on every modified PHP file.
- Never introduce PHP 8-only functions (`str_contains`, `str_starts_with`, `str_ends_with`, named arguments) into `v1.9-dev` / `phpwcms-legacy`.

## 2. Running PHPStan
- Always run the **bundled** PHPStan binary (`include/vendor/bin/phpstan`) with the branch's PHP command and `--memory-limit=2G --no-progress`:
  ```bash
  # On v2-dev:
  php84 include/vendor/bin/phpstan analyse -c .phpstan/phpstan.neon --memory-limit=2G --no-progress

  # On v2-dev (PHP 8.5 config):
  php85 include/vendor/bin/phpstan analyse -c .phpstan/phpstan-php85.neon --memory-limit=2G --no-progress

  # On v1.10-dev / master:
  php82 include/vendor/bin/phpstan analyse -c .phpstan/phpstan.neon --memory-limit=2G --no-progress
  ```
- **Never** use global `~/.composer/vendor/bin/phpstan` (version mismatch produces ~180 phantom baseline errors).
- **Never** run PHPStan under `php8.6.0` (its `phpstan_turbo` extension targets a different module API).

## 3. Syncing to the Local Test Server (`/Users/slackero/Sites/dev-phpwcms`)
The development repo is not the Apache document root; `/Users/slackero/Sites/dev-phpwcms` (`https://dev.phpwcms.local/`) serves the live test site.

- **Only `v2*` branches may be synced to `/Users/slackero/Sites/dev-phpwcms`**. Never sync `v1.10-dev`, `v1.9-dev`, `master`, or `phpwcms-legacy` to the dev site (it runs the v2 database schema).
- After making code changes on `v2-dev`, automatically sync them so the dev site stays current:
  - Run `make minify-sync` if CSS or JS files were modified (runs `npm run build` then syncs).
  - Run `make sync` if only PHP/template files changed.
- **Troubleshooting `make sync` Error 23**: `make sync` pipes `git ls-files --cached --others --exclude-standard` into `rsync`. If a tracked file was deleted on disk but not staged in git, `rsync` exits with code 23 (`stat: No such file or directory`). Run `git status --short | grep '^ D'` and stage (`git add`) the deletions before re-running `make sync`.
- **Protected files & directories**:
  - **NEVER** pass `--delete` to `rsync`. Existing files in `/filearchive/`, `content/images/`, and `content/tmp/` on the web server must never be deleted.
  - **NEVER** overwrite `include/config/conf.inc.php` or installation-owned config files (`include/config/{conf.indexpage.inc.php,conf.template_default.inc.php,feeds.ini.php,sitemap.custom.ini,sitemap.custom.php,iptc.inc.php}`).
  - **NEVER** create `include/vendor/.htaccess` (vendor protection and the TinyMCE exception `/include/vendor/(?!tinymce/|mklkj/tinymce-i18n/)` live exclusively in the root `.htaccess`, `_.htaccess`, `nginx.conf`, and `web.config`).
- **MAMP Mail / Xdebug ini note**: If `mail()` on the dev site stops reaching MailHog (`http://localhost:8025/mailhog/`), check `ini_get('sendmail_path')` in the web SAPI — MAMP PRO can accidentally prepend an Xdebug stderr log line to line 5 of `/Library/Application Support/appsolute/MAMP PRO/conf/php<version>.ini`, aborting ini parsing before `sendmail_path`.

## 4. Sandbox & Temporary Directories
- Under CLI tool sandboxes, writes to `/tmp` can be shadow-redirected so child processes (`tar`, `unzip`, `zip`) cannot see directories created by PHP or earlier subshells.
- For any extraction, archive build, or temp file that a child process must read, create a temporary directory inside the repository (e.g., `build/tmp-...`) and delete it when finished.

## 5. Git & Documentation Hygiene
- **Protect uncommitted user work**: Before modifying a file that already has uncommitted changes, run `git stash push -- <file>`. Never run `git checkout -- <file>` or `git restore <file>` to discard uncommitted work. If a mistake ever occurs, state it plainly and ask the user how to recover — never mount or dig through Time Machine snapshots.
- **Commits**:
  - Never auto-commit after finishing edits; only commit when explicitly asked.
  - When the user says "commit" or "amend", execute `git commit` / `git commit --amend` immediately in the same turn without printing a commit message preview first.
  - Never add `Co-Authored-By:` trailers to commit messages.
- **Local developer notes (`doc/`)**:
  - Store all scan findings (`doc/codebase-scan-*.md`) and developer/feature notes inside the gitignored `doc/` directory with dated, English-only filenames.
  - Never create developer notes at the repository root and never `git add -f` files inside `doc/`.
