<?php

namespace Lykegenes\LocaleSwitcher\Drivers;

use Illuminate\Http\Request;
use Lykegenes\LocaleSwitcher\ConfigManager;

class BrowserDriver extends BaseDriver
{
    /**
     * @var Illuminate\Http\Request
     */
    protected $request;

    /**
     * @var Lykegenes\LocaleSwitcher\ConfigManager
     */
    protected $configManager;

    public function __construct(Request $request, ConfigManager $configManager)
    {
        $this->request = $request;
        $this->configManager = $configManager;
    }

    public function has($key = 'Accept-Language')
    {
        return null !== $this->request->header($key, null);
    }

    public function get($key = 'Accept-Language', $default = null)
    {
        $header = $this->request->header($key, $default);

        $locales = $this->parseAcceptLanguage($header);

        return $this->selectPreferredLocale($locales);
    }

    protected function parseAcceptLanguage($acceptLanguage)
    {
        $locales = [];
        $languages = explode(',', $acceptLanguage);

        foreach ($languages as $item) {
            $split = explode(';', $item);
            $locales[] = [
                'locale' => $split[0],
                'q' => (array_key_exists(1, $split) && substr($split[1], 0, 2) === 'q=') ? (float) substr($split[1], 2) : 1.0,
            ];
        }

        // Sort in place by desc priority ("q")
        usort($locales, function ($a, $b) {
            return ($a['q'] > $b['q']) ? -1 : 1;
        });

        return $locales;
    }

    protected function selectPreferredLocale($locales)
    {
        foreach ($locales as $locale) {
            if ($this->configManager->isEnabledLocale($locale['locale'])) {
                return $locale['locale'];
            }
        }
    }
}
