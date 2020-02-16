<?php

namespace   App\Services\V1;

use App\Flight;

class  FlightService{

    protected  $supportedIncludes = [
        'arrivalAirport'    => 'arrival',  //from flight model
        'departureAirport'  => 'departure'
    ];

    public  function  getFlights($parameters)
    {
        //check if we have
        if (empty( $parameters)){
            return  $this->filterFlights(Flight::all());
        }

        $withKeys =[];

        if (isset( $parameters['include'])){
            $includeParams = explode(',', $parameters['include']);
            $includes = array_intersect($this->supportedIncludes, $includeParams);
            $withKeys = array_keys($includes);
        }
        return $this->filterFlights(Flight::with($withKeys)->get(), $withKeys);
        //return Flight::all();
    }

    public  function  getFlight($flightNumber)
    {
        return $this->filterFlights(Flight::where('flightNumber',$flightNumber )->get());
    }

    protected  function  filterFlights($flights, $keys = [])
    {
        $data = [];

        foreach ($flights as $flight){
            $entry =[
                'flightNumber' => $flight->flightNumber,
                'status'       => $flight->status,
                'href'         =>  route('flights.show',$flight->flightNumber)
                //'href'         =>  route('flights.show', ['id'=>$flight->flightNumber])

            ];

            if (in_array('arrivalAirport', $keys)){
                $entry['arrival'] =[
                    'datetime'   => $flight->arrivalDateTime,
                    'iataCode'   => $flight->arrivalAirport->iataCode,
                    'city'       => $flight->arrivalAirport->city,
                    'state'       => $flight->arrivalAirport->state,
                    'country'    => $flight->arrivalAirport->country,
                ];
            }

            if (in_array('departureAirport', $keys)){
                $entry['departure'] =[
                    'datetime'   => $flight->departureDateTime,
                    'iataCode'   => $flight->departureAirport->iataCode,
                    'city'       => $flight->departureAirport->city,
                    'state'       => $flight->departureAirport->state,
                    'country'    => $flight->departureAirport->country,
                ];
            }

            //Add in data array
            $data[] = $entry;
        }

        return $data;
    }
}
