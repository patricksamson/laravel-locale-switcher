<?php namespace Lykegenes\LaravelLocaleSwitcher;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $configPath = __DIR__ . '/../config/config.php';
        $this->mergeConfigFrom($configPath, 'locale-switcher');
    }

    public function boot()
    {
        $configPath = __DIR__ . '/../config/config.php';
        $this->publishes([$configPath => $this->getConfigPath()], 'config');

        $enabled = $this->app['config']->get('locale-switcher.enabled');
        if (!$enabled)
        {
            return;
        }

        $this->registerMiddleware('Lykegenes\LaravelLocaleSwitcher\Middleware\SwitchLocale');
    }

    /**
     * @return string[]
     */
    public function provides()
    {
        return ['locale-switcher'];
    }

    /**
     * Get the config path
     *
     * @return string
     */
    protected function getConfigPath()
    {
        return config_path('locale-switcher.php');
    }

    /**
     * Publish the config file
     *
     * @param  string $configPath
     */
    protected function publishConfig($configPath)
    {
        $this->publishes([$configPath => config_path('locale-switcher.php')], 'config');
    }

    /**
     * Register the Debugbar Middleware
     *
     * @param  string $middleware
     */
    protected function registerMiddleware($middleware)
    {
        $kernel = $this->app['Illuminate\Contracts\Http\Kernel'];
        $kernel->pushMiddleware($middleware);
    }

}
