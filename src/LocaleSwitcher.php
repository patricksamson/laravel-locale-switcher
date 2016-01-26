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
     * The detected locale.
     *
     * @var string|null
     */
    protected $locale = null;

    /**
     * Create a new locale switcher.
     */
    public function __construct(Application $app, ConfigManager $config)
    {
        $this->app = $app;
        $this->config = $config;
    }

    /**
     * Attempt to detected the new locale from the enabled drivers.
     *
     * @return string|null The locale that should now be used.
     */
    public function detectLocale()
    {
        $sourceDrivers = $this->config->getSourceDrivers();
        $key = $this->config->getDefaultKey();

        if (! empty($sourceDrivers) && is_string($key)) {
            foreach ($sourceDrivers as $driver) {
                $driver = class_exists($driver) ? $this->app->make($driver) : null;

                if ($driver instanceof BaseDriver && $driver->has($key)) {
                    $newLocale = $driver->get($key);
                    if ($this->config->isEnabledLocale($newLocale)) {
                        $this->locale = $newLocale;

                        return $newLocale;
                    }
                }
            }
        }

        return;
    }

    public function storeLocale()
    {
        $storeDriver = $this->config->getStoreDriver();
        $key = $this->config->getDefaultKey();

        if ($this->locale !== null && ! empty($storeDriver)) {
            $storeDriver = $this->app->make($storeDriver);

            if ($storeDriver instanceof BaseDriver) {
                $storeDriver->store($key, $this->locale);

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
