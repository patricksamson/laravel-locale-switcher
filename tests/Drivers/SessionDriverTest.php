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

    public function setUp()
    {
        parent::setUp();

        $this->session = Mockery::mock('Symfony\Component\HttpFoundation\Session\SessionInterface');

        $this->sessionDriver = new SessionDriver($this->session);
    }

    public function tearDown()
    {
        Mockery::close();
        $this->session = null;
    }

    /** @test */
    public function it_detects_if_session_has_locale()
    {
        $this->session->shouldReceive('has')->zeroOrMoreTimes()->andReturn(true);

        $this->assertTrue($this->sessionDriver->has());
    }

    /** @test */
    public function it_gets_locale_from_session()
    {
        $this->session->shouldReceive('get')->zeroOrMoreTimes()->andReturn('en');

        $locale = $this->sessionDriver->get();

        $this->assertEquals('en', $locale);
    }

    /** @test */
    public function it_stores_locale_in_session()
    {
        $this->session->shouldReceive('put')->zeroOrMoreTimes()->andReturn(true);

        $this->assertTrue($this->sessionDriver->store($this->sessionDriver::DEFAULT_KEY, 'en'));
    }
}
