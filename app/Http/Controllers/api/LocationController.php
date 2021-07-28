<?php

namespace App\Http\Controllers\api;

use App\Location;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLocationPost;
use App\Http\Controllers\api\ApiResponseController;
use App\Http\Requests\StoreLocationPut;

class LocationController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locations = Location::
        select('locations.*')
        ->whereNull('locations.deleted_at')
        ->get();
        return $this->successResponse(['locations'=>$locations,'message'=>'Locations retrieved successfully.']);
    }


    //Cambiar para que devuelva la ultima insercion hecha que corresponda a order_code pasado por el request

    public function locationsOrder(Request $request)
    {
         $modulo =  Str::contains($request['order_code'],'ABRAME');
            if($modulo){
                $locations = Location::
                select('locations.*')
                ->where('locations.order_code',[$request['order_code']])
                ->whereNull('locations.deleted_at')
                ->latest()->take(1)
                ->get();

                return $this->successResponse(['locations'=>$locations, 'message'=>'Locations retrieved successfully.']);
            }
            elseif ($modulo =  Str::contains($request['order_code'],'ABRAEXPRESS')) {
                $locations = Location::
                select('locations.*')
                ->where('locations.order_code',[$request['order_code']])
                ->whereNull('locations.deleted_at')
                ->latest()->take(1)
                ->get();
                return $this->successResponse(['locations'=>$locations, 'message'=>'Locations retrieved successfully.']);
            }
            elseif ($modulo =  Str::contains($request['order_code'],'ABRAMOTOTAXI')){
                $locations = Location::
                select('locations.*')
                ->where('locations.order_code',[$request['order_code']])
                ->whereNull('locations.deleted_at')
                ->latest()->take(1)
                ->get();
                return $this->successResponse(['locations'=>$locations, 'message'=>'Locations retrieved successfully.']);
            }

        return $this->successResponse(['message'=>'No existen localizaciones pertenecientes a ese codigo de orden']);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            $v_location = new StoreLocationPost();
        $validator = $request->validate($v_location->rules());
        if($validator){
           $location = new Location();
           $location->latitude = $request['latitude'];
           $location->longitude = $request['longitude'];
           $location->order_code = $request['order_code'];
           $location->save();

        return $this->successResponse(['location'=>$location,'message'=> 'Location created successfully.']);

        }
        return $this->successResponse(['message'=>'Error al validar']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function show(Location $location)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function edit(Location $location)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Location $location)
    {
        $v_location = new StoreLocationPut();
        $validator = $request->validate($v_location->rules());
        if($validator){
            $location->latitude = $request['latitude'];
            $location->longitude = $request['longitude'];
            $location->save();

            return $this->successResponse(['location'=>$location,'message'=> 'Location update successfully.']);
        }
        return $this->successResponse(['message'=>'Error al validar']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(Location $location)
    {
        $location->delete();
        return $this->successResponse(['message'=>'Location deleted successfully.']);
    }




}
