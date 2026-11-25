---
name: phpwcms-docs-and-wiki
description: Authoring and verifying phpwcms user documentation in docs/, regenerating llms.txt, editing the standalone phpwcms-wiki repository, and refreshing the phpwcms-textmate grammar. Use when writing user docs, wiki pages, or adding replacement tags.
---

# phpwcms Documentation, Wiki & Grammar Skill

Use this skill when authoring or verifying user documentation in `docs/`, editing the GitHub Wiki, or updating replacement tag grammars.

## 1. Source-of-Truth Priority
When documenting features, replacement tags, content parts, or configuration, resolve conflicts in this strict order:
1. **Repository PHP/JS/template source code** (ultimate source of truth)
2. **GitHub repository** (`https://github.com/slackero/phpwcms` — issues, releases, `CHANGELOG.md`)
3. **Live local dev site** (`https://dev.phpwcms.local/phpwcms.php` — verify field labels, ordering, and rendered HTML)
4. **phpwcms Wiki** (`https://wiki.phpwcms.org/doku.php` & GitHub wiki)
5. **Project website & Forum** (`https://www.phpwcms.org/`, `https://forum.phpwcms.org/`)

## 2. In-Repo User Documentation (`docs/` on `v2-dev`)
- `docs/` exists on `v2-dev` and contains Docusaurus-ready Markdown documentation (`title`, `description`, `sidebar_position` frontmatter).
- Always follow the authoring rules in `docs/_authoring/format-contract.md`.
- After adding or modifying pages in `docs/`:
  1. Verify all internal links:
     ```bash
     php84 docs/_authoring/linkcheck.php
     ```
  2. Regenerate `docs/llms.txt` and `docs/llms-full.txt`:
     ```bash
     php84 docs/_authoring/gen-llms.php
     ```

## 3. Standalone GitHub Wiki (`/Users/slackero/Dropbox/Development/phpwcms/phpwcms-wiki`)
- The GitHub Wiki (`https://github.com/slackero/phpwcms.wiki.git`) lives in the sibling directory `/Users/slackero/Dropbox/Development/phpwcms/phpwcms-wiki` (do not clone throwaway copies into `/tmp`).
- Conventions:
  - Page filename (with dashes for spaces) defines the URL.
  - When a page is superseded/moved, keep the old `.md` file as a one-line stub: `The article has moved to [[New Page]].`
  - Use `[[Page Title]]` for internal wiki links and standard Markdown `[label](url)` for external links.
  - Links from the wiki into the repository `docs/` directory must target the `v2-dev` branch (`https://github.com/slackero/phpwcms/blob/v2-dev/docs/...`), not `master`.

## 4. TextMate Grammar (`/Users/slackero/Dropbox/Development/phpwcms/phpwcms-textmate`)
- IDE grammar tooling lives in the sibling repository `/Users/slackero/Dropbox/Development/phpwcms/phpwcms-textmate` (never add grammar files into `phpwcms-dev`).
- After adding new replacement tags in phpwcms, refresh the grammar vocabulary by running:
  ```bash
  python3 /Users/slackero/Dropbox/Development/phpwcms/phpwcms-textmate/build-grammar.py
  ```
