<?php

use Lykegenes\LocaleSwitcher\LocaleSwitcher;

class LocaleSwitcherTest extends Orchestra\Testbench\TestCase
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
     * @var LocaleSwitcher
     */
    protected $localeSwitcher;

    /**
     * @var ConfigManager
     */
    protected $config;

    public function setUp()
    {
        parent::setUp();

        //$this->app = Mockery::mock('Illuminate\Foundation\Application');
        $this->request = Mockery::mock('Illuminate\Http\Request');
        $this->session = Mockery::mock('Symfony\Component\HttpFoundation\Session\SessionInterface');
        $this->config = Mockery::mock('Lykegenes\LocaleSwitcher\ConfigManager');

        $this->session->shouldReceive('put')->zeroOrMoreTimes();
        $this->request->shouldReceive('getSession')->zeroOrMoreTimes()->andReturn($this->session);
        $this->config->shouldReceive('isEnabledLocale')->zeroOrMoreTimes()->andReturn(true);

        $this->localeSwitcher = new LocaleSwitcher($this->app, $this->config);
    }

    public function tearDown()
    {
        Mockery::close();
        $this->request = null;
        $this->session = null;
    }

    /** @test */
    public function it_uses_application_default_by_default()
    {
        $this->config->shouldReceive('getSourceDrivers')->zeroOrMoreTimes()->andReturn([]);

        $newLocale = $this->localeSwitcher->setAppLocale();

        $this->assertNull($newLocale);
    }

    /** @test */
    public function it_detects_locale_from_driver()
    {
        $this->config->shouldReceive('getSourceDrivers')->zeroOrMoreTimes()->andReturn([Lykegenes\LocaleSwitcher\Drivers\RequestDriver::class]);

        $request = Mockery::mock('Illuminate\Http\Request');
        $request->shouldReceive('has')->zeroOrMoreTimes()->andReturn(true);
        $request->shouldReceive('input')->zeroOrMoreTimes()->andReturn('fr');
        $this->app->request = $request;

        $newLocale = $this->localeSwitcher->detectLocale();

        $this->assertEquals('fr', $newLocale);
    }

    /** @test */
    public function it_detects_locale_from_drivers_in_correct_order()
    {
        $this->config->shouldReceive('getSourceDrivers')->zeroOrMoreTimes()->andReturn([
            Lykegenes\LocaleSwitcher\Drivers\RequestDriver::class,
            Lykegenes\LocaleSwitcher\Drivers\SessiontDriver::class,
        ]);

        $request = Mockery::mock('Illuminate\Http\Request');
        $request->shouldReceive('has')->zeroOrMoreTimes()->andReturn(true);
        $request->shouldReceive('input')->zeroOrMoreTimes()->andReturn('fr');
        $this->app->request = $request;

        $session = Mockery::mock('Symfony\Component\HttpFoundation\Session\SessionInterface');
        $session->shouldNotReceive('has');
        $session->shouldNotReceive('get');
        $this->app->session = $session;

        $newLocale = $this->localeSwitcher->detectLocale();

        $this->assertEquals('fr', $newLocale);
    }

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

    public function it_returns_enabled_locales()
    {
        $expected = [
            'en' => 'English',
            'fr' => 'Français',
        ];
        $this->currentConfig->shouldReceive('getEnabledLocales')->zeroOrMoreTimes()->andReturn($expected);

        $locales = $this->localeSwitcher->getEnabledLocales();

        $this->assertEquals($expected, $locales);
    }
}
