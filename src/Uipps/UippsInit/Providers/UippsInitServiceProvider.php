<?php

namespace Uipps\UippsInit\Providers;

use Illuminate\Support\ServiceProvider;
use Uipps\UippsInit\Commands\UippsInitCommand;

class UippsInitServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                UippsInitCommand::class,
            ]);
        }
    }
}
