# Laravel Locale Switcher

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

A Simple Laravel middleware to easily load and switch the user's locale.

## Install

Via Composer

``` bash
composer require lykegenes/laravel-locale-switcher
```

Then, add this to your Service Providers :
``` php
Lykegenes\LocaleSwitcher\ServiceProvider::class,
```

and add this Alias [Optional]
``` php
'LocaleSwitcher' => Lykegenes\LocaleSwitcher\Facades\LocaleSwitcher::class,
```

Finally, you will need to register this Middleware either in your `Kernel.php` or directly in your routes.
This way, you can control which routes will have automatic locale detection (Web pages) or not (your API).
Make sure this middleware is placed **after** any Session or Cookie related middlewares from the framework.
``` php
\Lykegenes\LocaleSwitcher\Middleware\SwitchLocaleMiddleware::class,
```

Optionally, you can publish and edit the configuration file :
``` bash
php artisan vendor:publish --provider="Lykegenes\LocaleSwitcher\ServiceProvider" --tag=config
```

## Usage

To change the locale, simply add **"locale"** to the route parameters.
It works for all your routes.
Some examples :
```
http://my-app.com/?locale=en
http://my-app.com/some/sub/route?locale=fr
http://my-app.com/some/sub/route?locale=es&otherParam=value
```
This will store the locale in the user's session, and set it as the current locale everytime the user requests a page.

You can build the routes with the included helpers. The URLs will be generated using the current locale.
```php
$url = action_localized('SomeController@someFunction', ['your' => 'parameters']);
$url = route_localized('someNamedRoute', ['your' => 'parameters']);
```

To build a URL to the same page, but with a different locale, use the `switch_locale()` helper.
```php
$url = switch_locale('fr'); // URL of the French version of the current page.
```


You can easily generate a dropdown using the [laravelcollective/html](https://github.com/LaravelCollective/html) package :
```php
HTML::ul(LocaleSwitcher::getEnabledLocales());
```

## Credits

- [Patrick Samson][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/lykegenes/laravel-locale-switcher.svg?style=flat-square
[ico-license]: https://img.shields.io/packagist/l/lykegenes/laravel-locale-switcher.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/Lykegenes/laravel-locale-switcher/master.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/lykegenes/laravel-locale-switcher.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/lykegenes/laravel-locale-switcher.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/lykegenes/laravel-locale-switcher
[link-travis]: https://travis-ci.org/Lykegenes/laravel-locale-switcher
[link-code-quality]: https://scrutinizer-ci.com/g/lykegenes/laravel-locale-switcher
[link-downloads]: https://packagist.org/packages/lykegenes/laravel-locale-switcher
[link-author]: https://github.com/lykegenes
[link-contributors]: ../../contributors
