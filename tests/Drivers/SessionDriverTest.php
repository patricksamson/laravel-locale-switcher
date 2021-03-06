<?php

use Lykegenes\LocaleSwitcher\Drivers\SessionDriver;

class SessionDriverTest extends Orchestra\Testbench\TestCase
{
    /**
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected $session;

    /**
     * @var Lykegenes\LocaleSwitcher\Drivers\SessionDriver
     */
    protected $sessionDriver;

    public function setUp(): void
    {
        parent::setUp();

        $this->session = Mockery::mock('Illuminate\Contracts\Session\Session');

        $this->sessionDriver = new SessionDriver($this->session);
    }

    public function tearDown(): void
    {
        parent::tearDown();

        Mockery::close();
        $this->session = null;
    }

    /** @test */
    public function it_detects_if_session_has_locale()
    {
        $this->session->shouldReceive('has')->atLeast()->once()->andReturn(true);

        $this->assertTrue($this->sessionDriver->has('key'));
    }

    /** @test */
    public function it_gets_locale_from_session()
    {
        $this->session->shouldReceive('get')->atLeast()->once()->andReturn('en');

        $locale = $this->sessionDriver->get('key');

        $this->assertEquals('en', $locale);
    }

    /** @test */
    public function it_stores_locale_in_session()
    {
        $this->session->shouldReceive('put')->atLeast()->once()->andReturn(true);

        $this->assertTrue($this->sessionDriver->store('key', 'en'));
    }
}
