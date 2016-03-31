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

if ( ! function_exists('switch_locale')) {
    /**
     * Generate a URL to a controller action.
     *
     * @param  string   $locale
     * @return string
     */
    function switch_locale($newLocale)
    {
        $route = request()->route();

        $locale = [ConfigManager::getDefaultKey() => $newLocale];

        // The new locale overwrites the current parameters
        $parameters = array_merge($route->parameters(), $locale);

        if (null !== $route->getName()) {
            // Use the same named route
            return route($route->getName(), $parameters);
        } elseif (null !== $route->getActionName()) {
            // Closures are matched like Actions
            if ($route->getActionName() !== 'Closure') {
                // Use the same action
                return action($route->getActionName(), $parameters);
            } else {
                // This is a Closure
                // TODO : Can we do anything?
            }
        }

        // return the current URL
        return request()->url();
    }
}
