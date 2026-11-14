#!/usr/bin/env bash
#
# Create an archive of files changed between a tag/commit and HEAD.
#
# Works without `make` (macOS without Xcode Command Line Tools) and without
# GNU make features: only git, mktemp, tar are required.
#
# File contents are taken from HEAD, never from the working tree.
# Deleted files are skipped because there is no blob for them at HEAD.
#
# Usage:
#   ./scripts/changed-archive.sh <tag-or-commit> [tar.gz|zip]
#
# Examples:
#   ./scripts/changed-archive.sh v2.0.0-beta.3
#   ./scripts/changed-archive.sh v2.0.0-beta.3 zip

set -euo pipefail

from=${1:-}
format=${2:-tar.gz}

if [ -z "$from" ]; then
    echo "Usage: $0 <tag-or-commit> [tar.gz|zip]" >&2
    exit 1
fi

case "$format" in
    tar.gz|zip) ;;
    *) echo "Unsupported format: $format (use tar.gz or zip)" >&2; exit 1 ;;
esac

repo_root=$(git rev-parse --show-toplevel)
cd "$repo_root"

if ! git rev-parse --verify --quiet "$from^{commit}" >/dev/null; then
    echo "Unknown revision: $from" >&2
    exit 1
fi

out="phpwcms-${from//\//-}-to-HEAD.$format"

tmp_dir=$(mktemp -d)
list_file=$(mktemp)
trap 'rm -rf "$tmp_dir" "$list_file"' EXIT

# NUL separated paths keep spaces intact
git diff -z --name-only --diff-filter=ACMR "$from" HEAD > "$list_file"

if [ ! -s "$list_file" ]; then
    echo "No changed files between $from and HEAD" >&2
    exit 1
fi

# materialise HEAD content of the changed files outside the working tree
git --work-tree="$tmp_dir" checkout-index -f -z --stdin < "$list_file"

if [ "$format" = "zip" ] && command -v zip >/dev/null 2>&1; then
    (cd "$tmp_dir" && tr '\0' '\n' < "$list_file" | zip -q "$repo_root/$out" -@)
else
    # bsdtar (macOS default) picks the format from the -f extension via -a
    tar --null -T "$list_file" -C "$tmp_dir" -a -cf "$out"
fi

count=$(tr -cd '\0' < "$list_file" | wc -c | tr -d ' ')
echo "Created $out ($count files)"
