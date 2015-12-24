<?php

return array(
    /**
     *--------------------------------------------------------------------------
     * LocaleSwitcher Settings
     *--------------------------------------------------------------------------
     *
     * LocaleSwitcher is enabled by default.
     *
     */
    'enabled' => true,

    /**
     *--------------------------------------------------------------------------
     * LocaleSwitcher URL parameter key prefix
     *--------------------------------------------------------------------------
     *
     * Sometimes you want to set the URL parameter key to be used by LocaleSwitcher
     * to use to detect locale switching requests.
     * By default, it is : http://my-app.com/some/page/?locale=en
     *
     */
    'URL_param_key' => 'locale',

    /**
     *--------------------------------------------------------------------------
     * Primary and Regional Locales
     *--------------------------------------------------------------------------
     *
     * You can enable or disable regional locales support. (default : false)
     *
     * If regional locales are enabled, 'en', 'en-US' and 'en-GB' will be considered
     * like different locales by your Laravel application. They will also need to
     * be individually enabled in this package (see the "enabled_locales" setting).
     *
     * If regional locales are disabled, 'en-US' and 'en-GB' will automatically be
     * converted to simply 'en'. You'll only have to enable the 'en' locale in this
     * package, and only need the 'en' language in your Laravel translations.
     *
     */
    'enable_regional_locales' => false,

    /**
     *--------------------------------------------------------------------------
     * Enabled Locales
     *--------------------------------------------------------------------------
     *
     * Specify the locales you want to enable in your app.
     * Look here for all the supported locales :
     * https://github.com/Lykegenes/laravel-locale-switcher/blob/master/src/Resources/LocalesList.php
     *
     * Only the locales enabled here will be allowed in you application.
     *
     * Set to Null to disable this feature. ( 'enabled_locales' => null, )
     *
     */
    'enabled_locales' => [
        'en', // If Regional Locales are disabled, you only need 'en'
        //'en-US',  // else, you also need to enable the regions you want.
        //'en-GB',
        //'en-CA',
        'fr',
        //'fr-CA'
    ],

);
