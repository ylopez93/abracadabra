<?php

namespace App\Http\Controllers\api;

use App\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        return $this->successResponse(['productCategories'=>$productCategories,'message'=>'Products Categories retrieved successfully.']);
    }

    public function categoryProduct(ProductCategory $category)
    {

        return $this->successResponse(["category"=> $category,"product"=> $category->product()->paginate(10)]);
    }


    public function categoryProductAll()
    {
        $categories = ProductCategory::
        join('products','products.product_category_id','=','product_categories.id')->
        select('product_categories.id as category_id','product_categories.name as category',
        'product_categories.module','product_categories.image','products.*')->
        orderBy('product_categories.name','desc')->paginate(10);
        return $this->successResponse(['categories'=>$categories,'message'=>'Products retrieved successfully.']);
    }


    public function getCategoryModule($module){

        $categories = DB::select('select * from product_categories where product_categories.module = ?', [$module]);

        return $this->successResponse(['categories'=>$categories,'message'=>'Categories retrieved successfully.']);
    }


    public function byCategoryProductAll($id)
    {
        $category = ProductCategory::findOrFail($id);

            $categoryProducts = ProductCategory::
                 join('products','products.product_category_id','=','product_categories.id')
                ->join('product_images','product_images.product_image_id','=','products.id')
                ->select('product_categories.name as category','product_categories.module',
                'products.*','product_images.name as image')
                ->where('product_categories.name',$category->name)
                ->where('products.state', '=', 'published')
                ->whereNull('products.deleted_at')
                ->get();

        return $this->successResponse(['categoryProducts'=>$categoryProducts,'message'=>'Products retrieved successfully.']);
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

           if ($request->hasFile('image')) {
           $filename = time() .".". $request->image->extension();
           $request->image->move(public_path('images'),$filename);
           $productcategory->image = $filename;

           }

           $productcategory->save();

        return $this->successResponse(['productcategory'=>$productcategory, 'message'=>'Product Category created successfully.']);

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
            return $this->successResponse(['message'=>'Product Category not found.']);
        }

        return $this->successResponse(['productcategory'=>$productcategory,'message'=>'ProductProduct Category retrieved successfully.']);

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
        if ($request->hasFile('image')) {

            $filename = time() .".". $request->image->extension();
            $request->image->move(public_path('images'),$filename);
            $category->image = $filename;

        }

        $category->save();

        return $this->successResponse(['category'=>$category,'message'=> 'Product Category updated successfully.']);
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
        return $this->successResponse(['message'=>'Product Category deleted successfully.']);
    }
}