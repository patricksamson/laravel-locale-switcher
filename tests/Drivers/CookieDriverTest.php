<?php

use Lykegenes\LocaleSwitcher\Drivers\CookieDriver;

class CookieDriverTest extends Orchestra\Testbench\TestCase
{
    /**
     * @var Mockery\MockInterface
     */
    protected $request;

    /**
     * @var Lykegenes\LocaleSwitcher\Drivers\CookieDriver
     */
    protected $cookieDriver;

    public function setUp()
    {
        parent::setUp();

        $this->request = Mockery::mock('Illuminate\Http\Request');

        $this->cookieDriver = new CookieDriver($this->request);
    }

    public function tearDown()
    {
        parent::tearDown();

        Mockery::close();
        $this->request = null;
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
}
