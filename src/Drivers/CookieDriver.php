<?php

namespace Lykegenes\LocaleSwitcher\Drivers;

use Illuminate\Http\Request;

class CookieDriver extends BaseDriver
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function has($key)
    {
        return $this->request->hasCookie($key);
    }

    public function get($key, $default = null)
    {
        return $this->request->cookie($key, $default);
    }
}
