<?php

namespace Lykegenes\LocaleSwitcher;

use Illuminate\Foundation\Application;
use Lykegenes\LocaleSwitcher\Drivers\BaseDriver;

class LocaleSwitcher
{
    protected $app;
    /**
     * The current LocaleSwitcher config.
     *
     * @var \Lykegenes\LocaleSwitcher\ConfigManager
     */
    protected $config;

    /**
     * The detected locale
     *
     * @var string|null
     */
    protected $locale = null;

    /**
     * Create a new locale switcher.
     *
     */
    public function __construct(Application $app, ConfigManager $config)
    {
        $this->app = $app;
        $this->config = $config;
    }

    /**
     * Get an array of all the enabled locales.
     *
     * @return array
     */
    public function getEnabledLocales()
    {
        return $this->config->getEnabledLocales();
    }

    /**
     * Attempt to detected the new locale from the enabled drivers.
     *
     * @return string|null The locale that should now be used.
     */
    public function detectLocale()
    {
        $sourceDrivers = $this->config->getSourceDrivers();

        foreach ($sourceDrivers as $driver) {
            $driver = $this->app->make($driver);

            if ($driver instanceof BaseDriver && $driver->has()) {
                $newLocale = $driver->get();
                if ($this->config->isEnabledLocale($newLocale)) {
                    $this->locale = $newLocale;

                    return $newLocale;
                }
            }
        }

        return;
    }

    public function storeLocale()
    {
        if ($this->locale !== null) {
            $storeDriver = $this->config->getStoreDriver();
            $storeDriver = $this->app->make($storeDriver);
            if ($storeDriver instanceof BaseDriver) {
                $storeDriver->store($storeDriver::DEFAULT_KEY, $this->locale);

                return $this->locale;
            }
        }

        return;
    }

    /**
     * Attempt to detect and switch the current locale.
     *
     * @return string|null The detected locale or null
     */
    public function setAppLocale()
    {
        // Detect the current locale from enabled drivers
        $this->detectLocale();

        // Store the current locale for future requests
        $this->storeLocale();

        // Set the locale for this current request
        if ($this->locale !== null) {
            $this->app->setLocale($this->locale);
        }

        return $this->locale;
    }
};
