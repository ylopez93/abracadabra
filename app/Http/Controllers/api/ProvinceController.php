<?php

namespace App\Http\Controllers\api;

use App\Province;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProvincePost;
use App\Http\Controllers\api\ApiResponseController;

class ProvinceController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $provinces = Province::all();
        return $this->successResponse([$provinces,'Province retrieved successfully.']);
    }

    public function provincesMunicipies(Province $province)
    {

        return $this->successResponse(["province"=> $province,"municipie"=> $province->municipie()->paginate(10)]);
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
        $v_province = new StoreProvincePost();
        $validator = $request->validate($v_province->rules());
        if($validator){
           $province = new Province();
           $province->name = $request['name'];
           $province->country_id = $request['country_id'];
           $province->save();

        // $product = Product::create($request);
        return $this->successResponse([$province, 'Province created successfully.']);

        }
        return $this->successResponse(['Error al validar']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Province  $province
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $province = Province::find($id);

        if(is_null($province)){
            return $this->successResponse('Province  not found.');
        }

        return $this->successResponse([$province,'Province retrieved successfully.']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Province  $province
     * @return \Illuminate\Http\Response
     */
    public function edit(Province $province)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Province  $province
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Province $province)
    {
        $v_province = new StoreProvincePost();
        $validator = $request->validate($v_province->rules());
        if($validator){
            $province = new Province();
            $province->name = $request['name'];
            $province->country_id = $request['country_id'];
            $province->save();

            return $this->successResponse([$province, 'Province update successfully.']);
        }
        return $this->successResponse(['Error al validar']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Province  $province
     * @return \Illuminate\Http\Response
     */
    public function destroy(Province $province)
    {
        $province->delete();
        return $this->successResponse('Province deleted successfully.');
    }
}
