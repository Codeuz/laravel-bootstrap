<?php

namespace Cdz\Bootstrap;

use Illuminate\Support\ServiceProvider;
use Cdz\Bootstrap\Console\BootstrapInstall;


class BootstrapServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    }
	
	/**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    	$this->registerCommands();
    }
	
	/**
     * Register the commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        $this->commands([
            BootstrapInstall::class
        ]);
    }
	
	/**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            BootstrapInstall::class
        ];
    }

    
}
