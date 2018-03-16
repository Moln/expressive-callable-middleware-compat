Expressive callable middleware compat
======================================

[![Build Status](https://travis-ci.org/moln/expressive-callable-middleware-compat.png)](https://travis-ci.org/moln/expressive-callable-middleware-compat)
[![Coverage Status](https://coveralls.io/repos/github/moln/expressive-callable-middleware-compat/badge.svg?branch=master)](https://coveralls.io/github/moln/expressive-callable-middleware-compat?branch=master)
[![Latest Stable Version](https://poser.pugx.org/moln/expressive-callable-middleware-compat/v/stable.png)](https://packagist.org/packages/moln/expressive-callable-middleware-compat)

Compatible with callable middleware in Expressive V3

## Install 

```bash
composer require moln/expressive-callable-middleware-compat
```

## Usage

Add `Moln\ExpressiveCallableCompat\ConfigProvider::class` in `expressive-skeleton/config/config.php` 


```php

class TestMiddleware {
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next) {
         return $next($request, $response);
    }
}

$app->pipe(TestMiddleware::class);

```