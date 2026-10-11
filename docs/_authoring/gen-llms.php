<?php
// Generates docs/llms.txt (curated agent index) and docs/llms-full.txt (all pages, sidebar order)
// from docs frontmatter. Rerun after any content update: php8 docs/_authoring/gen-llms.php
$root   = dirname(__DIR__);
$docs   = $root;
$sections = [
    ['Introduction', 'index.md'],
    ['General', 'general'],
    ['Requirements', 'requirements'],
    ['Installation & Updates', 'installation'],
    ['Administration', 'administration'],
    ['Backend', 'backend'],
    ['Frontend', 'frontend'],
    ['Frontend — Replacement Tags', 'frontend/replacement-tags'],
    ['Templates', 'templates'],
    ['Content Parts', 'content-parts'],
    ['Modules', 'modules'],
];

function frontmatter(string $file): array {
    $raw = file_get_contents($file);
    if (preg_match('/\A---\s*\n(.*?)\n---\s*\n/s', $raw, $m)) {
        $fm = [];
        foreach (explode("\n", $m[1]) as $line) {
            if (preg_match('/^(\w+):\s*(.*)$/', $line, $mm)) {
                $fm[$mm[1]] = trim($mm[2], '"');
            }
        }
        $fm['_body'] = preg_replace('/\A---\s*\n.*?\n---\s*\n/s', '', $raw, 1);
        return $fm;
    }
    return ['_body' => $raw];
}

$pages = [];      // [section, path, fm]
foreach ($sections as [$label, $entry]) {
    if (is_file($docs . '/' . $entry)) {
        $pages[] = [$label, $entry, frontmatter($docs . '/' . $entry)];
        if (pathinfo($entry, PATHINFO_EXTENSION) === 'md') {
            continue;
        }
    }
    if (!is_dir($docs . '/' . $entry)) {
        continue;
    }
    $items = [];
    foreach (glob($docs . '/' . $entry . '/*.md') as $f) {
        if (str_contains($f, '/_authoring/')) {
            continue;
        }
        $items[] = [$entry . '/' . basename($f), frontmatter($f)];
    }
    // directory index page first, then sidebar_position, then filename
    foreach ($items as $k => $v) {
            if (basename($v[0]) !== 'index.md') {
                continue;
            }
            $pages[] = [$label, $v[0], $v[1]];
            unset($items[$k]);
            $items = array_values($items);
            break;
        }
    usort($items, fn($a, $b) => [
        (int)($a[1]['sidebar_position'] ?? 999),
        $a[0],
    ] <=> [
        (int)($b[1]['sidebar_position'] ?? 999),
        $b[0],
    ]);
    foreach ($items as $item) {
        $pages[] = [$label, $item[0], $item[1]];
    }
}

// llms.txt
$out  = "# phpwcms 2.x Documentation\n\n";
$out .= "> Reference documentation for phpwcms, the open source PHP 8.2+/MySQL content management system: structure levels, articles, content parts, templates, replacement tags, backend administration and modules.\n\n";
$cur = null;
foreach ($pages as [$label, $path, $fm]) {
    if ($label !== $cur) {
        $out .= "\n## $label\n\n";
        $cur = $label;
    }
    $title = $fm['title'] ?? basename($path);
    $desc  = $fm['description'] ?? '';
    $out .= "- [{$title}](docs/{$path}): {$desc}\n";
}
file_put_contents($docs . '/llms.txt', rtrim($out) . "\n");

// llms-full.txt
$full = "# phpwcms 2.x Documentation — full content\n\n";
foreach ($pages as [$label, $path, $fm]) {
    $body = trim($fm['_body'] ?? '');
    $full .= "<!-- docs/{$path} -->\n\n{$body}\n\n---\n\n";
}
file_put_contents($docs . '/llms-full.txt', rtrim($full) . "\n");

echo 'llms.txt: ' . count($pages) . " pages indexed\n";