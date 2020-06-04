<?php namespace ANet;

use Illuminate\Support\ServiceProvider;

class AuthorizeNetServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        $this->loadMigrations();
        $this->setupConfig();
    }

    /**
     * @return void
     */
    public function loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }

    /**
     * It will setup configuration file
     * @return void
     */
    public function setupConfig()
    {
        $configLocation = __DIR__.'/Config/authorizenet.php';

        $this->publishes([
            $configLocation => config_path('authorizenet.php')
        ], 'authorizenet');

        $this->mergeConfigFrom(
            $configLocation,
            'authorizenet'
        );
    }

    /**
     * @return void
     */
    public function register()
    {}



}
