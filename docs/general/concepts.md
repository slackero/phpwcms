---
title: Core concepts
description: Structure levels, articles, content parts, templates and how phpwcms assembles a page.
sidebar_position: 1
---

# Core concepts

> Source: `include/inc_front/content.func.inc.php`, `index.php`

phpwcms organizes websites into a small set of orthogonal entities. Understanding these four — structure levels, articles, content parts, templates — is enough to work both as an editor and as a template developer.

## The content hierarchy

A site is one tree of **structure levels**. Each level holds any number of **articles**, and each article is a stack of **content parts** that make up its individual page.

```text
Structure level (site section)
├── Article (page)
│   ├── Content part 1
│   ├── Content part 2
│   └── …
├── Article (page)
└── sub-Structure level
    └── …
```

- <a id="structure-level"></a>**Structure level** — navigation-ready category node. Carries its own page template, level keywords, and URL alias. One level can be marked as visible in the menu or hidden but reachable.
- <a id="article"></a>**Article** — a page of the site (or a list entry when the level is configured to list its article summaries). Holds metadata (title, keywords, description, dates, visibility, remote URL) plus its content parts stack.
- <a id="content-part"></a>**Content part** — one functional block: text with image, image gallery, teaser list, form, search, map, and so on. This is where content editing happens; the [content parts reference](../content-parts/index.md) documents every type.
- <a id="article-summary"></a>**Article summary** — every article has an always-present summary block used in listings, teasers and RSS output.

The tree, articles and content parts live in the database (`phpwcms_categories`, `phpwcms_article`, `phpwcms_articlecontent`); uploaded binaries and converted images live on disk under `/filearchive` and `/content`.

## Templates

Layout never lives in the database content. A **layout template** (managed in the backend "Templates" section, stored in `phpwcms_template`) defines the HTML skeleton of a page and marks slots with `{` `}` layout tags:

```html
<header>{HEADER}</header>
<nav>{NAV_LIST_UL:F,0,3,,act_path}</nav>
<main>{CONTENT}</main>
<footer>{FOOTER}</footer>
```

The frontend engine fills `{CONTENT}` with the rendered article stack, `{HEADER}`/`{FOOTER}` etc. with your admin-defined header/footer code, and replacement tags like `{NAV_LIST_UL:…}` with generated blocks. The full tag set is documented under [Template layout tags](../templates/layout-tags.md).

Many content parts select an additional, article-specific **content part template** out of `template/inc_cntpart/<type>/*.tmpl`; each content part type ships [default templates](../templates/default-templates.md) that fall back automatically when nothing is selected. Layout and content stay separable: change the template, keep the content parts.

## Rendering model

A frontend request flows through `index.php` → `include/inc_front/content.func.inc.php`:

1. Resolve the requested URL to a structure level and article (`aid`/`<article-alias>`/level alias).
2. Gather all content parts of the article in sort order. Each part is rendered by its renderer in `include/inc_front/content/cnt<N>.article.inc.php`, optionally through a selected `.tmpl` template.
3. Replace layout tags and replacement tags over the assembled page.
4. Run registered [frontend render scripts](../frontend/frontend-scripts.md) — your hook layer for custom filtering and output.
5. Emit the page; frontend caching applies per configuration.

Result pages are assembled per request; caching and gzip are backend-configurable. See [Page rendering](../frontend/page-rendering.md) for the tag pipeline in order.

## URLs and aliases

Default URLs are search engine friendly and alias based: `/index.php?<level-alias>` and `/index.php?<level-alias>,aid_<alias>` are canonical; with configured rewriting (`mode_rewrite` in `config.inc.php`) they become pure path URLs. Article aliases, level aliases and 301 forcing are all admin-configurable — details in [URL handling](url-handling.md).

## Users, groups and permissions

Backend access works through **users** and **usergroups**. A usergroup controls which backend sections and modules are visible, plus per-structure-level and per-file-category write rights; users inherit from their group, with per-user overrides. Frontend "visibility" of whole levels or single articles is independent from backend rights, letting the same CMS run a public site and a members-only area. See [Users and usergroups](../administration/users-usergroups.md).

## Modules

Modules are optional functional units (shop, calendar, glossary, newsletter, ads, …) shipped in `include/inc_module/`. Activated per installation in the admin panel and per usergroup, they add backend sections, their own tables and — where applicable — content part types that bridge module data into articles. See the [modules reference](../modules/index.md).

## Where the data lives

| Entity | Database table(s) |
| ------ | ----------------- |
| Structure levels | `phpwcms_categories` |
| Articles | `phpwcms_article` |
| Content parts | `phpwcms_articlecontent` |
| Templates / layout | `phpwcms_template`, `phpwcms_pagelayout` |
| Files | `phpwcms_file`, `phpwcms_filecat` |
| Backend users / groups | `phpwcms_user`, `phpwcms_usergroup` |
| Module data | one set per module (e.g. `phpwcms_shop`, `phpwcms_calendar`, `phpwcms_glossary`) |

Table prefixes (`db_prepend`, brand prefix) are set in the admin panel during setup; [Installation](../installation/setup-wizard.md) explains.