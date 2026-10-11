---
title: Docs authoring contract
description: Rules every page in this documentation must follow
---

# phpwcms documentation — authoring contract

This contract governs every file under `docs/`. Any page that violates it gets fixed before merge. The `_authoring/` folder is excluded from published site generation by Docusaurus (leading underscore) but stays in the repo.

## Files

- Path pattern: `docs/<section>/<NN>-<slug>.md` inside sections, `docs/<section>/index.md` as section intro.
- `NN-` prefix: `01-, 02-…` ascending, matching `sidebar_position`. Never renumber existing files casually — links exist.
- One page = one reference unit (one content part type, one replacement tag group, one module, one admin topic). If a page needs more than ~400 lines, split it; if a sub-feature is shared, factor it into a dedicated file and link.
- Filename slugs: lowercase, hyphens, no spaces.

## Frontmatter

YAML, only these keys unless later needed by the site generator:

```yaml
---
title: Text w/image
description: Insert text with one image using position, zoom and caption options
sidebar_position: 1
---
```

- `title`: short, matches the backend label where one exists (e.g. "Text w/image"), otherwise a plain noun phrase. No "About", "Introduction".
- `description`: one sentence, present tense, ends with a period. Used as page meta.
- `sidebar_position`: integer, equals the `NN-` filename prefix (or arbitrary unique int in sections without prefixes).

## Markdown rules

- Exactly one `# H1`, equal to `title` (or close).
- `##` for sections, `###` for sub-blocks. Deeper nesting goes into sub-pages.
- American English, present tense, second person ("you") for instructions. No humor, no marketing adjectives, no "please".
- Sentences over fragments in prose. Tables and lists may be terse.
- First use of a term links to its `[[glossary]]` page: e.g. `[content part](../general/concepts.md#content-part)`.
- Every cross-link is a relative path between real files, ending `.md`. Broken links fail review.
- HTML in prose: always double-quoted attributes (`<div class="cp">`) — matches project style. PHP in prose: single-quoted strings unless interpolation is required.

## Factual rules (the important ones)

- Source of truth is the code. Every field, option, default, and tag parameter you document must be verifiable in code or config.
- Each page carries provenance: directly under the H1, one line:

  ```markdown
  > Source: `include/inc_lib/content/cnt1.readform.inc.php`, `include/inc_front/content/cnt1.article.inc.php`
  ```

  For tables, provenance goes under the section heading introducing the table.
- Defaults come from `include/config/dist.conf.inc.php` or the field's actual code default — never guessed, and never read from a live `include/config/conf.inc.php` (runtime file, installation-specific). If a default depends on another setting, say so.
- If you cannot verify a fact, either verify it now or resolve it before finishing the page. A page may hold unresolved `<!-- VERIFY: reason -->` comments only while the batch that owns it is open; final review strips them or rejects the page.
- Version notes for behavior that differs from 1.9: lead the relevant section with `:::note`-style plain blockquote, e.g. `> **Note:** Since 2.0, …`. Keep generator-agnostic formatting; no Docusaurus admonition syntax.
- Behavior differences between module versions or config modes go in the same table as a "Depends on" column, not in prose disclaimers.

## Structure patterns

### Option / field tables

```markdown
| Field | Type | Default | Description |
| ---- | ---- | ------- | ----------- |
| Position | int | 0 | Image placement: 0 = right of text … |
```

- `Type` in backend terms when the UI constrains it (checkbox, select, text, number, colorpicker); PHP types (`string`, `int`) when raw.
- One row per visible field, ordered top to bottom as in the backend form.

### Replacement tag pages

```markdown
# NAV_LIST_UL
> Source: `include/inc_front/content.func.inc.php`, `include/inc_front/front.func.inc.php`
## Syntax
`{NAV_LIST_UL: menu_type, start_id, max_depth, class_path, class_active, ul_id_name, wrap_ul_div, wrap_link_text}`
## Parameters
(table: Position/Parameter | Type | Default | Description)
## Output
(what HTML is emitted, with a trimmed example)
## Related
(links to sibling tags)
```

### Content part pages

```markdown
# <backend label> (cp id N)
> Source: files (readform / sql / takeval / renderer / list template)
## Purpose
## Backend form
(field table)
## Rendering
(template tags the CP fills, default template name, HTML sketch)
## Storage
(where the values land in phpwcms_phpwcms DB — `acontent_form`, `acontent_text`, …)
## Related
```

### Module pages

```markdown
# <Module name>
> Source: `include/inc_module/mod_<name>/`
## Purpose
## Installation / activation
## Backend
## Frontend
## Configuration
```

## Secondary references

Two legacy sources may be consulted for completeness checks and wording (both predate 2.x — code always wins):

- old project site: `https://www.phpwcms.org/`
- community wiki: `https://wiki.phpwcms.org/doku.php` (namespaces: english/start, deutsch/start, RTs, navigation, modules, content parts)
- support forum: `https://forum.phpwcms.org/` (EN/DE boards: Support, Templates, Modules, Installation) — tertiary, snippet/recipe level; verify anything from it against code.
- GitHub repo: `https://github.com/slackero/phpwcms` — releases, issue history, CHANGELOG; release notes are a valid source for [version notes](#factual-rules-the-important-ones).

Use them to notice gaps (an option the wiki documents but code shows modified: document the coded behavior). Never copy verbatim.

## Derived files

When the tree is complete (and after every content-affecting update), regenerate under `docs/` — both files are intended for AI agent consumption:

- `llms.txt` — curated index: H2-style group per section, one entry per page `> [Title](path): description` following the repo-root `example.llms.txt` format conventions.
- `llms-full.txt` — all pages concatenated in sidebar order, each preceded by its path as heading separator.

Frontmatter `description` is the source for both. A regeneration script lives in `docs/_authoring/` once the base set ships.

## Prose patterns

- Do not restate the field table in prose.
- Do not document behavior of code you did not find — "usually phpwcms does X" is banned.
- Keep code examples minimal, runnable, and copied from the code path you cite.
- When a feature requires config to be enabled, name the config key and link the admin page.