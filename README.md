# Laravel Locale Switcher

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Total Downloads][ico-downloads]][link-downloads]

A Simple Laravel middleware to easily load and switch the user's locale.

## Install

Via Composer

``` bash
$ composer require lykegenes/laravel-locale-switcher
```

Then, add this to your $middleware array in App/Http/Kernel.php
``` php
\Lykegenes\LaravelLocaleSwitcher\Middleware\SwitchLocale::class,
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

You can build the routes like so :
``` php
$url = action('SomeController@someFunction').'?locale=en';
$url = route('someNamedRoute').'?locale=en';
$url = url('/some/url').'?locale=en';
```

## Testing

``` bash
$ composer test
```

## Credits

- [Patrick Samson][link-author]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/lykegenes/laravel-locale-switcher.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/Lykegenes/laravel-locale-switcher/master.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/lykegenes/laravel-locale-switcher.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/lykegenes/laravel-locale-switcher
[link-travis]: https://travis-ci.org/Lykegenes/laravel-locale-switcher
[link-downloads]: https://packagist.org/packages/lykegenes/laravel-locale-switcher
[link-author]: https://github.com/Lykegenes
[link-contributors]: ../../contributors
