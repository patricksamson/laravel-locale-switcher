<?php

use Lykegenes\LocaleSwitcher\Drivers\CookieDriver;

class CookieDriverTest extends Orchestra\Testbench\TestCase
{
    /**
     * @var Mockery\MockInterface
     */
    protected $request;

    protected $cookieJar;

    /**
     * @var Lykegenes\LocaleSwitcher\Drivers\CookieDriver
     */
    protected $cookieDriver;

    public function setUp()
    {
        parent::setUp();

        $this->request = Mockery::mock('Illuminate\Http\Request');
        $this->cookieJar = Mockery::mock('Illuminate\Cookie\CookieJar');

        $this->cookieDriver = new CookieDriver($this->request, $this->cookieJar);
    }

    public function tearDown()
    {
        parent::tearDown();

        Mockery::close();
        $this->request = null;
        $this->cookieJar = null;
    }

    /** @test */
    public function it_detects_if_cookie_has_locale()
    {
        $this->request->shouldReceive('hasCookie')->atLeast()->once()->andReturn(true);

        $this->assertTrue($this->cookieDriver->has('key'));
    }

    /** @test */
    public function it_gets_locale_from_cookie()
    {
        $this->request->shouldReceive('cookie')->atLeast()->once()->andReturn('en');

        $locale = $this->cookieDriver->get('key');

        $this->assertEquals('en', $locale);
    }

    /** @test */
    public function it_stores_locale_in_session()
    {
        $this->cookieJar->shouldReceive('queue')->atLeast()->once()->andReturn(true);

        $this->assertTrue($this->cookieDriver->store('key', 'en'));
    }
}
