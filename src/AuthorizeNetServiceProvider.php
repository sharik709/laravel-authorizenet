<?php namespace ANet;

use Illuminate\Support\ServiceProvider;

class AuthorizeNetServiceProvider extends ServiceProvider
{


    public function boot() {
        $this->publishes([
            __DIR__.'/Config/authorizenet.php' => config_path('authorizenet.php')
        ], 'authorizenet');
    }

    public function register() {
        $this->mergeConfigFrom(
            __DIR__.'/Config/authorizenet.php',
            'authorizenet'
        );
    }



}