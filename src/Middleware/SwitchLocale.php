<?php
namespace Lykegenes\LocaleSwitcher\Middleware;

use Closure;
use Lykegenes\LocaleSwitcher\LocaleSwitcher;

class SwitchLocale
{
    /**
     * The locale switcher instance.
     *
     * @var \Lykegenes\LaravelLocaleSwitcher\LocaleSwitcher
     */
    protected $localeSwitcher;

    /**
     * Create a new middleware instance.
     *
     * @param  \Lykegenes\LaravelLocaleSwitcher\LocaleSwitcher  $localeSwitcher
     * @return void
     */
    public function __construct(LocaleSwitcher $localeSwitcher)
    {
        $this->localeSwitcher = $localeSwitcher;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request\
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->localeSwitcher->setAppLocale();

        return $this->localeSwitcher->localeWasSwitched() ? back()->withInput() : $next($request);
    }

}
