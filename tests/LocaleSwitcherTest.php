<?php

use Lykegenes\LocaleSwitcher\LocaleSwitcher;

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
        $this->request   = Mockery::mock('Illuminate\Http\Request');
        $this->container = Mockery::mock('Illuminate\Contracts\Container\Container');
        $this->session   = Mockery::mock('Symfony\Component\HttpFoundation\Session\SessionInterface');
        $this->session->shouldReceive('put')->zeroOrMoreTimes();
        $this->request->shouldReceive('getSession')->zeroOrMoreTimes()->andReturn($this->session);
        $this->localeSwitcher = new LocaleSwitcher($this->session, $this->request);
    }

    public function tearDown()
    {
        Mockery::close();
        $this->request   = null;
        $this->container = null;
        $this->session   = null;
    }

    /** @test */
    public function it_uses_application_default_by_default()
    {
        $this->request->shouldReceive('input')->zeroOrMoreTimes()->andReturn(null);
        $this->request->shouldReceive('cookie')->zeroOrMoreTimes()->andReturn(null);

        $newLocale = $this->localeSwitcher->switchLocale();

        $this->assertFalse($this->localeSwitcher->localeWasSwitched());
        $this->assertNull($newLocale);
    }

    /** @test */
    public function it_stores_locale_in_session()
    {
        $this->request->shouldReceive('input')->zeroOrMoreTimes()->andReturn(null);
        $this->request->shouldReceive('cookie')->zeroOrMoreTimes()->andReturn(null);
        $this->session->shouldReceive('has')->zeroOrMoreTimes()->andReturn(true);
        $this->session->shouldReceive('get')->zeroOrMoreTimes()->andReturn('fr');

        $newLocale = $this->localeSwitcher->switchLocale('fr');

        $this->assertTrue($this->localeSwitcher->localeWasSwitched());
        $this->assertTrue($this->localeSwitcher->sessionHasLocale());
        $this->assertEquals('fr', $this->localeSwitcher->getLocaleFromSession());
        $this->assertNotEquals('', $newLocale);
        $this->assertEquals('fr', $newLocale);
    }

    /** @test */
    public function it_switches_locale_from_request()
    {
        $this->request->shouldReceive('input')->zeroOrMoreTimes()->andReturn('fr');
        $this->request->shouldReceive('cookie')->zeroOrMoreTimes()->andReturn(null);
        $this->request->shouldReceive('has')->zeroOrMoreTimes()->andReturn(true);

        $newLocale = $this->localeSwitcher->switchLocale();

        $this->assertTrue($this->localeSwitcher->localeWasSwitched());
        $this->assertTrue($this->localeSwitcher->requestHasLocale());
        $this->assertNotEquals('', $newLocale);
        $this->assertEquals('fr', $newLocale);
    }

    /** @test */
    public function it_switches_locale_from_cookie()
    {
        $this->request->shouldReceive('input')->zeroOrMoreTimes()->andReturn(null);
        $this->request->shouldReceive('cookie')->zeroOrMoreTimes()->andReturn('fr');
        $this->request->shouldReceive('hasCookie')->zeroOrMoreTimes()->andReturn(true);

        $newLocale = $this->localeSwitcher->switchLocale();

        $this->assertTrue($this->localeSwitcher->localeWasSwitched());
        $this->assertTrue($this->localeSwitcher->cookieHasLocale());
        $this->assertNotEquals('', $newLocale);
        $this->assertEquals('fr', $newLocale);
    }

    /** @test */
    public function it_uses_request_over_cookie()
    {
        $this->request->shouldReceive('input')->zeroOrMoreTimes()->andReturn('fr');
        $this->request->shouldReceive('cookie')->zeroOrMoreTimes()->andReturn('en');

        $newLocale = $this->localeSwitcher->switchLocale();

        $this->assertTrue($this->localeSwitcher->localeWasSwitched());
        $this->assertNotEquals('', $newLocale);
        $this->assertNotEquals('en', $newLocale);
        $this->assertEquals('fr', $newLocale);
    }

    /** @test */
    public function it_sets_app_locale()
    {
        $this->request->shouldReceive('input')->zeroOrMoreTimes()->andReturn('fr');
        $this->session->shouldReceive('has')->zeroOrMoreTimes()->andReturn(true);
        $this->session->shouldReceive('get')->zeroOrMoreTimes()->andReturn('fr');
        Illuminate\Support\Facades\App::shouldReceive('setLocale')->once();

        $newLocale = $this->localeSwitcher->setAppLocale();

        $this->assertTrue($this->localeSwitcher->localeWasSwitched());
        $this->assertNotEquals('', $newLocale);
        $this->assertNotEquals('en', $newLocale);
        $this->assertEquals('fr', $newLocale);
    }

}
