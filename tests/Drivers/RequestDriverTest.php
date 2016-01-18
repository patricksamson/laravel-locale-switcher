<?php

use Lykegenes\LocaleSwitcher\Drivers\RequestDriver;

class RequestDriverTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mockery\MockInterface
     */
    protected $request;

    /**
     * @var Lykegenes\LocaleSwitcher\Drivers\RequestDriver
     */
    protected $requestDriver;

    public function setUp()
    {
        $this->request = Mockery::mock('Illuminate\Http\Request');

        $this->requestDriver = new RequestDriver($this->request);
    }

    public function tearDown()
    {
        Mockery::close();
        $this->request = null;
    }

    /** @test */
    public function it_detects_if_request_has_locale()
    {
        $this->request->shouldReceive('has')->zeroOrMoreTimes()->andReturn(true);

        $this->assertTrue($this->requestDriver->has());
    }

    /** @test */
    public function it_gets_locale_from_request()
    {
        $this->request->shouldReceive('get')->zeroOrMoreTimes()->andReturn('en');

        $locale = $this->requestDriver->get();

        $this->assertEquals('en', $locale);
    }
}
