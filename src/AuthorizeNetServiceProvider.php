<?php namespace ANet;

use Illuminate\Support\ServiceProvider;

class AuthorizeNetServiceProvider extends ServiceProvider
{

    /**
     * @return void
     */
    public function boot() {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->setupConfig();
    }

    /**
     * It will setup configuration file
     * @return void
     */
    public function setupConfig():void
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

    public function register() {}



}