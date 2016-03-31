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

        // Switch Locale from route parameter
        $app['router']->get('{locale}/something', [
            'as' => 'route-parameter',
            'middleware' => [
                \Lykegenes\LocaleSwitcher\Middleware\SwitchLocaleMiddleware::class,
                \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            ],
            'uses' => TestHelperController::$route_parameter,
        ]);

        // Switch Locale using Query String
        $app['router']->get('something', [
            'as' => 'route-querystring',
            'middleware' => [
                \Lykegenes\LocaleSwitcher\Middleware\SwitchLocaleMiddleware::class,
                \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            ],
            'uses' => TestHelperController::$route_querystring,
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
}

class TestHelperController extends \Illuminate\Routing\Controller
{
    public static $route_parameter = 'Lykegenes\LocaleSwitcher\TestCase\TestHelperController@route_parameter';
    public static $route_querystring = 'Lykegenes\LocaleSwitcher\TestCase\TestHelperController@route_querystring';

    public function route_parameter()
    {
        return 'hello world, locale is : '.\App::getLocale();
    }

    public function route_querystring()
    {
        return 'hello world, locale is : '.\App::getLocale();
    }
}
