---
name: phpwcms-translations
description: Guidelines and workflow for adding, translating, or editing backend language keys ($BL) across all phpwcms language files in include/inc_lang/backend/. Use whenever adding or modifying backend translation strings.
---

# phpwcms Backend Translations Skill

Use this skill whenever adding, updating, or auditing `$BL[...]` language keys in `include/inc_lang/backend/*/lang.inc.php`.

## 1. Multi-Language Coverage vs. Fallback Rules
- **Base language (`en`)**: `include/inc_lang/backend/en/lang.inc.php` is always loaded first as the default fallback language before being overloaded by the active language.
- **No untranslated English duplicates**: Never copy raw English (`en`) strings untranslated into other language files just to fill keys.
- **Translate new keys across all backend languages**: When introducing a new `$BL['...']` key for a feature, translate and insert it across all backend language files (`include/inc_lang/backend/*/lang.inc.php`: `ar`, `bg`, `bn`, `bs`, `ca`, `cs`, `da`, `de`, `de-at`, `de-ch`, `en`, `es`, `et`, `eu`, `fi`, `fr`, `gl`, `gr`, `hi`, `hr`, `hu`, `id`, `is`, `it`, `ja`, `lt`, `lv`, `mk`, `ms`, `nl`, `no`, `pa`, `pl`, `pt`, `ro`, `ru`, `sk`, `sl`, `sq`, `sr`, `sv`, `ta`, `tr`, `ua`, `ur`, `vi`, `zh-cn`).

## 2. File Formatting & Alignment
- Insert new keys right after the anchor key of the same feature block.
- **No indentation**: `$BL[...]` starts at column 1.
- **Column alignment**: Pad with spaces so the `=` sign sits at **column 35** across all files:
  ```php
  $BL['be_my_new_key']              = 'Translated text';
  ```
- Always use single quotes for PHP string values.

## 3. Typography & Language Conventions
Before and after inserting translations, **grep the target `lang.inc.php` file** to match its existing punctuation, entity encoding, and vocabulary conventions:

- **Apostrophes / Single Quotes**:
  - In single-quoted PHP lang files, use the typographic apostrophe `’` (U+2019) instead of escaped `\'` for languages like French, Catalan, Italian, and English (e.g., `$BL['key'] = 'galerie d’images';`).
  - **Ukrainian (`ua`)**: Uses U+02BC `ʼ` (modifier letter apostrophe), not ASCII `'` or U+2019.
- **Double Quotes inside Strings**:
  - When wrapping a word in double quotes inside a translation string, use language-appropriate typographic double quotes (e.g., German `„Anmerkung“`, French `« … »`, English `“…”`).
  - Keep standard ASCII double quotes `"` strictly for HTML tag attributes (e.g., `$BL['key'] = 'Meine <div title="Anmerkung">„Anmerkung“</div> hier.';`).
- **German (`de`, `de-at`, `de-ch`)**:
  - Check neighbouring keys in the same block: historical keys often entity-encode umlauts (`&auml;`, `&ouml;`, `&uuml;`, `&szlig;`) while newer UTF-8 blocks use literal characters. Match the surrounding block convention.
- **Language-Specific Punctuation & Vocabulary**:
  - **Spanish (`es`) & Galician (`gl`)**: Include opening inverted marks (`¡`, `¿`).
  - **French (`fr`)**: Keep non-breaking/thin space before `!`, `?`, `:`, `;` per existing file style.
  - **Japanese (`ja`) & Chinese (`zh-cn`)**: Use full-width punctuation (`！`, `。`, `、`, `：`).
  - **Hindi (`hi`), Bengali (`bn`), Punjabi (`pa`)**: Use danda `।` for sentence termination where existing keys do.
  - **Estonian (`et`)**: Verify existing terminology in the file (e.g., `grupp`/`grupi`, not `rühm`).

## 4. Verification
1. Lint every modified language file with the branch's PHP command (`php84` on `v2-dev`):
   ```bash
   for f in include/inc_lang/backend/*/lang.inc.php; do
     php84 -l "$f" >/dev/null || echo "FAIL: $f"
   done
   ```
2. Verify each new key appears exactly once per file (`grep -c "\$BL\['key_name'\]" include/inc_lang/backend/*/lang.inc.php`).
