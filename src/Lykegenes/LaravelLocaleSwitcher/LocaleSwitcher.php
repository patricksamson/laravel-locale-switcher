<?php namespace Lykegenes\LaravelLocaleSwitcher;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LocaleSwitcher
{
    /**
     * The session used by the guard.
     *
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected $session;

    /**
     * The request instance.
     *
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * The request instance.
     *
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $localeWasSwitched = false;

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const SESSION_KEY = 'locale';

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const REQUEST_KEY = 'locale';

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const COOKIE_KEY = 'locale';

    /**
     * Create a new locale switcher.
     *
     * @param  \Symfony\Component\HttpFoundation\Session\SessionInterface  $session
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @return void
     */
    public function __construct(SessionInterface $session,
        Request $request = null)
    {
        $this->session = $session;
        $this->request = $request;
    }

    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function sessionHasLocale()
    {
        return $this->session->has(static::SESSION_KEY);
    }

    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function requestHasLocale()
    {
        return $this->request->has(static::REQUEST_KEY);
    }

    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function cookieHasLocale()
    {
        return $this->request->hasCookie(static::COOKIE_KEY);
    }

    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function setSessionLocale($locale)
    {
        return $this->session->put(static::SESSION_KEY, $locale);
    }

    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function getLocaleFromSession($default = null)
    {
        return $this->session->get(static::SESSION_KEY, $default);
    }

    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function getLocaleFromRequest($default = null)
    {
        return $this->request->input(static::REQUEST_KEY, $default);
    }

    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function getLocaleFromCookie($default = null)
    {
        return $this->request->cookie(static::COOKIE_KEY, $default);
    }

    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function localeWasSwitched()
    {
        return $this->localeWasSwitched;
    }

    /**
     * Attempt to authenticate using HTTP Basic Auth.
     *
     * @param  string  $field
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public function switchLocale($locale = '')
    {
        if ($this->requestHasLocale())
        {
            $locale = $this->getLocaleFromRequest();
        }
        elseif ($this->cookieHasLocale())
        {
            $locale = $this->getLocaleFromCookie();
        }

        if ($locale != null)
        {
            $this->setSessionLocale($locale);
            $this->localeWasSwitched = true;
        }

        return $locale;
    }

    /**
     * Attempt to authenticate using HTTP Basic Auth.
     *
     * @param  string  $field
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public function setAppLocale()
    {
        $this->switchLocale();

        if ($this->sessionHasLocale())
        {
            app()->setLocale($this->session->get(static::SESSION_KEY));
        }
    }
}
