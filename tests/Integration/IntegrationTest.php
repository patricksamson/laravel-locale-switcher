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
            ],
            function () {
                return 'hello world, locale is : '.\App::getLocale();
            },
        ]);
    }

    /**
     * @test
     */
    public function testGetLocaleRoute()
    {
        $this->get('locale')
             ->assertSee('hello world, locale is : en');
    }

    /**
     * @test
     */
    public function testDetectLocaleFromRequestUrl()
    {
        $this->app['config']->set('locale-switcher.source_drivers', [\Lykegenes\LocaleSwitcher\Drivers\RequestDriver::class]);

        $this->get('locale?locale=fr')
             ->assertSee('hello world, locale is : fr');

        // switch to new locale on subsequent request
        $this->get('locale?locale=en')
             ->assertSee('hello world, locale is : en');
    }

    /**
     * @test
     */
    public function testDetectLocaleFromSession()
    {
        $this->app['config']->set('locale-switcher.source_drivers', [\Lykegenes\LocaleSwitcher\Drivers\SessionDriver::class]);

        $this->withSession(['locale' => 'fr'])
             ->get('locale')
             ->assertSee('hello world, locale is : fr');

        $this->withSession(['locale' => 'en'])
            ->get('locale')
            ->assertSee('hello world, locale is : en');
    }

    /**
     * @test
     */
    public function testDetectLocaleFromRouteParameter()
    {
        $this->app['config']->set('locale-switcher.source_drivers', [\Lykegenes\LocaleSwitcher\Drivers\RouteParameterDriver::class]);

        $this->app['router']->get('{locale}/parametertest', ['middleware' => \Lykegenes\LocaleSwitcher\Middleware\SwitchLocaleMiddleware::class, function () {
            return 'hello world, locale is : '.\App::getLocale();
        }]);

        $this->get('fr/parametertest')
             ->assertSee('hello world, locale is : fr');

        // switch to new locale on subsequent request
        $this->get('en/parametertest')
             ->assertSee('hello world, locale is : en');
    }

    /**
     * @test
     */
    public function testStoreLocaleInSession()
    {
        $this->app['config']->set('locale-switcher.source_drivers', [\Lykegenes\LocaleSwitcher\Drivers\RequestDriver::class]);
        $this->app['config']->set('locale-switcher.store_driver', \Lykegenes\LocaleSwitcher\Drivers\SessionDriver::class);

        $this->get('locale?locale=fr')
             ->assertSee('hello world, locale is : fr')
             ->assertSessionHas('locale', 'fr');

        // switch to new locale on subsequent request
        $this->get('locale?locale=en')
             ->assertSee('hello world, locale is : en')
             ->assertSessionHas('locale', 'en');
    }
}
