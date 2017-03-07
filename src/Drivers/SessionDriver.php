<?php

namespace Lykegenes\LocaleSwitcher\Drivers;

use Illuminate\Contracts\Session\Session;

class SessionDriver extends BaseDriver
{
    protected $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function has($key)
    {
        return $this->session->has($key);
    }

    public function get($key, $default = null)
    {
        return $this->session->get($key, $default);
    }

    public function store($key, $value)
    {
        return $this->session->put($key, $value);
    }
}
