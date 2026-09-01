#!/bin/bash
# build/make-release.sh — build a self-update release zip with .update-manifest
#
# Produces release/phpwcms-v<version>.zip whose entries are DOCROOT-ROOTED
# (paths start at include/, phpwcms.php, ...) so the update engine's verifyZip
# can read include/inc_lib/revision/revision.php and .update-manifest from the
# zip root. The manifest lists one 'sha256  path' line per dist file (two
# spaces), matching the engine's parseManifest().
set -euo pipefail

VERSION="${1:?Usage: make-release.sh <version>  (e.g. 2.0.1)}"
TAG="v${VERSION}"
ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
STAGE="$(mktemp -d)/phpwcms"
mkdir -p "$STAGE"

# Copy the docroot into a clean stage, excluding everything the update engine
# must never touch or ship (config secrets, user content, VCS, dev tooling).
rsync -a --exclude '.git*' --exclude 'include/config/config.inc.php' \
  --exclude 'filearchive/' --exclude 'content/' --exclude 'upload/' \
  --exclude '.htaccess' --exclude 'robots.txt' --exclude 'doc/' --exclude 'tests/' \
  --exclude 'build/' --exclude 'release/' --exclude 'node_modules/' \
  --exclude '.DS_Store' --exclude '.phpstan/cache' --exclude '.claude/' --exclude '.codex/' \
  "$ROOT/" "$STAGE/"

# Generate the manifest INSIDE the stage, docroot-relative paths, excluding
# itself from the listing. It is still included in the zip (zip . below) so the
# engine's verifyZip can read it.
( cd "$STAGE" && find . -type f ! -name '.update-manifest' | sed 's|^\./||' | sort \
  | while read -r f; do echo "$(shasum -a 256 "$f" | cut -d' ' -f1)  $f"; done > .update-manifest )

# Zip from INSIDE the stage so entries are docroot-rooted (no phpwcms/ top dir).
mkdir -p "$ROOT/release"
( cd "$STAGE" && zip -qr "$ROOT/release/phpwcms-${TAG}.zip" . )
echo "Built release/phpwcms-${TAG}.zip — attach as release asset for ${TAG}"
