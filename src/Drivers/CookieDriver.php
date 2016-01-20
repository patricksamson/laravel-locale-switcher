<?php

namespace Lykegenes\LocaleSwitcher\Drivers;

use Illuminate\Http\Request;
use Illuminate\Cookie\CookieJar;

class CookieDriver extends BaseDriver
{
    protected $request;
    protected $cookieJar;

    public function __construct(Request $request, CookieJar $cookieJar)
    {
        $this->request = $request;
        $this->cookieJar = $cookieJar;
    }

    public function has($key)
    {
        return $this->request->hasCookie($key);
    }

    public function get($key, $default = null)
    {
        return $this->request->cookie($key, $default);
    }

    public function store($key, $value)
    {
        return $this->cookieJar->queue(cookie($key, $value, 45000));
    }
}
