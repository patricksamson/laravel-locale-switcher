<?php

use Lykegenes\LocaleSwitcher\ConfigManager;

if ( ! function_exists('route_localized')) {
    /**
     * Generate a URL to a named route.
     *
     * @param  string                    $name
     * @param  array                     $parameters
     * @param  bool                      $absolute
     * @param  \Illuminate\Routing\Route $route
     * @return string
     */
    function route_localized($name, $parameters = [], $absolute = true, $route = null)
    {
        $locale = [ConfigManager::getDefaultKey() => app()->getLocale()];
        $parameters = array_merge($locale, $parameters);

        return route($name, $parameters, $absolute, $route);
    }
}

if ( ! function_exists('action_localized')) {
    /**
     * Generate a URL to a controller action.
     *
     * @param  string   $name
     * @param  array    $parameters
     * @param  bool     $absolute
     * @return string
     */
    function action_localized($name, $parameters = [], $absolute = true)
    {
        $locale = [ConfigManager::getDefaultKey() => app()->getLocale()];
        $parameters = array_merge($locale, $parameters);

        return action($name, $parameters, $absolute);
    }
}
