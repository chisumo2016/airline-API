<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    public  function  arrivingFlights()
    {
        return $this->hasMany(Flight::class, 'arrivalAirport_id');
    }

    public  function  departingFlights()
    {
        return $this->hasMany(Flight::class, 'departureAirport_id');
    }
}
