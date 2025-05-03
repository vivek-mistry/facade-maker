<?php

namespace VivekMistry\FacadeMaker;

use Illuminate\Support\ServiceProvider;
use VivekMistry\FacadeMaker\Commands\FacadeDeveloper;

class FacadeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                FacadeDeveloper::class,
            ]);
        }
    }

    public function register()
    {
        //
    }
}