<?php

use Lykegenes\LocaleSwitcher\Drivers\CookieDriver;

class CookieDriverTest extends PHPUnit_Framework_TestCase
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
        $this->request = Mockery::mock('Illuminate\Http\Request');

        $this->cookieDriver = new CookieDriver($this->request);
    }

    public function tearDown()
    {
        Mockery::close();
        $this->request = null;
    }

    /** @test */
    public function it_detects_if_cookie_has_locale()
    {
        $this->request->shouldReceive('hasCookie')->zeroOrMoreTimes()->andReturn(true);

        $this->assertTrue($this->cookieDriver->has());
    }

    /** @test */
    public function it_gets_locale_from_cookie()
    {
        $this->request->shouldReceive('cookie')->zeroOrMoreTimes()->andReturn('en');

        $locale = $this->cookieDriver->get();

        $this->assertEquals('en', $locale);
    }
}
