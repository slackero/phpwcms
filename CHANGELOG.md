# Changelog

All notable changes to this project will be documented in this file.

## [Unreleased]

### Security
- **Password Reminder Tokens:** Content Part 28 replaced the account password during an anonymous reminder request and mailed the new value. The reminder now issues a single-use token, stores only its SHA-256 hash in `sysvalue`, expires it after one hour, and drops any older token for the account. The password changes only after the token is submitted with a new password (minimum 8 characters, entered twice); a used or expired token is rejected. Reminder and reset requests are rate limited per IP. Shipped templates use the new `{RESET_URL}` placeholder and no longer contain a password. A reset does not terminate other active frontend or backend sessions; those have to be logged out separately.
- **Random Image Archive Visibility:** `img/random_image.php` served any image found in a directory below the private file archive, ignoring the file record. Files in the archive are now filtered through the same public, active, non-trashed and anonymous `f_granted=0` rule as `_getFileInfo()`, so an unregistered or private image is never returned. Directories outside the archive keep working. A request that already carries a logged-in frontend session may also receive granted images and is sent with no-cache headers.
- **Glossary Template Traversal:** the Glossary renderer concatenated the stored `glossary_template` below the module template directory and read it with `file_get_contents()`, so a stored `../../../../index.php` disclosed deployment files. The name is resolved through the new `phpwcms_template_file_path()`, which requires the canonical file to stay inside the template directory and rejects absolute paths, stream wrappers, traversal and symlink escapes. The editor stores the name only when it resolves to a file inside the template directory.
- **Form Upload Active Types:** the distributed `content/form/.htaccess` blocked server scripts but not active documents. `.html`, `.htm`, `.xhtml`, `.svg`, `.xml`, `.js` and related types are denied too.
- **Password Reminder PRNG:** `generic_string()` reseeded the PRNG from the request microsecond before drawing characters. It now uses `random_int()` only, so a replacement password cannot be reconstructed from the request time.
- **Private File Thumbnail Tooltip XSS:** the thumbnail tooltips in `include/inc_lib/files.private-functions.inc.php` still HTML-encoded the file name and copyright into a single-quoted `Tip()` call. The HTML parser decodes `&#39;` before JavaScript runs, so an apostrophe ended the string. Both values use `js_inline_string()` now, like the other file lists.
- **Random Image Path Traversal:** `img/random_image.php` joined the unauthenticated `imgdir` parameter to `PHPWCMS_ROOT` and removed `../` once, so a value such as `....//` escaped the root. The value is resolved with `realpath()` through the new `phpwcms_image_dir_path()` and accepted only inside the root, which also rejects symlinks that leave it.
- **Frontend Image Grant Check:** `img/cmsimage.php` filtered only `f_public`, so images marked for logged-in frontend users (`f_granted=1`) were served anonymously. Both the numeric-ID and hash lookups add `f_granted=0` unless a frontend user session is logged in, matching `_getFileInfo()`. A backend session alone does not lift the restriction, so backend previews of granted images also return the empty image, and responses for a logged-in frontend user now carry no-cache headers.
- **Article Visibility Ownership:** `include/inc_act/act_articlecontent.php` case 3 changed `article_aktiv` by article id without an owner check, so a non-admin could publish an article owned by another user. The update now requires `article_uid` to match the current user or an administrator session, the same rule the other cases use.
- **Form Export Authorization:** the non-API branch of `include/inc_act/act_export.php` checked login and CSRF but not the requested form, so any backend user could export the stored submissions and source IPs of any form id. The form must now belong to an article the user owns, or the user must be an administrator; a validated direct-download API key still authorizes its own form.
- **Content Part 21 Local File Read:** the manual page address accepted `file://` and other local stream wrappers, and `include_url()` fetched the value with `file_get_contents()`, so an author could publish server files. Only `http` and `https` URLs are accepted when saving and in `include_url()`; the save-time probe is gone. Existing content parts that stored a site-local path in the manual field now render empty and have to be entered as an external URL or selected from the page file list.
- **Private File Tooltip XSS:** the private file list and the file center put the file name and copyright into a single-quoted inline JavaScript `Tip()` call while only HTML-encoding them. The HTML parser decodes `&#39;` before JavaScript runs, so an apostrophe ended the string. Both values are escaped with `js_inline_string()` now.
- **Upload Executable Extensions:** the backend upload paths and the FTP takeover accepted server-executed extensions. One shared extension list checks every dot-separated segment, `saveUploadedFile()` and the private upload reject executable extensions without consulting `allowed_upload_ext`, stored names drop a trailing dot and neutralise an embedded handler segment, and the takeover checks the file before it becomes executable in the web-served storage. Revision `r564` widens the quarantine list of `r563`.
- **Web Server Default-Deny:** Apache, nginx and IIS deny HTTP access to `content/{tmp,cache,log,backup}`, `include/config`, `include/vendor`, `upload/`, `filearchive/` and `tests/`. nginx and IIS changed from a static directory pass-through to an explicit allowlist, so renaming a private directory no longer exposes it. Directory-local deny files travel with a renamed directory, existing `Deny from all` files are upgraded to Apache 2.4 syntax, and PHP execution is blocked inside private and upload directories.
- **Form Export Key Comparison:** the direct-download form export matched the 16-character key through an unescaped SQL `LIKE` pattern, so wildcard characters matched any stored key. The key is validated as alphanumeric and compared with `hash_equals()`.

