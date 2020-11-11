<?php

namespace App\Http\Controllers\api;

use App\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCountryPost;
use App\Http\Controllers\api\ApiResponseController;

class CountryController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countries = Country::all();
        return $this->successResponse([$countries,'Country retrieved successfully.']);
    }

    public function countryPorvinces(Country $country)
    {

        return $this->successResponse(["country"=> $country,"province"=> $country->province()->paginate(10)]);
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
        $v_country = new StoreCountryPost();
        $validator = $request->validate($v_country->rules());
        if($validator){
           $country = new Country();
           $country->name = $request['name'];
           $country->save();

        // $product = Product::create($request);
        return $this->successResponse([$country, 'Country created successfully.']);

        }
        return response()->json([
            'message' => 'Error al validar'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $country = Country::find($id);

        if(is_null($country)){
            return $this->successResponse('Country  not found.');
        }

        return $this->successResponse([$country,'Country retrieved successfully.']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function edit(Country $country)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Country $country)
    {
        $v_country = new StoreCountryPost();
        $validator = $request->validate($v_country->rules());
        if($validator){
        $country->name = $request['name'];
        $country->save();
        return $this->successResponse([$country, 'Country updated successfully.']);
        }
        return response()->json([
            'message' => 'Error al validar'
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country)
    {
        $country->delete();
        return $this->successResponse('Country deleted successfully.');
    }
}