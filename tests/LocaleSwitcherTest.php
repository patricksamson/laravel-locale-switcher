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

        $this->request = Mockery::mock('Illuminate\Http\Request');
        $this->session = Mockery::mock('Symfony\Component\HttpFoundation\Session\SessionInterface');
        $this->config = Mockery::mock('Lykegenes\LocaleSwitcher\ConfigManager');

        $this->config->shouldReceive('isEnabledLocale')->zeroOrMoreTimes()->andReturn(true);
        $this->config->shouldReceive('getDefaultKey')->zeroOrMoreTimes()->andReturn('locale');

        $this->app->request = $this->request;
        $this->app->session = $this->session;

        $this->localeSwitcher = new LocaleSwitcher($this->app, $this->config);
    }

    public function tearDown()
    {
        parent::tearDown();

        Mockery::close();
        $this->request = null;
        $this->session = null;
    }

    /** @test */
    public function it_uses_application_default_by_default()
    {
        $this->config->shouldReceive('getSourceDrivers')->zeroOrMoreTimes()->andReturn([]);
        $this->config->shouldReceive('getStoreDriver')->zeroOrMoreTimes()->andReturn(null);

        $newLocale = $this->localeSwitcher->setAppLocale();

        $this->assertNull($newLocale);
    }

    /** @test */
    public function it_detects_locale_from_driver()
    {
        $this->config->shouldReceive('getSourceDrivers')->zeroOrMoreTimes()->andReturn([Lykegenes\LocaleSwitcher\Drivers\RequestDriver::class]);

        $this->request->shouldReceive('has')->once()->andReturn(true);
        $this->request->shouldReceive('input')->once()->andReturn('fr');

        $newLocale = $this->localeSwitcher->detectLocale();

        $this->assertEquals('fr', $newLocale);
    }

    /** @test */
    public function it_detects_locale_from_drivers_in_correct_order()
    {
        $this->config->shouldReceive('getSourceDrivers')->zeroOrMoreTimes()->andReturn([
            Lykegenes\LocaleSwitcher\Drivers\RequestDriver::class,
            Lykegenes\LocaleSwitcher\Drivers\SessionDriver::class,
        ]);

        $this->request->shouldReceive('has')->once()->andReturn(true);
        $this->request->shouldReceive('input')->once()->andReturn('fr');

        $this->session->shouldNotReceive('has');
        $this->session->shouldNotReceive('get');

        $newLocale = $this->localeSwitcher->detectLocale();

        $this->assertEquals('fr', $newLocale);
    }

    /** @test */
    public function it_ignores_empty_drivers()
    {
        $this->config->shouldReceive('getSourceDrivers')->zeroOrMoreTimes()->andReturn([
            Lykegenes\LocaleSwitcher\Drivers\RequestDriver::class,
            Lykegenes\LocaleSwitcher\Drivers\SessionDriver::class,
        ]);

        $this->request->shouldReceive('has')->once()->andReturn(false);
        $this->request->shouldNotReceive('input');

        $this->session->shouldReceive('has')->once()->andReturn(true);
        $this->session->shouldReceive('get')->once()->andReturn('fr');

        $newLocale = $this->localeSwitcher->detectLocale();

        $this->assertEquals('fr', $newLocale);
    }

    /** @test */
    public function it_sets_application_locale()
    {
        $this->config->shouldReceive('getSourceDrivers')->zeroOrMoreTimes()->andReturn([Lykegenes\LocaleSwitcher\Drivers\RequestDriver::class]);
        $this->request->shouldReceive('has')->once()->andReturn(true);
        $this->request->shouldReceive('input')->once()->andReturn('fr');

        $this->config->shouldReceive('getStoreDriver')->zeroOrMoreTimes()->andReturn(null);

        $newLocale = $this->localeSwitcher->setAppLocale();

        $this->assertEquals('fr', $newLocale);
        $this->assertEquals('fr', $this->app->getLocale());
    }

    /** @test */
    public function it_returns_enabled_locales()
    {
        $expected = [
            'en' => 'English',
            'fr' => 'Français',
        ];
        $this->config->shouldReceive('getEnabledLocales')->zeroOrMoreTimes()->andReturn($expected);

        $locales = $this->localeSwitcher->getEnabledLocales();

        $this->assertEquals($expected, $locales);
    }
}
