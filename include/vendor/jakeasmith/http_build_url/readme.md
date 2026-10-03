# http_build_url() for PHP

**This package is deprecated and no longer maintained. Please move off it.**

I wrote this in 2014 to provide `http_build_url()` without the pecl_http
extension. PHP 8.5 now ships a built-in URI API that does the same job, so
there's no reason to keep depending on this. Known bugs in this package, like joining a path
onto a URL with a trailing slash stripping every "a" from it
([#25](https://github.com/jakeasmith/http_build_url/issues/25)), won't be
fixed.

## Replacing it on PHP 8.5+

```php
use Uri\Rfc3986\Uri;

// Replace parts of a URL
echo Uri::parse('https://example.com/search?q=php#top')
    ->withPath('/docs')
    ->withQuery('page=2')
    ->withFragment(null)
    ->toString();
// https://example.com/docs?page=2

// Join a relative path and merge query strings
// (was HTTP_URL_JOIN_PATH | HTTP_URL_JOIN_QUERY)
$base = Uri::parse('https://example.com/v1/users?page=2');
parse_str($base->getQuery() ?? '', $query);
echo $base->resolve('me')
    ->withQuery(http_build_query($query + ['fields' => 'name']))
    ->toString();
// https://example.com/v1/me?page=2&fields=name
```

On older PHP, combine `parse_url()` and `http_build_query()`, or use a URI
library such as [league/uri](https://github.com/thephpleague/uri).

Released versions stay on Packagist, so existing installs keep working.

## License

MIT. See LICENSE.
