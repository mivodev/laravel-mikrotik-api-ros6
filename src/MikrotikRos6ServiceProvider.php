<?php

declare(strict_types=1);

namespace Mivo\LaravelMikrotikRos6;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Mivo\MikrotikRos6\Client;

class MikrotikRos6ServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/mikrotik-ros6.php',
            'mikrotik-ros6'
        );

        $this->app->singleton('mikrotik.ros6', function (Application $app) {
            return new MikrotikManager($app);
        });

        // Register alias for the Manager
        $this->app->alias('mikrotik.ros6', MikrotikManager::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/mikrotik-ros6.php' => config_path('mikrotik-ros6.php'),
            ], 'mikrotik-ros6-config');
        }
    }
}
