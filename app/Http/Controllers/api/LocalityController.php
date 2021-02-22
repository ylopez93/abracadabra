<?php

namespace App\Http\Controllers\api;

use App\Locality;
use App\Municipie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLocalityPost;
use App\Http\Controllers\api\ApiResponseController;

class LocalityController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $localities = Locality::all();
        return $this->successResponse([$localities,'Locality retrieved successfully.']);
    }

    public function localitiesMunicipie(Request $request)
    {
        $localities = Locality::
        select('localities.id','localities.name')
        ->join('municipies', 'municipies.id', '=', 'localities.municipie_id')
        ->where('localities.municipie_id',[$request['municipie_id']])
        ->whereNull('localities.deleted_at')
        ->whereNull('municipies.deleted_at')
        ->get();
        return $this->successResponse([$localities, 'Localities retrieved successfully.']);
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
        $v_locality = new StoreLocalityPost();
        $validator = $request->validate($v_locality->rules());
        if($validator){
           $locality = new Locality();
           $locality->name = $request['name'];
           $locality->municipie_id = $request['municipie_id'];
           $locality->save();

        // $product = Product::create($request);
        return $this->successResponse([$locality, 'Locality created successfully.']);

        }
        return response()->json([
            'message' => 'Error al validar'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Locality  $locality
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $locality = Locality::find($id);

        if(is_null($locality)){
            return $this->successResponse('Locality  not found.');
        }

        return $this->successResponse([$locality,'Locality retrieved successfully.']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Locality  $locality
     * @return \Illuminate\Http\Response
     */
    public function edit(Locality $locality)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Locality  $locality
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Locality $locality)
    {
        $v_locality = new StoreLocalityPost();
        $validator = $request->validate($v_locality->rules());
        if($validator){
        $locality->name = $request['name'];
        $locality->municipie_id = $request['municipie_id'];
        $locality->save();
        return $this->successResponse([$locality, 'Locality updated successfully.']);
        }
        return response()->json([
            'message' => 'Error al validar'
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Locality  $locality
     * @return \Illuminate\Http\Response
     */
    public function destroy(Locality $locality)
    {
        $locality->delete();
        return $this->successResponse('Locality deleted successfully.');
    }
}