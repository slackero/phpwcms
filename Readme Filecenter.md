# Dateizentrale / File Center (Entwicklerhinweise / Developer Notes)

## Deutsch

Dieses Dokument beschreibt die Funktionen, um die die Dateizentrale
(Admin -> Dateizentrale, `phpwcms.php?do=files`) erweitert wurde, sowie die dafür geänderten
und neu hinzugefügten Dateien. Reine Bugfixes aus der Entwicklungsphase sind hier nicht
aufgeführt.

### 1. Drag & Drop Datei-Verschieben

Dateien lassen sich per Drag & Drop verschieben:

- Ziehen startet nur über einen dedizierten Anfasser (Grip-Icon) links in der Zeile — dasselbe
  visuelle Muster wie beim Sortieren der Inhaltsabschnitte (`.handle` + `fa-grip-vertical`, vgl.
  `articlecontent.list.tmpl.php`).
- Abgelegt werden kann auf der Ordner-Zeile selbst, mitten im Bereich der bereits angezeigten
  Dateien eines (aufgeklappten) Ordners, auf einer Root-Drop-Zone ("Dateiablage (ROOT)") sowie
  direkt in der Root-Dateiliste.
- Es wird **keine** exakte Sortierposition beim Ablegen berücksichtigt — die Datei landet im
  Zielordner, die Reihenfolge dort richtet sich weiterhin nach dem bestehenden
  Sortierfeld/Namen.
- Kein SortableJS: Das Verschieben einer Datei in einen anderen Ordner ist ein Reparenting in
  einem Baum (Datei auf eine Ordner-Zeile fallenlassen), kein Umsortieren einer flachen Liste.
  Natives HTML5 Drag & Drop kommt ohne Umbau der rekursiv gerenderten Baumstruktur aus.
- `include/inc_act/act_file.php`: die vorhandene `paste`-Aktion (setzt `f_pid` der Datei) liefert
  bei AJAX-Aufrufen (`X-Requested-With: XMLHttpRequest`) eine kleine JSON-Antwort statt immer zu
  redirecten.

### 2. Ampel-Status für Dateiverwendung (inkl. verlinktem Rot-Status)

Jede Datei zeigt einen farbigen Status-Punkt neben dem Namen, plus eine Legende über der Liste:

- **Rot** = aktuell in Artikel-Inhalten referenziert, kann nicht in den Papierkorb verschoben
  werden; **Gelb** = neu hochgeladen, jünger als 24h, noch nie verwendet; **Grün** = unbenutzt und
  24h oder älter; **Schwarz** = war früher in Verwendung, aktuell nicht mehr.
- Erkennung läuft über Artikel-Inhaltsabschnitte (Bildfeld, Dateiliste-Feld, Richtext/HTML-Body,
  Medien, Formularfelder), abgeglichen über Datei-ID und den eindeutigen Datei-Hash. Bewusst
  **nicht** abgedeckt: Shop-, Ads-, Glossar- und Mailvorlagen-Module sowie hart in Templates
  kodierte Dateipfade.
- Neue Spalte `f_used` in `phpwcms_file`, die markiert, dass eine Datei mindestens einmal
  verwendet wurde — die Historie zählt erst ab dem Zeitpunkt, an dem dieses Feature live geht.
  Die Spalte wird beim ersten Aufruf jeder Dateizentrale-Nutzungsfunktion automatisch angelegt
  (siehe Abschnitt 5) — unabhängig von der Revisionsnummerierung.
- Damit eine Datei auch dann zuverlässig als "schon einmal verwendet" erkannt wird, wenn
  zwischenzeitlich niemand die Dateizentrale geöffnet hat, markiert
  `phpwcms_mark_content_files_used($acontent_id)` in `include/inc_lib/files.private-usage.inc.php`
  referenzierte Dateien sofort mit `f_used=1`, direkt beim Speichern eines Inhaltsabschnitts
  (aufgerufen aus `include/inc_lib/article.editcontent.inc.php`, sowohl beim Neuanlegen als auch
  beim Aktualisieren).
