<?php

namespace App\Providers\v1;

use App\Services\V1\FlightService;
use Illuminate\Support\Facades\Validator;
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
//        Validator::extend('flightstatus',  function ($attribute, $value, $parameters, $validator){
//            return $value == 'ontime' || $value == 'delayed';
//        });
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

