<?php

namespace Lykegenes\LocaleSwitcher\Drivers;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionDriver extends BaseDriver
{
    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const DEFAULT_KEY = 'locale';

    protected $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function has($key = self::DEFAULT_KEY)
    {
        return $this->session->has($key);
    }

    public function get($key = self::DEFAULT_KEY, $default = null)
    {
        return $this->session->get($key, $default);
    }

    public function store($key = self::DEFAULT_KEY, $value)
    {
        return $this->session->put($key, $value);
    }
}
