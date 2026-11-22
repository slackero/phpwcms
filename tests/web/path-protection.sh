#!/usr/bin/env bash
# Verify that private paths are unreachable through Apache and nginx, and that
# the signed-link form controller still runs.
# Requires Docker. Usage: tests/web/path-protection.sh
set -euo pipefail

cd "$(dirname "$0")/../.."

APACHE_URL="${APACHE_URL:-http://localhost:8080}"
NGINX_URL="${NGINX_URL:-http://localhost:8082}"
FIXTURES=(
    content/tmp/probe.txt
    content/cache/probe.txt
    content/log/probe.txt
    content/form/probe.txt
    content/images/probe.txt
    include/config/probe.txt
    include/vendor/probe.txt
    include/inc_lib/probe.php
    include/inc_css/probe.css
    include/inc_ext/ckeditor/probe.txt
    include/inc_act/probe.php
    tests/probe.txt
    upload/probe.txt
    filearchive/probe.txt
    template/probe.txt
    img/probe.txt
    .hidden/probe.txt
)
# 404 expected
DENY=(
    content/tmp/probe.txt
    content/cache/probe.txt
    content/log/probe.txt
    content/form/probe.txt
    include/config/probe.txt
    include/vendor/probe.txt
    include/inc_lib/probe.php
    include/inc_act/probe.php
    tests/probe.txt
    upload/probe.txt
    filearchive/probe.txt
    .hidden/probe.txt
    CHANGELOG.md
    Makefile
)
# 200 expected
ALLOW=(
    content/images/probe.txt
    include/inc_css/probe.css
    include/inc_ext/ckeditor/probe.txt
    template/probe.txt
    img/probe.txt
)

CREATED=()
cleanup() {
    for f in "${CREATED[@]}"; do
        rm -rf "$f"
    done
    rmdir .hidden 2>/dev/null || true
}
trap cleanup EXIT

# Never overwrite files that belong to the repository (Makefile,
# CHANGELOG.md); they are probed with their real content.
for f in "${FIXTURES[@]}"; do
    if [ ! -e "$f" ]; then
        mkdir -p "$(dirname "$f")"
        printf 'probe\n' > "$f"
        CREATED+=("$f")
    fi
done

docker compose up -d --build
echo "Waiting for containers..."
for _ in $(seq 1 60); do
    if curl -s -o /dev/null "$APACHE_URL/" && curl -s -o /dev/null "$NGINX_URL/"; then
        break
    fi
    sleep 2
done

failures=0
check() { # base url path expected; "deny" accepts 403 or 404
    local base="$1" path="$2" expected="$3" code
    code=$(curl -s -o /dev/null -w '%{http_code}' --path-as-is "$base/$path")
    if { [ "$expected" = "deny" ] && { [ "$code" = "403" ] || [ "$code" = "404" ]; }; } || [ "$code" = "$expected" ]; then
        printf 'OK   %-40s %s %s\n' "$path" "$base" "$code"
    else
        printf 'FAIL %-40s %s expected %s got %s\n' "$path" "$base" "$expected" "$code"
        failures=$((failures + 1))
    fi
}

for base in "$APACHE_URL" "$NGINX_URL"; do
    for path in "${DENY[@]}"; do
        check "$base" "$path" deny
    done
    for path in "${ALLOW[@]}"; do
        check "$base" "$path" 200
    done

    # The document root must not be answered with 404 by the deny rule.
    code=$(curl -s -o /dev/null -w '%{http_code}' "$base/")
    if [ "$code" = "404" ]; then
        echo "FAIL / returned 404 on $base"
        failures=$((failures + 1))
    else
        printf 'OK   %-40s %s %s\n' "/" "$base" "$code"
    fi

    # formfile.php must reach PHP: an invalid token is answered by the
    # controller itself ("File not found."), not by the server config.
    body=$(curl -s "$base/formfile.php?t=invalid")
    if [ "$body" = "File not found." ]; then
        printf 'OK   %-40s %s controller runs\n' "formfile.php" "$base"
    else
        printf 'FAIL %-40s %s controller blocked or crashed\n' "formfile.php" "$base"
        failures=$((failures + 1))
    fi
done

docker compose down -v
exit $((failures > 0 ? 1 : 0))
