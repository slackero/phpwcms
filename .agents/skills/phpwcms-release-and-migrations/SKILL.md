---
name: phpwcms-release-and-migrations
description: Branch lineage, database revision engine (rNNN.php), composer.json caret-range policy, release branch merge workflow, and self-update manifest packaging for phpwcms. Use when creating DB revisions, updating composer dependencies, merging to master/phpwcms-legacy, or building release packages.
---

# phpwcms Releases, Composer & Database Revisions Skill

Use this skill when adding database revision scripts (`rNNN.php`), running `composer update`, merging development branches into release branches, or packaging self-update releases.

## 1. Branch & Version Lineage
- `v1.9-dev` → `phpwcms-legacy` (1.9.x legacy releases, PHP 7.4)
- `v1.10-dev` → `master` (1.12.x current stable releases, PHP 8.2+)
- `v2-dev` (2.0.0-dev / `v2.0.0-beta.x`, PHP 8.4+)
- **Important**:
  - Development branches (`v2-dev`, `v1.10-dev`, `v1.9-dev`) are the leading branches. Merge forward only (`v1.10-dev` → `master`, `v1.9-dev` → `phpwcms-legacy`). **Never** merge `master` or release commits back into a development branch.
  - No released phpwcms 2.x version exists yet (only `v2.0.0-beta.x` from `v2-dev`). Ignore unrelated git tags `v2.4.x`–`v2.5.2` when writing changelogs or security advisories.

## 2. Database Revision Engine (`include/inc_lib/revision/`)
- `phpwcms_revision_check()` iterates and runs **every** pending `include/inc_lib/revision/rNNN.php` file up to `PHPWCMS_REVISION` (defined in `include/inc_lib/revision/revision.php`).
- Completion markers are written to both `content/tmp/rNNN.checked.tmp` and the database `sysvalue` table (`revision_rNNN`).
- **Global Revision Numbering Rule**:
  - Revision numbers (`rNNN`) form **one shared global sequence** across all branches (`v1.9-dev`, `v1.10-dev`, `v2-dev`).
  - **Never** reuse an `rNNN` number on an older branch with different logic than a newer branch (otherwise an install upgrading from 1.12 to 2.0 will have `rNNN.checked.tmp` set and skip 2.0's migration).
  - **Never** add a new revision number on `v1.9-dev` unless `v1.9-dev` is on-par with `v1.10-dev` under the same revision numbers.

## 3. Composer Dependency Policy (`composer.json`)
- **Caret ranges matching installed version**: After running `composer update`, update `composer.json` constraints so every package states its **actually installed version as a caret range** (e.g., `"^2.10.3"` when `2.10.3` is installed), not an older lower bound and not an exact pin (keep exact pins only where pre-existing by design, such as `netcarver/textile`).
- Verify with `composer update --dry-run` (must report `"Nothing to install, update or remove"`).
- **Branch-switch lockfile gotcha**: `composer.lock` is gitignored and persists across `git checkout`. Always run a real `composer update` using the target branch's MAMP PHP binary after switching branches before trusting `composer show` or `--dry-run`.

## 4. Release Branch Merge Workflow (`master` / `phpwcms-legacy`)
1. Before merging a dev branch into its release branch, clean up `## [Unreleased]` in `CHANGELOG.md` on the dev branch so it only lists changes since the previous release tag.
2. Merge the dev branch into the release branch (`master` or `phpwcms-legacy`) and perform all production trimming **inside the merge commit itself** (no follow-up cleanup commits):
   - Resolve `modify/delete` conflicts toward **deleted** for dev-only files: `CLAUDE.md`, `DEPENDENCIES.md`, `include/vendor/phpstan/**`, `include/vendor/bin/*`, and `roave/security-advisories`.
   - Run `composer update --no-dev` using the release branch's PHP binary *before* committing the merge so `include/vendor/` and `composer/installed.*` are in production shape inside the merge commit.
3. Follow the merge commit with a single `release: bump version to X.Y.Z` commit that updates `CHANGELOG.md` (`## [X.Y.Z] - YYYY-MM-DD`), `include/inc_lib/revision/revision.php`, and `README.md`.
4. After completing work on a non-v2 branch, run `git checkout v2-dev && make sync` to ensure `/Users/slackero/Sites/dev-phpwcms` is back on `v2-dev`.

## 5. Self-Update Packaging (`v2-dev`)
- Generator script: `bin/build-release-package.php --tag=<tag> [--out=<dir>] [--manifest-only]` (note: `/bin/*` is gitignored except `bin/get-oauth-token.php`). Write output to a repo-local directory (e.g., `build/`), not `/tmp`.
- Manifest format: `<sha256>  <relative/path>` (two spaces), placed at the zip root as `.update-manifest`.
- Engine skip lists (never in manifest, never overwritten):
  - `SKIP_PATHS`: `include/config/`, `filearchive/`, `content/`, `upload/`, `setup/`
  - `SKIP_FILES`: `.htaccess`, `robots.txt`, `.update-manifest`
- Incremental asset: every release also gets `phpwcms-<tag>-changes-from-<previous-tag>.zip`, built from the tag (never the working tree) with every added/modified file plus `DELETE-THESE.txt` listing removed paths:

  ```bash
  while IFS= read -r f; do mkdir -p "$stage/$(dirname "$f")"; git show "$tag:$f" > "$stage/$f"; done \
      < <(git diff --name-only --diff-filter=ACMR "$prev" "$tag")
  git diff --name-only --diff-filter=D "$prev" "$tag" > "$stage/DELETE-THESE.txt"
  (cd "$stage" && zip -qr "$out" .)
  ```

  Upload it with `gh release upload` and describe it in the notes. It is a manual-update archive, not an engine package: `buildPlan()` treats every file missing from the new manifest as deleted, so never feed a diff manifest to the updater.
- GitHub release upload note: `gh release upload` renames leading-dot files (`.update-manifest` becomes `default.update-manifest`), so upload standalone manifests under an explicit name like `phpwcms-<tag>.update-manifest`.
