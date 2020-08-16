<?php

namespace App\Http\Controllers\api;

use App\ProductCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryProduct;
use App\Http\Controllers\api\ApiResponseController;

class ProductCategoryController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productCategories = ProductCategory::all();
        return $this->successResponse([$productCategories,'Products Categories retrieved successfully.']);
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
        $v_productcategory = new StoreCategoryProduct();
        $validator = $request->validate($v_productcategory->rules());
        if($validator){
           $productcategory = new ProductCategory();
           $productcategory->name = $request['name'];
           //insertar image
           $productcategory->image = $request['image'];
           $productcategory->save();

        // $product = Product::create($request);
        return $this->successResponse([$productcategory, 'Product Category created successfully.']);

        }
        return response()->json([
            'message' => 'Error al validar'
        ], 201);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $productcategory = ProductCategory::find($id);

        if(is_null($productcategory)){
            return $this->errorResponse('Product Category not found.');
        }

        return $this->successResponse([$productcategory,'ProductProduct Category retrieved successfully.']);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductCategory $category)
    {
        $v_productcategory = new StoreCategoryProduct();
        $validator = $request->validate($v_productcategory->rules());
        if($validator){
        $category->name = $request['name'];
        $category->image = $request['image'];
        $category->save();
        return $this->successResponse([$category, 'Product Category updated successfully.']);
        }
        return response()->json([
            'message' => 'Error al validar'
        ], 201);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( ProductCategory $category)
    {
        $category->delete();
        return $this->successResponse('Product Category deleted successfully.');
    }
}
