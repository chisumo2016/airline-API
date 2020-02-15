<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{

    public  function arrivalAirport()
    {
        return $this->belongsTo(Airport::class, 'arrivalAirport_id');
    }


    public  function departureAirport()
    {
        return $this->belongsTo(Airport::class, 'departureAirport_id');
    }

    public  function  passengers()
    {
        return $this->belongsToMany(Customer::class, 'customer_flight');
    }
}
