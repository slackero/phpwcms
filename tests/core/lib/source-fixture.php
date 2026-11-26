<?php
// tests/core/lib/source-fixture.php — shared helpers for standalone source tests.
// Each test sets `$failures = 0;` before requiring this file and ends with
// `if ($failures) { ... exit(1); }`.

/**
 * Record and print one check result.
 */
function check(bool $ok, string $message): void
{
    global $failures;
    if ($ok) {
        echo "OK   $message\n";
        return;
    }
    echo "FAIL $message\n";
    $failures++;
}

/**
 * Extract one named function from source text and define it.
 *
 * Braces are counted instead of matching the first `^}` so functions nested
 * in a `function_exists()` guard can be extracted too.
 */
function extract_fn(string $source, string $name): void
{
    if (!preg_match('/^[ \t]*function ' . preg_quote($name, '/') . '\b/m', $source, $match, PREG_OFFSET_CAPTURE)) {
        echo "FAIL cannot extract $name\n";
        exit(1);
    }

    $start = $match[0][1];
    $length = strlen($source);
    $depth = 0;
    $string = null;
    $end = null;

    for ($i = $start; $i < $length; $i++) {
        $char = $source[$i];
        if ($string !== null) {
            if ($char === '\\') {
                $i++;
            } elseif ($char === $string) {
                $string = null;
            }
            continue;
        }
        if ($char === '"' || $char === "'") {
            $string = $char;
            continue;
        }
        if ($char === '{') {
            $depth++;
            continue;
        }
        if ($char === '}') {
            $depth--;
            if ($depth === 0) {
                $end = $i + 1;
                break;
            }
        }
    }

    if ($end === null) {
        echo "FAIL cannot extract $name\n";
        exit(1);
    }

    eval(substr($source, $start, $end - $start));
}

/**
 * Create a throwaway directory below tests/ that removes itself on shutdown.
 */
function temp_root(string $prefix): string
{
    $root = dirname(__DIR__, 2) . '/.tmp-' . $prefix . '-' . getmypid();
    @mkdir($root, 0755, true);

    register_shutdown_function(static function () use ($root): void {
        if (!is_dir($root)) {
            return;
        }
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($iterator as $item) {
            $item->isDir() ? @rmdir($item->getPathname()) : @unlink($item->getPathname());
        }
        @rmdir($root);
    });

    return $root;
}
