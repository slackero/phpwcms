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
rsync -a \
  --exclude '.git*' \
  --exclude '.agents/' --exclude '.claude/' --exclude '.codex/' --exclude '.superpowers/' \
  --exclude '.idea/' --exclude '.nova/' --exclude '.mcp.json' --exclude '*.esproj' \
  --exclude '.DS_Store' --exclude '.phpstan/' \
  --exclude 'stacklit.html' --exclude 'stacklit.json' \
  --exclude 'include/config/conf.inc.php' --exclude 'include/config/config.inc.php' \
  --exclude 'setup/setup.conf.inc.php' \
  --exclude 'bin/whitelabel-license.php' --exclude 'bin/examples/' \
  --exclude 'filearchive/' --exclude 'content/' --exclude 'upload/' \
  --exclude '.htaccess' --exclude 'robots.txt' --exclude 'doc/' --exclude 'tests/' \
  --exclude 'build/' --exclude 'release/' --exclude 'node_modules/' \
  "$ROOT/" "$STAGE/"

# Safety check: ensure sensitive files never leak into release
for sensitive in \
  "$STAGE/include/config/conf.inc.php" \
  "$STAGE/include/config/config.inc.php" \
  "$STAGE/bin/whitelabel-license.php"; do
  if [ -e "$sensitive" ]; then
    echo "CRITICAL ERROR: Sensitive file found in stage: $sensitive" >&2
    exit 1
  fi
done

# Seed setup/setup.conf.inc.php from clean dist template for self-installation
cp "$STAGE/include/config/dist.conf.inc.php" "$STAGE/setup/setup.conf.inc.php"

# Verify setup/setup.conf.inc.php strictly matches dist template (no credentials)
if ! cmp -s "$STAGE/include/config/dist.conf.inc.php" "$STAGE/setup/setup.conf.inc.php"; then
  echo "CRITICAL ERROR: setup/setup.conf.inc.php does not match dist.conf.inc.php" >&2
  exit 1
fi

# Generate the manifest INSIDE the stage, docroot-relative paths, excluding
# itself from the listing. It is still included in the zip (zip . below) so the
# engine's verifyZip can read it.
( cd "$STAGE" && find . -type f ! -name '.update-manifest' | sed 's|^\./||' | sort \
  | while read -r f; do echo "$(shasum -a 256 "$f" | cut -d' ' -f1)  $f"; done > .update-manifest )

# Zip from INSIDE the stage so entries are docroot-rooted (no phpwcms/ top dir).
mkdir -p "$ROOT/release"
( cd "$STAGE" && zip -qr "$ROOT/release/phpwcms-${TAG}.zip" . )
echo "Built release/phpwcms-${TAG}.zip — attach as release asset for ${TAG}"
