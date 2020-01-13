<?php

use Lykegenes\LocaleSwitcher\Drivers\RequestDriver;

class RequestDriverTest extends Orchestra\Testbench\TestCase
{
    /**
     * @var Mockery\MockInterface
     */
    protected $request;

    /**
     * @var Lykegenes\LocaleSwitcher\Drivers\RequestDriver
     */
    protected $requestDriver;

    public function setUp(): void
    {
        parent::setUp();

        $this->request = Mockery::mock('Illuminate\Http\Request');

        $this->requestDriver = new RequestDriver($this->request);
    }

    public function tearDown(): void
    {
        parent::tearDown();

        Mockery::close();
        $this->request = null;
    }

    /** @test */
    public function it_detects_if_request_has_locale()
    {
        $this->request->shouldReceive('query')->atLeast()->once()->andReturn(true);

        $this->assertTrue($this->requestDriver->has('key'));
    }

    /** @test */
    public function it_gets_locale_from_request()
    {
        $this->request->shouldReceive('query')->atLeast()->once()->andReturn('en');

        $locale = $this->requestDriver->get('key');

        $this->assertEquals('en', $locale);
    }
}
