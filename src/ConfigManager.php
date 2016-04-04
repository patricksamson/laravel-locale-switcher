<?php

namespace Lykegenes\LocaleSwitcher;

class ConfigManager
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

    public static function getSourceDrivers()
    {
        return config('locale-switcher.source_drivers', []);
    }

    public static function getStoreDriver()
    {
        return config('locale-switcher.store_driver');
    }

    public static function getDefaultKey()
    {
        return config('locale-switcher.default_key');
    }

    /**
     * Determine if this locale is enabled or not.
     * Depends on the current settings used.
     *
     * @param string $locale The locale shorthand to verify
     *
     * @return bool
     */
    public static function isEnabledLocale($locale)
    {
        return config('locale-switcher.enabled_locales') !== null
            && array_key_exists($locale, config('locale-switcher.enabled_locales'));
    }

    /**
     * Get the localized name of the specified locale.
     *
     * @param string $locale The locale shorthand to verify
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
