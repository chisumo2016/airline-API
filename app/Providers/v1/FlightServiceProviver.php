<?php

namespace App\Providers\v1;

use App\Services\V1\FlightService;
use Illuminate\Support\ServiceProvider;

class FlightServiceProviver extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(FlightService::class, function ($app){
             return new FlightService();
        });
    }
}
