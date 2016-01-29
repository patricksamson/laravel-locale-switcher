<?php

return [
    /*
     *--------------------------------------------------------------------------
     * LocaleSwitcher Settings
     *--------------------------------------------------------------------------
     *
     * LocaleSwitcher is enabled by default.
     *
     */
    'enabled' => true,

    /*
     *--------------------------------------------------------------------------
     * LocaleSwitcher Source Drivers
     *--------------------------------------------------------------------------
     *
     * These are the drivers that will be used to determine the current Locale.
     *
     * The Drivers will be used in this order, and if no locale is found,
     * the Application default locale will be used.
     *
     */
    'source_drivers' => [
        Lykegenes\LocaleSwitcher\Drivers\RouteParameterDriver::class, // Laravel Route parameter
        // Lykegenes\LocaleSwitcher\Drivers\RequestDriver::class, // URL query string
        // Lykegenes\LocaleSwitcher\Drivers\CookieDriver::class, // Cookie
        // Lykegenes\LocaleSwitcher\Drivers\SessionDriver::class, // Laravel Session
        // Lykegenes\LocaleSwitcher\Drivers\BrowserDriver::class, // Browser Accept-Language header
    ],

    /*
     *--------------------------------------------------------------------------
     * LocaleSwitcher Store Driver
     *--------------------------------------------------------------------------
     *
     * This is the driver that will be used to store the locale for future requests.
     * It is set to null by default in order to detect the locale on every request.
     * Only one can be used!
     *
     * The included drivers are :
     * Lykegenes\LocaleSwitcher\Drivers\SessionDriver::class,
     * Lykegenes\LocaleSwitcher\Drivers\CookieDriver::class,
     *
     */
    'store_driver' => null,

    /*
     *--------------------------------------------------------------------------
     * LocaleSwitcher Driver Key
     *--------------------------------------------------------------------------
     *
     * This key will be used by all the included drivers to detect and store
     * the locale across requests.
     *
     */
    'default_key' => 'locale',

    /*
     *--------------------------------------------------------------------------
     * Enabled Locales
     *--------------------------------------------------------------------------
     *
     * Specify the locales you want to enable in your app.
     * Set to null to allow ANY locale
     *
     */
    'enabled_locales' => [
        'en' => 'English',
        'fr' => 'Français',
    ],

];
