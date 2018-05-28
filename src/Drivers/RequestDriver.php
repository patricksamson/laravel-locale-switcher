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
        return $this->request->query($key) != null;
    }

    public function get($key, $default = null)
    {
        return $this->request->query($key, $default);
    }
}
