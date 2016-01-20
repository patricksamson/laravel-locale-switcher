<?php

namespace Lykegenes\LocaleSwitcher\TestCase;

class RouteTest extends \Orchestra\Testbench\TestCase
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

        $app['router']->get('locale', function () {
            return 'hello world, locale is : '.\App::getLocale();
        })->middleware('Lykegenes\LocaleSwitcher\Middleware\SwitchLocaleMiddleware');

        $app['router']->get('hello', function () {
            return 'hello world';
        });

        $app['router']->resource('foo', 'Lykegenes\LocaleSwitcher\TestCase\FooController');
    }

    /**
     * Test GET hello route.
     *
     * @test
     */
    public function testGetHelloRoute()
    {
        $this->get('hello')
            ->see('hello world');
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
        $this->makeRequest('GET', 'locale', ['locale' => 'fr'])
            ->see('hello world, locale is : fr');
    }

    /** @test */
    public function testDetectLocaleFromSession()
    {
        $this->withSession(['locale' => 'fr'])
            ->visit('locale')
            ->see('hello world, locale is : fr');
    }

    /** @test */
    public function testDetectLocaleFromCookie()
    {
        $this->makeRequest('GET', 'locale', [], ['locale' => 'fr'])
            ->see('hello world, locale is : fr');
    }

    /**
     * Test GET foo/index route using action.
     *
     * @test
     */
    public function testGetFooIndexRouteUsingAction()
    {
        $crawler = $this->action('GET', '\Lykegenes\LocaleSwitcher\TestCase\FooController@index');

        $this->assertResponseOk();
        $this->assertEquals('FooController@index', $crawler->getContent());
    }

    /**
     * Test GET foo/index route using call.
     *
     * @test
     */
    public function testGetFooIndexRouteUsingCall()
    {
        $crawler = $this->call('GET', 'foo');

        $this->assertResponseOk();
        $this->assertEquals('FooController@index', $crawler->getContent());
    }
}

class FooController extends \Illuminate\Routing\Controller
{
    public function index()
    {
        return 'FooController@index';
    }
}
