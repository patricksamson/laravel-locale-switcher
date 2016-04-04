<?php

namespace Lykegenes\LocaleSwitcher\Drivers;

use Illuminate\Http\Request;

class RequestDriver extends BaseDriver
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function has($key)
    {
        return $this->request->has($key);
    }

    public function get($key, $default = null)
    {
        return $this->request->input($key, $default);
    }
}
