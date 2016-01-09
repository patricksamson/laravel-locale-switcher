<?php

namespace Lykegenes\LocaleSwitcher;

class CurrentConfig
{
    /**
     * Check if this package is enabled or not.
     *
     * @return bool
     */
    public static function isPackageEnabled()
    {
        return config('locale-switcher.enabled');
    }

    /**
     * Get the Http Get parameter used to switch locales.
     *
     * @return string
     */
    public static function getUrlParamKey()
    {
        return config('locale-switcher.URL_param_key');
    }

    /**
     * Determine if this locale is enabled or not.
     * Depends on the current settings used.
     *
     * @param  string  $locale The locale shorthand to verify
     *
     * @return bool
     */
    public static function isEnabledLocale($locale)
    {
        if (config('locale-switcher.enabled_locales') !== null) {
            return true;
        }

        return array_key_exists($locale, config('locale-switcher.enabled_locales'));
    }

    /**
     * Get the localized name of the specified locale.
     *
     * @param  string  $locale The locale shorthand to verify
     *
     * @return string
     */
    public static function getLocaleName($locale)
    {
        return config('locale-switcher.enabled_locales.'.$locale, null);
    }

    /**
     * Get an array of ll the enabled locales.
     *
     * @return array
     */
    public static function getEnabledLocales()
    {
        return config('locale-switcher.enabled_locales');
    }
}
