<?php

namespace Leonard133\Laranow;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;
use Laravel\Ui\UiCommand;
use Leonard133\Laranow\Presets\Template1;

class LaranowServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                AuthCommand::class,
                PrintCommand::class,
                PackageCommand::class,
            ]);
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        UiCommand::macro('landing:t1', function () {
            Artisan::call('ui:auth', ['--force' => true]);
            Template1::install();
        });

        UiCommand::macro('admin:t1', function () {
            Artisan::call('ui:auth', ['--force' => true]);
            Template1::install();
        });
    }
}
