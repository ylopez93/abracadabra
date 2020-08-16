<?php

namespace App\Http\Controllers\api;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\api\ApiResponseController;
use App\Http\Requests\StoreProductPost;
use App\ProductCategory;

class ProductController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        return $this->successResponse([$products,'Products retrieved successfully.']);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //creacion de cruds create
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

            $v_product = new StoreProductPost();
            $validator = $request->validate($v_product->rules());
            if($validator){
               $product = new Product();
               $product->name = $request['name'];
               //buscar funcion para generar codigo
               $product->code = $request['code'];
               $product->description = $request['description'];
               $product->stock = $request['stock'];
               $product->price = $request['price'];
               $product->discount_percent = $request['discount_percent'];
               $product->state = 'published';
               $product->product_category_id = $request['product_category_id'];
               $product->save();

            // $product = Product::create($request);
            return $this->successResponse([$product, 'Product created successfully.']);

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
        $product = Product::find($id);

        if(is_null($product)){
            return $this->errorResponse('Product not found.');
        }

        return $this->successResponse([$product,'Product retrieved successfully.']);

    }


    public function categoryProduct(ProductCategory $category)
    {

        return $this->successResponse(["category"=> $category,"product"=> $category->product()->paginate(10)]);
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
    public function update(Request $request, Product $product)
    {
        $v_product = new StoreProductPost();
        $validator = $request->validate($v_product->rules());
        if($validator){
        $product->name = $request['name'];
        $product->code = $request['code'];
        $product->description = $request['description'];
        $product->stock = $request['stock'];
        $product->price = $request['price'];
        $product->discount_percent = $request['discount_percent'];
        $product->state = $request['state'];
        $product->product_category_id = $request['product_category_id'];
        $product->save();
        return $this->successResponse([$product, 'Product updated successfully.']);
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
    public function destroy(Product $product)
    {
        $product->delete();
        return $this->successResponse('Product deleted successfully.');
    }




}
