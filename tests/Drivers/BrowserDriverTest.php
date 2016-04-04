<?php

use Lykegenes\LocaleSwitcher\Drivers\BrowserDriver;

class BrowserDriverTest extends Orchestra\Testbench\TestCase
{
    /**
     * @var Mockery\MockInterface
     */
    protected $request;

    /**
     * @var Mockery\MockInterface
     */
    protected $configManager;

    /**
     * @var Lykegenes\LocaleSwitcher\Drivers\BrowserDriver
     */
    protected $browserDriver;

    public function setUp()
    {
        parent::setUp();

        $this->request = Mockery::mock('Illuminate\Http\Request');
        $this->configManager = Mockery::mock('Lykegenes\LocaleSwitcher\ConfigManager');

        $this->browserDriver = new BrowserDriver($this->request, $this->configManager);
    }

    public function tearDown()
    {
        parent::tearDown();

        Mockery::close();
        $this->request = null;
    }

    /** @test */
    public function it_detects_if_browser_sent_accept_language_header()
    {
        $this->request->shouldReceive('header')->atLeast()->once()->andReturn('en-US,en;q=0.8,fr-CA;q=0.5,fr;q=0.3');

        $this->assertTrue($this->browserDriver->has('key'));
    }

    /** @test */
    public function it_gets_locale_from_request()
    {
        $this->request->shouldReceive('header')->atLeast()->once()->andReturn('en-US,en;q=0.8,fr-CA;q=0.5,fr;q=0.3');
        $this->configManager->shouldReceive('isEnabledLocale')->atLeast()->once()
            ->andReturnUsing(function ($locale) {
                return $locale === 'fr';
            });

        $locale = $this->browserDriver->get('Accept-Language');

        $this->assertEquals('fr', $locale);
    }
}
