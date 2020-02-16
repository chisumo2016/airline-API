<?php

namespace   App\Services\V1;

use App\Airport;
use App\Flight;

class  FlightService{

    protected  $supportedIncludes = [
        'arrivalAirport'    => 'arrival',  //from flight model
        'departureAirport'  => 'departure'
    ];
    protected   $clauseProperties = [
        // user key and value 'arrivalDateTime'  => 'arrival.datetime'
        'status',
        'FlightNumber',
    ];
    public  function  getFlights($parameters)
    {
        //check if we have
        if (empty( $parameters)){
            return  $this->filterFlights(Flight::all());
        }

        //Refactor the code
        $withKeys = $this->getWithKeys($parameters);

        //from query
        $whereClauses = $this->getWhereClause($parameters);

        $flights = Flight::with($withKeys)->where($whereClauses)->get();
        return $this->filterFlights($flights, $withKeys);
        //return Flight::all();
    }

//    public  function  getFlight($flightNumber)
//    {
//        return $this->filterFlights(Flight::where('flightNumber',$flightNumber )->get());
//    }

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

    public  function createFlight($request)
    {
        $arrivalAirport     = $request->input('arrival.iataCode');
        $departureAirport   = $request->input('departure.iataCode');

        $airports = Airport::whereIn('iataCode',[$arrivalAirport ,  $departureAirport ])->get();
        $codes = [];

        foreach ( $airports as  $port) {
            $codes[$port->iataCode] = $port->id;
        }

        $flight = new Flight();

        $flight->flightNumber           = $request->input('flightNumber');
        $flight->status                 = $request->input('status');

        $flight->arrivalAirport_id      = $codes[$arrivalAirport];
        $flight->arrivalDateTime        = $request->input('arrival.datetime');

        $flight->departureAirport_id    = $codes[$departureAirport];
        $flight->departureDateTime      = $request->input('departure.datetime');

        $flight->save();

        return  $this->filterFlights([$flight]);
    }

    public  function updateFlight($request, $flightNumber)
    {
        $flight =Flight::where('flightNumber', $flightNumber)->firstOrFail();
        $arrivalAirport     = $request->input('arrival.iataCode');
        $departureAirport   = $request->input('departure.iataCode');

        $airports = Airport::whereIn('iataCode',[$arrivalAirport ,  $departureAirport ])->get();
        $codes = [];

        foreach ( $airports as  $port) {
            $codes[$port->iataCode] = $port->id;
        }

        $flight->flightNumber           = $request->input('flightNumber');
        $flight->status                 = $request->input('status');

        $flight->arrivalAirport_id      = $codes[$arrivalAirport];
        $flight->arrivalDateTime        = $request->input('arrival.datetime');

        $flight->departureAirport_id    = $codes[$departureAirport];
        $flight->departureDateTime      = $request->input('departure.datetime');

        $flight->save();

        return  $this->filterFlights([$flight]);
    }

    public  function deleteFlight($flightNumber)
    {
        $flight =Flight::where('flightNumber', $flightNumber)->firstOrFail();
        $flight->delete();

    }


    protected  function  getWithKeys($parameters)
    {
        $withKeys = [];

        if (isset( $parameters['include'])){
            $includeParams = explode(',', $parameters['include']);
            $includes = array_intersect($this->supportedIncludes, $includeParams);
            $withKeys = array_keys($includes);
        }

        return $withKeys;
    }

    protected  function  getWhereClause($parameters)
    {
        $clause = [];

        foreach ($this->clauseProperties as $prop) {
            if (in_array($prop, array_keys($parameters))){
                //add key
                $clause[$prop]  = $parameters[$prop];
            }
        }
        return $clause;
    }
}
