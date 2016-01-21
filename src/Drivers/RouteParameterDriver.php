<?php

namespace Lykegenes\LocaleSwitcher\Drivers;

use Illuminate\Http\Request;

class RouteParameterDriver extends BaseDriver
{
    /**
     * @var Illuminate\Routing\Route
     */
    protected $currentroute;

    public function __construct(Request $request)
    {
        $this->currentroute = $request->route();
    }

    public function has($key)
    {
        return $this->currentroute->hasParameter($key);
    }

    public function get($key, $default = null)
    {
        return $this->currentroute->getParameter($key, $default);
    }
}
