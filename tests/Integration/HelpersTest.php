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
            function () {
                return 'hello world, locale is : '.\App::getLocale();
            },
        ]);

        // Switch Locale using Query String
        $app['router']->get('something', [
            'as' => 'route-querystring',
            'middleware' => [
                \Lykegenes\LocaleSwitcher\Middleware\SwitchLocaleMiddleware::class,
                \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            ],
            function () {
                return 'hello world, locale is : '.\App::getLocale();
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
        $this->assertEquals('http://localhost/en/something', route_localized('route-parameter'));

        $this->app['config']->set('app.locale', 'fr');
        $this->assertEquals('http://localhost/fr/something', route_localized('route-parameter'));

        $this->makeRequest('GET', route_localized('route-parameter'))
             ->see('hello world, locale is : fr');
    }
}
