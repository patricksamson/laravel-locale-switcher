<?php

namespace Lykegenes\LocaleSwitcher;

use Lykegenes\LocaleSwitcher\Resources\LocalesList;

class Locales
{
    use LocalesList;

    /**
     * Verifies that we have this locale in LocaleList
     *
     * @param  string $locale The locale shorthand
     *
     * @return bool
     */
    public static function localeExists($locale)
    {
        return array_key_exists($locale, self::$localesList);
    }

    /**
     * Get the human-readable name of this locale
     *
     * @param  string $locale The locale shorthand
     *
     * @return string
     */
    public static function getLanguageNameFromLocale($locale)
    {
        return self::$localesList[$locale];
    }

    /**
     * Checks if it is the primary locale shorthand.
     * "en" is a primary locale.
     * "en-US" and "en-GB" are not; they're regional.
     *
     * @param  string $locale The locale shorthand
     *
     * @return bool
     */
    public static function localeIsPrimary($locale)
    {
        return  ! self::localeIsRegional($locale);
    }

    /**
     * Checks if it is a regional locale shorthand.
     * "en" is a primary locale.
     * "en-US" and "en-GB" are regional locales.
     *
     * @param  string $locale The locale shorthand
     *
     * @return bool
     */
    public static function localeIsRegional($locale)
    {
        return strpos($locale, '-') !== false;
    }

    /**
     * Returns the primary locale of a regional locale
     * "en" will return "en".
     * "en-US" and "en-GB" will both return "en".
     *
     * @param  string $locale The locale shorthand
     *
     * @return string         The primary locale
     */
    public static function regionalToPrimaryLocale($locale)
    {
        return explode('-', $locale)[0];
    }
}
