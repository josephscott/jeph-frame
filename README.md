# JEPH/Frame

Just Enough PHP for a bit of a framework.

[![Tests](https://github.com/josephscott/jeph-frame/actions/workflows/tests.yml/badge.svg)](https://github.com/josephscott/jeph-frame/actions/workflows/tests.yml)

A minimal PHP routing framework built on [FastRoute](https://github.com/nikic/FastRoute). Define routes, match HTTP methods, and handle requests with closures, files, or classes.

## Requirements

- PHP >= 8.3
- nikic/fast-route 1.3.0

## Installation

```bash
composer require jeph/frame
```

## Basic Usage

```php
<?php
require __DIR__ . '/vendor/autoload.php';

$frame = new JEPH\Frame();

$frame->get( '/', function() {
    echo 'Hello World!';
} );

$frame->run();
```

## HTTP Methods

Supports GET, POST, PUT, DELETE, PATCH, OPTIONS, and HEAD:

```php
$frame->get( '/resource', $handler );
$frame->post( '/resource', $handler );
$frame->put( '/resource', $handler );
$frame->delete( '/resource', $handler );
$frame->patch( '/resource', $handler );
$frame->options( '/resource', $handler );
$frame->head( '/resource', $handler );
```

Use `any()` to match all methods:

```php
$frame->any( '/api', function() {
    echo 'Handles any HTTP method';
} );
```

## Route Parameters

Named parameters with regex patterns:

```php
$frame->get( '/user/{id:\d+}', function( $vars ) {
    echo "User ID: {$vars['id']}";
} );

$frame->get( '/hello/{name:\w+}', function( $vars ) {
    echo "Hello {$vars['name']}!";
} );
```

Optional parameters:

```php
$frame->get( '/page[/{slug:\w+}]', function( $vars ) {
    $slug = $vars['slug'] ?? 'home';
    echo "Page: $slug";
} );
```

## Handler Types

### Closures

```php
$frame->get( '/hello', function( $vars ) {
    echo 'Hello!';
} );
```

### File Includes

```php
$frame->get( '/dashboard', __DIR__ . '/routes/dashboard.php' );
```

In the included file, route parameters are available via the `$_frame` array:

```php
<?php
$id = $_frame['id'] ?? null;
echo "ID: $id";
```

### Class Handlers

```php
class ApiResource {
    public function get( $vars ) {
        echo "GET request";
    }
    public function post( $vars ) {
        echo "POST request";
    }
}

$frame->get( '/api/resource', [ 'ApiResource' ] );
$frame->post( '/api/resource', [ 'ApiResource' ] );
```

The class method matching the HTTP method is called automatically.

## Route Caching

By default, routes are compiled on every request, which is ideal for development since changes take effect immediately.

For production, enable route caching to avoid recompiling routes on each request:

```php
$frame = new JEPH\Frame();
$frame->set_cache_file( '/tmp/routes.cache' );

$frame->get( '/', function() {
    echo 'Hello World!';
} );

$frame->run();
```

A common pattern is to enable caching based on environment:

```php
$frame = new JEPH\Frame();

if ( getenv( 'APP_ENV' ) === 'production' ) {
    $frame->set_cache_file( '/tmp/routes.cache' );
}

$frame->get( '/', function() {
    echo 'Hello World!';
} );

$frame->run();
```

When routes change in production, delete the cache file to regenerate it on the next request.

## Behavior

- **Trailing slashes**: Redirects `/path/` to `/path` with a 302 response
- **Query strings**: Stripped during route matching, still accessible via `$_GET`
- **URL encoding**: Parameters are automatically decoded
- **404 Not Found**: Returned when no route matches
- **405 Method Not Allowed**: Returned when the path matches but the method does not