<?php

use Lykegenes\LaravelLocaleSwitcher\LocaleSwitcher;

class LocaleSwitcherTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mockery\MockInterface
     */
    protected $request;

    /**
     * @var Mockery\MockInterface
     */
    protected $session;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var LocaleSwitcher
     */
    protected $localeSwitcher;

    public function setUp()
    {
        $this->request = Mockery::mock('Illuminate\Http\Request');
        $this->container = Mockery::mock('Illuminate\Contracts\Container\Container');
        $this->session = Mockery::mock('Symfony\Component\HttpFoundation\Session\SessionInterface');
        $this->request->shouldReceive('getSession')->zeroOrMoreTimes()->andReturn($this->session);
        $this->localeSwitcher = new LocaleSwitcher($this->session, $this->request);
    }

    public function tearDown()
    {
        Mockery::close();
        $this->request = null;
        $this->container = null;
        $this->session = null;
    }

    /** @test */
    public function it_stores_locale_in_session()
    {
        $this->session->shouldReceive('get')->zeroOrMoreTimes()->andReturn('fr');
        $this->request->shouldReceive('has')->zeroOrMoreTimes()->andReturn(false);
        $this->request->shouldReceive('hasCookie')->zeroOrMoreTimes()->andReturn(false);

        $newLocale = $this->localeSwitcher->switchLocale('fr');

        $this->assertTrue($this->localeSwitcher->localeWasSwitched());
        $this->assertNotEquals('', $newLocale);
        $this->assertEquals('fr', $newLocale);
    }

    /** @test */
    public function it_switches_locale_from_request()
    {
        $this->request->shouldReceive('has')->zeroOrMoreTimes()->andReturn(true);
        $this->request->shouldReceive('input')->zeroOrMoreTimes()->andReturn('fr');
        $this->request->shouldReceive('hasCookie')->zeroOrMoreTimes()->andReturn(false);

        $newLocale = $this->localeSwitcher->switchLocale();

        $this->assertTrue($this->localeSwitcher->localeWasSwitched());
        $this->assertNotEquals('', $newLocale);
        $this->assertEquals('fr', $newLocale);
    }

    /** @test */
    public function it_switches_locale_from_cookie()
    {
        $this->request->shouldReceive('has')->zeroOrMoreTimes()->andReturn(false);
        $this->request->shouldReceive('hasCookie')->zeroOrMoreTimes()->andReturn(true);
        $this->request->shouldReceive('cookie')->zeroOrMoreTimes()->andReturn('fr');

        $newLocale = $this->localeSwitcher->switchLocale();

        $this->assertTrue($this->localeSwitcher->localeWasSwitched());
        $this->assertNotEquals('', $newLocale);
        $this->assertEquals('fr', $newLocale);
    }
    
    /** @test */
    public function it_uses_request_over_cookie()
    {
        $this->request->shouldReceive('has')->zeroOrMoreTimes()->andReturn(true);
        $this->request->shouldReceive('input')->zeroOrMoreTimes()->andReturn('fr');
        $this->request->shouldReceive('hasCookie')->zeroOrMoreTimes()->andReturn(true);
        $this->request->shouldReceive('cookie')->zeroOrMoreTimes()->andReturn('en');

        $newLocale = $this->localeSwitcher->switchLocale();

        $this->assertTrue($this->localeSwitcher->localeWasSwitched());
        $this->assertNotEquals('', $newLocale);
        $this->assertNotEquals('en', $newLocale);
        $this->assertEquals('fr', $newLocale);
    }
}
