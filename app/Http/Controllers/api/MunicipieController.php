<?php

namespace App\Http\Controllers\api;

use App\Municipie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\api\ApiResponseController;
use App\Http\Requests\StoreMunicipiePost;

class MunicipieController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $municipies = Municipie::all();
        return $this->successResponse([$municipies,'Municipie retrieved successfully.']);
    }

    public function municipieUsers(Municipie $municipie)
    {

        return $this->successResponse(["municipie"=> $municipie,"user"=> $municipie->user()->paginate(10)]);
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
        $v_municipie = new StoreMunicipiePost();
        $validator = $request->validate($v_municipie->rules());
        if($validator){
           $municipie = new Municipie();
           $municipie->name = $request['name'];
           $municipie->price = $request['price'];
           $municipie->province_id = $request['province_id'];
           $municipie->save();

        // $product = Product::create($request);
        return $this->successResponse([$municipie, 'Municipie created successfully.']);

        }
        return response()->json([
            'message' => 'Error al validar'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Municipie  $municipie
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $municipie = Municipie::find($id);

        if(is_null($municipie)){
            return $this->successResponse('Municipie  not found.');
        }

        return $this->successResponse([$municipie,'Municipie retrieved successfully.']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Municipie  $municipie
     * @return \Illuminate\Http\Response
     */
    public function edit(Municipie $municipie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Municipie  $municipie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Municipie $municipie)
    {
        $v_municipie = new StoreMunicipiePost();
        $validator = $request->validate($v_municipie->rules());
        if($validator){
        $municipie->name = $request['name'];
        $municipie->price = $request['price'];
        $municipie->province_id = $request['province_id'];
        $municipie->save();
        return $this->successResponse([$municipie, 'Municipie updated successfully.']);
        }
        return response()->json([
            'message' => 'Error al validar'
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Municipie  $municipie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Municipie $municipie)
    {
        $municipie->delete();
        return $this->successResponse('Municipie deleted successfully.');
    }
}
