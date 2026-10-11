<?php
// Link checker for docs/*.md — verifies relative .md/.md#anchor links resolve to files.
$docsDir = realpath(__DIR__ . '/..');
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($docsDir, FilesystemIterator::SKIP_DOTS));
$files = [];
foreach ($rii as $file) {
    if ($file->isFile() && $file->getExtension() === 'md' && !str_contains($file->getPathname(), '/_authoring/')) {
        $files[] = str_replace('\\', '/', $file->getPathname());
    }
}
sort($files);
$errors = 0;
foreach ($files as $file) {
    $relDir = dirname($file);
    $content = file_get_contents($file);
    if (preg_match_all('/\[[^\]]*\]\(((?!\w+:\/\/)[^)]+)\)/', $content, $m)) {
        foreach ($m[1] as $link) {
            $path = trim(str_replace(['<', '>'], '', $link));
            if ($path === '' ) {
                continue;
            }
            if (str_starts_with($path, '#')) {
                continue;
            }
            [$target, $anchor] = array_pad(explode('#', $path, 2), 2, null);
            $clean = html_entity_decode($target, ENT_QUOTES);
            $resolved = realpath($relDir . '/' . $clean);
            $ok = $resolved !== false && is_file($resolved);
            if (!$ok) {
                echo "BROKEN: " . basename($file) . ' -> ' . $link . "\n";
                $errors++;
            }
        }
    }
}
echo $errors ? "FAIL: $errors broken links\n" : "OK: all .md links resolve (" . count($files) . " files)\n";