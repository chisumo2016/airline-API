<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Services\V1\FlightService;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    protected  $flights;

    public  function __construct(FlightService $service)
    {
        $this->flights = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parameters = request()->input();
        //call  service
        $data  = $this->flights->getFlights($parameters);
        //return data
        return  response()->json($data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //$arrivalDateTime = $request->input(['arrival.datetime']);
        try {
            $flight = $this->flights->createFlight($request);

            return response()->json($flight,201);
        }catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $parameters = request()->input();
        $parameters['flightNumber'] = $id;
        //call  service
        //$data  = $this->flights->getFlights($id);
        $data  = $this->flights->getFlights($parameters);
        return  response()->json($data);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $flight = $this->flights->updateFlight($request, $id);

            return response()->json($flight,200);
        }catch (ModelNotFoundException $ex){
            throw $ex;
        }

        catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $flight = $this->flights->deleteFlight($id);

            return response()->make('', 204);
        }catch (ModelNotFoundException $ex){
            throw $ex;
        }

        catch (Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
