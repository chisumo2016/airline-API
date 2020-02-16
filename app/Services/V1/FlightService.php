<?php

namespace   App\Services\V1;

use App\Flight;

class  FlightService{

    public  function  getFlights()
    {
        return  $this->filterFlights(Flight::all());
        //return Flight::all();
    }

    public  function  getFlight($flightNumber)
    {
        return $this->filterFlights(Flight::where('flightNumber',$flightNumber )->get());
    }

    protected  function  filterFlights($flights)
    {
        $data = [];

        foreach ($flights as $flight){
            $entry =[
                'flightNumber' => $flight->flightNumber,
                'status'       => $flight->status,
                'href'         =>  route('flights.show',$flight->flightNumber)
                //'href'         =>  route('flights.show', ['id'=>$flight->flightNumber])

            ];

            //Add in data array
            $data[] = $entry;
        }

        return $data;
    }
}