- Backend-seitige Absicherung: `act_file.php` verweigert das Verschieben einer aktuell
  verwendeten Datei in den Papierkorb zusätzlich zum bereits deaktivierten Button im UI.
- Der Rot-Status verlinkt direkt zu jedem referenzierenden Inhaltsabschnitt
  (`phpwcms.php?do=articles&p=2&s=1&aktion=2&id=...&acid=...`), jeweils mit der
  Inhaltsabschnitts-ID beschriftet (`[ID: acontent_id]`). Da ein normaler Hover-Tooltip keinen
  zuverlässigen Klick auf einen enthaltenen Link erlaubt, wird dafür ein fokussierbarer Button mit
  Bootstrap-Popover verwendet (klick-/fokus-ausgelöst, schließt bei Blur); die übrigen Status
  behalten den leichtgewichtigen Hover-Tooltip. Die Popover-Initialisierung ist eigenständig in
  `files.private.additions.inc.php` und rührt nicht die geteilte, minifizierte
  `phpwcms.js`/`phpwcms.min.js` an.
- Neue Datei `include/inc_lib/files.private-usage.inc.php`: enthält
  `phpwcms_get_content_file_usage()`, `phpwcms_file_in_use()`, `phpwcms_get_file_usage_locations()`,
  `phpwcms_get_file_traffic_light()`, `phpwcms_render_file_traffic_light()`,
  `phpwcms_render_file_usage_legend()` und `phpwcms_mark_content_files_used()`.

### 3. Mehrfachauswahl + Sammel-Verschieben/-Löschen

