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

    public function categoryProduct(ProductCategory $category)
    {

        return $this->successResponse(["category"=> $category,"product"=> $category->product()->paginate(10)]);
    }


    public function categoryProductAll()

    {
        //preguntarle a roilan que datos espscificos quiere que sean devueltos en la query
        $categories = ProductCategory::
        join('products','products.product_category_id','=','product_categories.id')->
        select('product_categories.name','product_categories.id_module','product_categories.image','products.name as product',
        'products.code','products.description','products.stock','products.price','products.discount_percent')->
        orderBy('product_categories.name','desc')->paginate(10);
        return $this->successResponse([$categories,'Products retrieved successfully.']);
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
           $productcategory->module = $request['module'];
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
        $category->module = $request['module'];
        if ($request->hasFile('file')) {

            $filename = time() .".". $request->image->extension();
            $request->image->move(public_path('images'),$filename);
            $category->image = $filename;

        }

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
