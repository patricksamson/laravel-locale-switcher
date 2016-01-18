<?php

namespace Lykegenes\LocaleSwitcher\Drivers;

use Lykegenes\LocaleSwitcher\Contracts\DriverInterface;

abstract class BaseDriver implements DriverInterface
{
    const DEFAULT_KEY = 'locale';

    public function has($key)
    {
        return false;
    }

    public function get($key, $default)
    {
        return false;
    }

    public function store($key, $default)
    {
        return false;
    }
}
