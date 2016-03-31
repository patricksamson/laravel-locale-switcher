<?php

namespace Lykegenes\LocaleSwitcher\TestCase;

class HelpersTest extends \Orchestra\Testbench\TestCase
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            'Lykegenes\LocaleSwitcher\ServiceProvider',
        ];
    }

    /**
     * Define environment setup.
     *
     * @param Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.locale', 'en');

        // Detect Locale from route parameter
        $app['router']->get('{locale}/something', [
            'as' => 'route-parameter',
            'middleware' => [
                \Lykegenes\LocaleSwitcher\Middleware\SwitchLocaleMiddleware::class,
                \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            ],
            'uses' => TestHelperController::$route_parameter,
        ]);

        // Detect Locale using Query String
        $app['router']->get('something', [
            'as' => 'route-querystring',
            'middleware' => [
                \Lykegenes\LocaleSwitcher\Middleware\SwitchLocaleMiddleware::class,
                \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            ],
            'uses' => TestHelperController::$route_querystring,
        ]);

        // Named switch locale route
        $app['router']->get('named/{newLocale}', [
            'as' => 'named-switch-locale',
            'middleware' => [
                \Lykegenes\LocaleSwitcher\Middleware\SwitchLocaleMiddleware::class,
                \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            ],
            'uses' => TestHelperController::$named_switch_locale,
        ]);

        $app['router']->get('action/{newLocale}', [
            'middleware' => [
                \Lykegenes\LocaleSwitcher\Middleware\SwitchLocaleMiddleware::class,
                \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            ],
            'uses' => TestHelperController::$action_switch_locale,
        ]);

        $app['router']->get('closure/{newLocale}', [
            'middleware' => [
                \Lykegenes\LocaleSwitcher\Middleware\SwitchLocaleMiddleware::class,
                \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            ],
            function ($newLocale) {
                return 'hello world, url is : '.switch_locale($newLocale);
            },
        ]);
    }

    /**
     * @test
     */
    public function testRouteLocalizedHelperWithRouteParams()
    {
        $this->assertEquals('http://localhost/en/something', route_localized('route-parameter'));

        $this->app['config']->set('app.locale', 'fr');
        $this->assertEquals('http://localhost/fr/something', route_localized('route-parameter'));

        $this->makeRequest('GET', route_localized('route-parameter'))
             ->see('hello world, locale is : fr');
    }

    /**
     * @test
     */
    public function testRouteLocalizedHelperWithQueryString()
    {
        $this->assertEquals('http://localhost/something?locale=en', route_localized('route-querystring'));

        $this->app['config']->set('app.locale', 'fr');
        $this->assertEquals('http://localhost/something?locale=fr', route_localized('route-querystring'));

        $this->makeRequest('GET', route_localized('route-querystring'))
             ->see('hello world, locale is : fr');
    }

    /**
     * @test
     */
    public function testActionLocalizedHelperWithRouteParams()
    {
        $this->assertEquals('http://localhost/en/something', action_localized(TestHelperController::$route_parameter));

        $this->app['config']->set('app.locale', 'fr');
        $this->assertEquals('http://localhost/fr/something', action_localized(TestHelperController::$route_parameter));

        $this->makeRequest('GET', action_localized(TestHelperController::$route_parameter))
             ->see('hello world, locale is : fr');
    }

    /**
     * @test
     */
    public function testActionLocalizedHelperWithQueryString()
    {
        $this->assertEquals('http://localhost/something?locale=en', action_localized(TestHelperController::$route_querystring));

        $this->app['config']->set('app.locale', 'fr');
        $this->assertEquals('http://localhost/something?locale=fr', action_localized(TestHelperController::$route_querystring));

        $this->makeRequest('GET', action_localized(TestHelperController::$route_querystring))
             ->see('hello world, locale is : fr');
    }

    /**
     * @test
     */
    public function testSwitchLocaleHelperWithQueryString()
    {
        // Query string has to be appended
        $this->makeRequest('GET', route_localized('named-switch-locale', ['newLocale' => 'fr']))
             ->see('http://localhost/named/fr?locale=fr');

        // Query String has to be appended
        $this->makeRequest('GET', action_localized(TestHelperController::$action_switch_locale, ['newLocale' => 'fr']))
             ->see('http://localhost/action/fr?locale=fr');

        // We can't match a Closure. Return the exact same urkl, without the query string
        $this->makeRequest('GET', '/closure/fr')
             ->see('http://localhost/closure/fr');
    }
}

class TestHelperController extends \Illuminate\Routing\Controller
{
    public static $route_parameter = 'Lykegenes\LocaleSwitcher\TestCase\TestHelperController@route_parameter';
    public static $route_querystring = 'Lykegenes\LocaleSwitcher\TestCase\TestHelperController@route_querystring';
    public static $named_switch_locale = 'Lykegenes\LocaleSwitcher\TestCase\TestHelperController@named_switch_locale';
    public static $action_switch_locale = 'Lykegenes\LocaleSwitcher\TestCase\TestHelperController@action_switch_locale';

    public function route_parameter()
    {
        return 'hello world, locale is : '.\App::getLocale();
    }

    public function route_querystring()
    {
        return 'hello world, locale is : '.\App::getLocale();
    }

    public function named_switch_locale($newLocale)
    {
        return 'hello world, url is : '.switch_locale($newLocale);
    }

    public function action_switch_locale($newLocale)
    {
        return 'hello world, url is : '.switch_locale($newLocale);
    }
}
