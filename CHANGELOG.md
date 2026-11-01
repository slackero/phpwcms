# Changelog

All notable changes to the phpwcms v1.9-dev branch are documented in this file.
This branch is the legacy line for PHP 7.4 and older installations; new features land on v1.10-dev.

## [Unreleased]

### Fixed
- **Skipped Revisions on Upgrade:** `phpwcms_revision_check()` only executed the newest available revision file and relied on every revision calling its predecessor, so an upgrade could silently skip pending revisions and leave articles hidden behind converted date fields. It now iterates every revision up to the target revision, and the checked markers are persisted in `phpwcms_sysvalue` as well, so a cleaned `content/tmp/` folder no longer loses upgrade state. Refs #389, #390.

### Dependency Updates
- **Dependency Refresh:** `league/commonmark` 2.10.4 → 2.10.7, the current `simplepie/simplepie` snapshot and the transitive `symfony/polyfill-mbstring` 1.43.0 → 1.43.3. Constraints state the installed versions as caret ranges; the PHP 7.4 platform and the `>=7.4` minimum are unchanged.
