<?php

namespace Lykegenes\LocaleSwitcher\Drivers;

use Illuminate\Http\Request;

class BrowserDriver extends BaseDriver
{
    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const DEFAULT_KEY = 'Accept-Language';

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function has($key = self::DEFAULT_KEY)
    {
        return !isnull($this->request->header($key, null));
    }

    public function get($key = self::DEFAULT_KEY, $default = null)
    {
        return $this->request->header($key, $default);
    }
}
