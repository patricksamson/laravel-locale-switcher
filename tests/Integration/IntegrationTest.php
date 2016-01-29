<?php

namespace Lykegenes\LocaleSwitcher\TestCase;

class IntegrationTest extends \Orchestra\Testbench\TestCase
{
    /**
     * Get package providers.
     *
     * @param \Illuminate\Foundation\Application $app
     *
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

        $app['router']->get('locale', [
            'middleware' => [
                \Lykegenes\LocaleSwitcher\Middleware\SwitchLocaleMiddleware::class,
                \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            ],
            function () {
                return 'hello world, locale is : '.\App::getLocale();
            },
        ]);
    }

    /** @test */
    public function testGetLocaleRoute()
    {
        $this->visit('locale')
            ->see('hello world, locale is : en');
    }

    /** @test */
    public function testDetectLocaleFromRequestUrl()
    {
        $this->app['config']->set('locale-switcher.source_drivers', [\Lykegenes\LocaleSwitcher\Drivers\RequestDriver::class]);

        $this->makeRequest('GET', 'locale', ['locale' => 'fr'])
            ->see('hello world, locale is : fr');
    }

    /** @test */
    public function testDetectLocaleFromSession()
    {
        $this->app['config']->set('locale-switcher.source_drivers', [\Lykegenes\LocaleSwitcher\Drivers\SessionDriver::class]);

        $this->withSession(['locale' => 'fr'])
            ->visit('locale')
            ->see('hello world, locale is : fr');
    }

    /** @test */
    public function testDetectLocaleFromCookie()
    {
        $this->app['config']->set('locale-switcher.source_drivers', [\Lykegenes\LocaleSwitcher\Drivers\CookieDriver::class]);

        $this->makeRequest('GET', 'locale', [], ['locale' => 'fr'])
            ->see('hello world, locale is : fr');
    }

    /** @test */
    public function testDetectLocaleFromRouteParameter()
    {
        $this->app['config']->set('locale-switcher.source_drivers', [\Lykegenes\LocaleSwitcher\Drivers\RouteParameterDriver::class]);

        $this->app['router']->get('{locale}/parametertest', ['middleware' => \Lykegenes\LocaleSwitcher\Middleware\SwitchLocaleMiddleware::class, function () {
            return 'hello world, locale is : '.\App::getLocale();
        }]);

        $this->visit('fr/parametertest')
            ->see('hello world, locale is : fr');
    }

    /** @test */
    public function testStoreLocaleInSession()
    {
        $this->app['config']->set('locale-switcher.source_drivers', [\Lykegenes\LocaleSwitcher\Drivers\RequestDriver::class]);
        $this->app['config']->set('locale-switcher.store_driver', \Lykegenes\LocaleSwitcher\Drivers\SessionDriver::class);

        $this->makeRequest('GET', 'locale', ['locale' => 'fr'])
            ->see('hello world, locale is : fr')
            ->assertSessionHas('locale', 'fr');
    }

    /** @test */
    public function testStoreLocaleInCookie()
    {
        $this->app['config']->set('locale-switcher.source_drivers', [\Lykegenes\LocaleSwitcher\Drivers\RequestDriver::class]);
        $this->app['config']->set('locale-switcher.store_driver', \Lykegenes\LocaleSwitcher\Drivers\CookieDriver::class);

        $this->makeRequest('GET', 'locale', ['locale' => 'fr'])
            ->see('hello world, locale is : fr')
            ->seeCookie('locale', 'fr');
    }
}