- Checkbox pro Zeile, teilt sich die Zelle mit dem Anfasser (keine zusätzliche Spalte).
- Sammel-Aktionsleiste über der Liste, sobald mindestens eine Datei ausgewählt ist ("In
  Papierkorb legen" / "Auswahl aufheben").
- Ziehen einer markierten Datei, die Teil einer Mehrfachauswahl ist, verschiebt die gesamte
  Auswahl; eine nicht markierte Datei wird weiterhin einzeln verschoben.
- `act_file.php`s `paste`- und `trash`-Aktionen akzeptieren jetzt zusätzlich zu einer einzelnen
  ID eine Doppelpunkt-getrennte Liste von IDs, vollständig abwärtskompatibel zu allen
  bestehenden Einzeldatei-Links.
- Aktuell in Verwendung stehende (rote) Dateien werden beim Sammel-Löschen automatisch
  übersprungen, mit einer Meldung, wie viele übersprungen wurden.

### 4. Suche & Sammel-Löschen nicht mehr genutzter Dateien (Dateiaktionen)

Neues Werkzeug "Nicht mehr genutzte Dateien" auf der Dateiaktionen-Seite
(**Admin -> Dateizentrale -> Dateiaktionen**, `phpwcms.php?do=files&p=4`, unterhalb der Karte
"Dateiaktionen"):

- Durchsucht alle Dateien nach Schwarz-Status (früher in Artikel-Inhalten verwendet, aktuell
  nirgends mehr eingebunden) und listet alle Treffer mit Checkbox pro Datei plus
  "Alle auswählen"-Umschalter, damit unbemerkt aus der Nutzung gefallene Dateien gefunden werden
  können, ohne beim Durchblättern jeden Ordner einzeln auf den schwarzen Punkt zu prüfen.
- Neue Funktion `phpwcms_get_unused_files()` in `include/inc_lib/files.private-usage.inc.php`
  führt die Suche aus, mit derselben Sichtbarkeits-Einschränkung wie die normale Dateiliste
  (Nicht-Admins sehen nur eigene Dateien). Sie nutzt `phpwcms_get_file_traffic_light()` je
  Kandidat, das wiederum den pro Request gecachten Scan aus
  `phpwcms_get_content_file_usage()` wiederverwendet — dadurch bleibt die Prüfung eine einzige
  Content-Abfrage, unabhängig von der Anzahl der Kandidaten-Dateien.
- Ausgewählte Dateien werden per Klick über dieselbe Sammel-`trash`-Aktion in `act_file.php` in
  den Papierkorb verschoben, die auch die Mehrfachauswahl in der Dateizentrale selbst nutzt
  (Abschnitt 3), inklusive Bestätigungsdialog und Verwendungs-Sicherung.
- Implementiert in `include/inc_tmpl/files.actions.tmpl.php`; neue Sprachschlüssel
  `file_actions_unused_title/intro/search/none/back` (de+en).

### 5. Datenbank-Migration von der Revisionsnummerierung entkoppelt

Die `f_used`-Spalte für den Ampel-Status wird bewusst **nicht** über das nummerierte
Revisions-/Build-System (`include/inc_lib/revision/rNNN.php` + `PHPWCMS_REVISION`) eingespielt,
mit dem der Entwickler seine eigenen, offiziellen Releases kennzeichnet — eine eigene Migration
an einer festen `rNNN`-Stelle würde das Risiko einer Kollision mit einer später vom Entwickler
vergebenen Revisionsnummer bergen.

- Stattdessen übernimmt eine eigenständige Funktion `phpwcms_filecenter_ensure_schema()` in
  `include/inc_lib/files.private-usage.inc.php` die Migration: Sie prüft und legt die Spalte bei
  Bedarf selbst an (`_dbColumnExists()`/`ALTER TABLE`), merkt sich das Ergebnis aber über ein
  eigenes `sysvalue`-Flag (`filecenter_schema_f_used`, Gruppe `sys_filecenter`) — komplett
  unabhängig von `PHPWCMS_REVISION` und der `rNNN.php`-Namenskonvention.
- Aufgerufen als erste Anweisung in den drei Funktionen, die `f_used` lesen oder schreiben:
  `phpwcms_mark_content_files_used()`, `phpwcms_get_file_traffic_light()` und
  `phpwcms_get_unused_files()`.
- `setup/default_sql/phpwcms_init.sql` definiert die Spalte zusätzlich direkt, damit eine
  Neuinstallation sie von Anfang an hat.

### 6. Bereits vorhandene, aktuell ungenutzte Dateien als Dateileichen markiert

Damit eine Datei, die schon vor der Installation dieser Erweiterung im System lag, nicht optisch
wie ein brandneuer, unbenutzter Upload erscheint, prüft und markiert die Erweiterung beim
Einspielen jede bereits vorhandene Datei sofort mit dem passenden Status:

- Neue Funktion `phpwcms_filecenter_backfill_used_flag()` in
  `include/inc_lib/files.private-usage.inc.php`. Läuft einmalig, direkt aus
  `phpwcms_filecenter_ensure_schema()` (Abschnitt 5) heraus aufgerufen, unabhängig davon, ob die
  Spalte dabei gerade neu angelegt wird oder schon vorher existierte (eigenes `sysvalue`-Flag
  `filecenter_backfill_f_used`, Gruppe `sys_filecenter`, getrennt vom Spalten-Flag
  `filecenter_schema_f_used`).
- Markiert **jede** zum Zeitpunkt des Deployments bereits vorhandene Datei mit `f_used=1` —
  unabhängig davon, ob sie gerade aktuell verwendet wird oder nicht (ein einziges
  `UPDATE ... WHERE f_used=0 AND f_kid=1 AND f_trash=0`, keine Inhaltsabschnitts-Analyse nötig).
- Auswirkung auf die Ampel: Aktuell verwendete Dateien werden rot markiert (Verwendung hat immer
  Vorrang vor `f_used`). Jede andere, zum Deployment-Zeitpunkt bereits vorhandene Datei springt
  sofort auf **Schwarz** statt Grün/Gelb — sie wird also als Dateileiche behandelt, nicht als
  frischer, unbenutzter Upload. Neu hochgeladene Dateien nach dem Deployment sind davon nicht
  betroffen und durchlaufen den normalen Gelb->Grün/Rot/Schwarz-Lebenszyklus wie gehabt.
- **Bewusste Vereinfachung:** Es wird nicht versucht, die tatsächliche Nutzungshistorie zu
  rekonstruieren (unmöglich, da vergangene Inhaltsabschnitts-Änderungen keine Spuren hinterlassen)
  — stattdessen wird pauschal angenommen, dass eine zum Deployment-Zeitpunkt bereits vorhandene,
  aktuell ungenutzte Datei eine Karteileiche ist.

### Geänderte/neue Dateien in diesem Paket

- `include/inc_act/act_file.php` (Drag & Drop AJAX-Antwort, Mehrfachauswahl-IDs, Verwendungs-Guard)
- `include/inc_lib/files.private-usage.inc.php` (Ampel-Status-Logik, Nutzungsorte, Suche nach ungenutzten Dateien, `phpwcms_mark_content_files_used()`, `phpwcms_filecenter_ensure_schema()` siehe Abschnitt 5, `phpwcms_filecenter_backfill_used_flag()` siehe Abschnitt 6)
- `include/inc_lib/files.private-functions.inc.php` (Ordner-Ansicht: Anfasser, Checkbox, Ampel)
- `include/inc_lib/files.private-filelist.inc.php` (Root-Ansicht: Anfasser, Checkbox, Ampel)
- `include/inc_lib/files.private.additions.inc.php` (Drop-Zonen, Sammel-Leiste, Popover-Init, gesamtes CSS/JS)
- `include/inc_tmpl/files.actions.tmpl.php` (Werkzeug "Nicht mehr genutzte Dateien" auf der Dateiaktionen-Seite, unterhalb der Dateiaktionen-Karte)
- `include/inc_lib/article.editcontent.inc.php` (ruft `phpwcms_mark_content_files_used()` nach dem Speichern eines Inhaltsabschnitts auf, siehe Abschnitt 2)
- `include/inc_lang/backend/de/lang.inc.php` (neue/angepasste Sprachschlüssel, siehe oben)
- `include/inc_lang/backend/en/lang.inc.php` (dieselben Schlüssel, englisch)
- `setup/default_sql/phpwcms_init.sql` (definiert die Spalte `f_used` direkt, damit eine Neuinstallation die Spalte von Anfang an hat)
- `CHANGELOG.md`

Alle PHP-Dateien wurden mit `php -l` auf Syntaxfehler geprüft.

## English

This document describes the functions the File Center
(Admin -> File Center, `phpwcms.php?do=files`) was extended with, along with the files changed
and newly added for them. Plain bugfixes from the development phase are not listed here.

### 1. Drag & Drop File Moving

Files can be moved via drag & drop:

- Dragging only starts from a dedicated handle (grip icon) on the left of the row — the same
  visual pattern used for sorting content sections (`.handle` + `fa-grip-vertical`, cf.
  `articlecontent.list.tmpl.php`).
- A file can be dropped onto the folder row itself, anywhere within an (expanded) folder's
  already-displayed files, onto a root-level drop zone ("File Drop (ROOT)"), or directly into the
  root file list.
- No exact drop position within the target is taken into account — the file lands in the target
  folder, and its order there still follows the existing sort field/name.
- No SortableJS: moving a file into another folder is reparenting within a tree (dropping a file
  onto a folder row), not reordering a flat list. Native HTML5 drag & drop is enough without
  reworking the recursively rendered tree structure.
- `include/inc_act/act_file.php`: the existing `paste` action (which sets a file's `f_pid`) now
  returns a small JSON response for AJAX calls (`X-Requested-With: XMLHttpRequest`) instead of
  always redirecting.

### 2. File Usage Traffic-Light Status (Including Linked Red Status)

Each file shows a colored status dot next to its name, plus a legend above the list:

- **Red** = currently referenced in article content, cannot be moved to the trash; **Yellow** =
  newly uploaded, less than 24h old, never used; **Green** = unused and 24h or older; **Black** =
  was previously in use, not anymore.
- Detection runs across article content sections (image field, file-list field, richtext/HTML
  body, media, form fields), matched by file ID and the file's unique hash. Deliberately **not**
  covered: the shop, ads, glossary and mail-template modules, as well as file paths hardcoded
  directly into templates.
- New `f_used` column on `phpwcms_file`, which flags that a file has been used at least once —
  history only starts counting from the point this feature goes live. The column is created
  automatically the first time any File Center usage function runs (see section 5) — independent
  of the revision numbering.
- So that a file is reliably recognized as "already used" even if nobody has opened the File
  Center in the meantime, `phpwcms_mark_content_files_used($acontent_id)` in
  `include/inc_lib/files.private-usage.inc.php` immediately flags referenced files with
  `f_used=1` right when a content section is saved (called from
  `include/inc_lib/article.editcontent.inc.php`, both on create and on update).
- Backend-side safeguard: `act_file.php` now also refuses to move a currently-used file into the
  trash, in addition to the button already being disabled in the UI.
- The red status links directly to every content section referencing the file
  (`phpwcms.php?do=articles&p=2&s=1&aktion=2&id=...&acid=...`), each labeled with its content
  section ID (`[ID: acontent_id]`). Since a normal hover tooltip doesn't reliably allow clicking a
  link inside it, a focusable button with a Bootstrap popover is used instead (triggered by
  click/focus, closes on blur); the other statuses keep the lightweight hover tooltip. The popover
  initialization is self-contained in `files.private.additions.inc.php` and does not touch the
  shared, minified `phpwcms.js`/`phpwcms.min.js`.
- New file `include/inc_lib/files.private-usage.inc.php`: contains
  `phpwcms_get_content_file_usage()`, `phpwcms_file_in_use()`, `phpwcms_get_file_usage_locations()`,
  `phpwcms_get_file_traffic_light()`, `phpwcms_render_file_traffic_light()`,
  `phpwcms_render_file_usage_legend()` and `phpwcms_mark_content_files_used()`.

### 3. Multi-Select + Bulk Move/Trash

- A checkbox per row, sharing the cell with the handle (no extra column).
- A bulk action bar appears above the list once at least one file is selected ("Move to trash" /
  "Clear selection").
- Dragging a checked file that is part of a multi-selection moves the whole selection; an
  unchecked file is still moved individually.
- `act_file.php`'s `paste` and `trash` actions now accept a colon-separated list of ids in
  addition to a single id, fully backward compatible with all existing single-file links.
- Files currently in use (red) are automatically skipped during bulk trashing, with a message
  reporting how many were skipped.

### 4. Search & Bulk-Trash Unused Files (File Actions)

New "Unused Files" tool on the File Actions page (**Admin -> File Center -> File Actions**,
`phpwcms.php?do=files&p=4`, below the "File Actions" card):

- Searches all files for black status (previously used in article content, no longer referenced
  anywhere) and lists every match with a checkbox per file plus a "select all" toggle, so files
  that have quietly fallen out of use can be found without checking every folder individually for
  the black dot.
- New function `phpwcms_get_unused_files()` in `include/inc_lib/files.private-usage.inc.php` runs
  the search, with the same visibility restriction as the regular file list (non-admins only see
  their own files). It uses `phpwcms_get_file_traffic_light()` per candidate, which in turn reuses
  the per-request cached scan from `phpwcms_get_content_file_usage()` — so the check remains a
  single content query regardless of the number of candidate files.
- Selected files are moved to the trash with one click via the same bulk `trash` action in
  `act_file.php` also used by multi-select in the File Center itself (section 3), including its
  confirmation dialog and usage safeguard.
- Implemented in `include/inc_tmpl/files.actions.tmpl.php`; new language keys
  `file_actions_unused_title/intro/search/none/back` (de+en).

### 5. Database Migration Decoupled From Revision Numbering

The `f_used` column for the traffic-light status is deliberately **not** rolled out through the
numbered revision/build system (`include/inc_lib/revision/rNNN.php` + `PHPWCMS_REVISION`), which
is how the developer marks their own official releases — a custom migration pinned to a fixed
`rNNN` slot would risk colliding with a revision number the developer assigns later.

- Instead, a self-contained function `phpwcms_filecenter_ensure_schema()` in
  `include/inc_lib/files.private-usage.inc.php` handles the migration: it checks for and creates
  the column itself if needed (`_dbColumnExists()`/`ALTER TABLE`), but remembers the result via
  its own `sysvalue` flag (`filecenter_schema_f_used`, group `sys_filecenter`) — completely
  independent of `PHPWCMS_REVISION` and the `rNNN.php` naming convention.
- Called as the first statement in the three functions that read or write `f_used`:
  `phpwcms_mark_content_files_used()`, `phpwcms_get_file_traffic_light()` and
  `phpwcms_get_unused_files()`.
- `setup/default_sql/phpwcms_init.sql` also defines the column directly, so a fresh installation
  has it from the start.

### 6. Pre-Existing, Currently-Unused Files Marked As Orphans

So that a file already present in the system before this extension was installed doesn't visually
look like a brand-new, unused upload, the extension checks and marks every pre-existing file with
the correct status right away when deployed:

- New function `phpwcms_filecenter_backfill_used_flag()` in
  `include/inc_lib/files.private-usage.inc.php`. Runs once, called directly from
  `phpwcms_filecenter_ensure_schema()` (section 5), regardless of whether the column is being
  created right now or already existed before (its own `sysvalue` flag,
  `filecenter_backfill_f_used`, group `sys_filecenter`, separate from the column flag
  `filecenter_schema_f_used`).
- Marks **every** file that already exists at deployment time with `f_used=1` — regardless of
  whether it is currently in use or not (a single
  `UPDATE ... WHERE f_used=0 AND f_kid=1 AND f_trash=0`, no content-section analysis needed).
- Effect on the traffic light: currently-used files are still marked red (usage always takes
  priority over `f_used`). Every other file already present at deployment time immediately turns
  **black** instead of green/yellow — it is treated as an orphan rather than a fresh, unused
  upload. Files uploaded after deployment are unaffected and go through the normal
  yellow->green/red/black lifecycle as before.
- **Deliberate simplification:** No attempt is made to reconstruct actual usage history
  (impossible, since past content-section edits leave no trace) — instead it is assumed that a
  file already present at deployment time and currently unused is an orphan.

### Changed/New Files In This Package

- `include/inc_act/act_file.php` (drag & drop AJAX response, multi-select ids, usage guard)
- `include/inc_lib/files.private-usage.inc.php` (traffic-light logic, usage locations, unused-files search, `phpwcms_mark_content_files_used()`, `phpwcms_filecenter_ensure_schema()` see section 5, `phpwcms_filecenter_backfill_used_flag()` see section 6)
- `include/inc_lib/files.private-functions.inc.php` (folder view: handle, checkbox, traffic light)
- `include/inc_lib/files.private-filelist.inc.php` (root view: handle, checkbox, traffic light)
- `include/inc_lib/files.private.additions.inc.php` (drop zones, bulk bar, popover init, all CSS/JS)
- `include/inc_tmpl/files.actions.tmpl.php` ("Unused Files" tool on the File Actions page, below the File Actions card)
- `include/inc_lib/article.editcontent.inc.php` (calls `phpwcms_mark_content_files_used()` after saving a content section, see section 2)
- `include/inc_lang/backend/de/lang.inc.php` (new/adjusted language keys, see above)
- `include/inc_lang/backend/en/lang.inc.php` (same keys, English)
- `setup/default_sql/phpwcms_init.sql` (defines the `f_used` column directly, so a fresh installation has the column from the start)
- `CHANGELOG.md`

All PHP files were checked for syntax errors with `php -l`.
