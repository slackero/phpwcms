# Vendor patches

Patches applied on top of packages in `include/vendor/`. Re-apply after
`composer install`/`composer update` replaces the patched files.

## universalfeedcreator-rss-enclosure.patch

- Upstream: `flack/UniversalFeedCreator` commits `c656647` and merge `98bbc60`
- Status: fixed on `master`, not in a release (latest tag is `v1.9.0`, 2024-02-22)
- Effect: renders `<enclosure>` for RSS 2.0 items; previously only Atom 1.0 and JSON Feed did
- Remove once a release above `v1.9.0` is installed

Apply:

```bash
patch -p1 -d include/vendor/openpsa/universalfeedcreator \
    < build/patches/universalfeedcreator-rss-enclosure.patch
```

Dry run first: add `--dry-run`.

## htmlpurifier-4.19.1-fixes.patch

- Upstream: `ezyang/htmlpurifier`, unreleased fixes after `v4.19.1`
- Commits: `fadfa8a4` (E_USER_ERROR deprecated in PHP 8.4), `f3494776`
  (spaced composite aspect ratios), `a09c01df` (reject data URI when
  `tempnam()` fails), `710c8dcd` (image URL case in `list-style`),
  `08bdd2b6` (slash in `border-radius`)
- Excluded: `e1272475` (`sms:` scheme feature), `4a7c7b82` (CI, tests and
  `composer.json` only)
- Effect: no `E_USER_ERROR` deprecation on PHP 8.4+, `aspect-ratio: 4 / 3`
  and `border-radius: 10px / 20px` survive purification, `data:` URIs are
  rejected when no temp file can be created
- Remove once a release above `v4.19.1` is installed

Apply:

```bash
patch -p1 -d include/vendor/ezyang/htmlpurifier \
    < build/patches/htmlpurifier-4.19.1-fixes.patch
```

