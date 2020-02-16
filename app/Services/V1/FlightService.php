<?php

namespace   App\Services\V1;

use App\Flight;

class  FlightService{

    public  function  getFlights()
    {
        return Flight::all();
    }
}
