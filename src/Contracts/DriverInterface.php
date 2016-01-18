<?php

namespace Lykegenes\LocaleSwitcher\Contracts;

interface DriverInterface
{
    public function has($key);
    public function get($key, $default);
    public function store($key, $default);
}