### Fixed
- **Alias and Refid Key Length:** fresh utf8mb4 installs failed with `ERROR 1071`, because the full `varchar(1000)` alias indexes need 4000 bytes. `article_alias`, `acat_alias` and `calendar_refid` are capped at `varchar(500)`; revision `r568` truncates longer aliases duplicate-free first and then shrinks the columns, and `calendar_refid` keeps a 768-character prefix index.
- **Multibyte Alias Truncation:** `proof_alias` was cut with `substr()` at 230 bytes and could split a UTF-8 sequence. It uses `mb_substr()` with `PHPWCMS_CHARSET`.
- **Setup HTML Escaping:** the manual setup escaper double-encoded named and hex entities and emitted invalid UTF-8 unchanged. It uses `htmlspecialchars()` with `ENT_QUOTES` and `ENT_SUBSTITUTE`, double encoding off, and maps backslashes to `&#92;` like the core wrapper.

### Changed
- **jQuery 3.8:** the backend loads jQuery 3.8.0, and the frontend library set gains jQuery 3.8 (Migrate 3.6.0) and 4.1 (Migrate 4.0.2) in plain, migrate and slim variants. Existing versions stay selectable.

---

## CDN-Replaced Libraries (How to host locally)

The following libraries have been migrated to CDN loading by default to reduce repository bloat. To run them locally, download the assets and place them in the following paths:

### 1. Prism.js
* **Download:** [PrismJS Download](https://prismjs.com/download.html)
* **Local Paths:**
  * `template/lib/prism/prism.css`
  * `template/lib/prism/prism.js`
  * `template/lib/prism/prism-autoloader.min.js`
* **Configuration:** Update Prism templates in `template/inc_cntpart/code/example/` to replace CDN links with local files.

### 2. SyntaxHighlighter
* **Local Paths:**
  * `template/lib/syntaxhighlighter/styles/shCoreDefault.css`
  * `template/lib/syntaxhighlighter/shCore.js`
  * `template/lib/syntaxhighlighter/shBrushJScript.js` (and other language brushes)
* **Configuration:** Update SyntaxHighlighter templates in `template/inc_cntpart/code/example/` to replace CDN links with local files.

### 3. SWFObject
* **Local Path:** `template/lib/swfobject/swfobject.js`
* **Configuration:** The loader in `include/inc_front/js.inc.php` automatically detects if the file exists at this path. If not found, it automatically falls back to CDN.

### 4. HTML5shiv & Respond.js
* **Local Paths:**
  * `template/lib/html5shiv/html5shiv.min.js`
  * `template/lib/respond/respond.min.js`
* **Configuration:** The loader in `include/inc_front/content.func.inc.php` automatically detects if the files exist at these paths. If not found, it automatically falls back to CDN.
