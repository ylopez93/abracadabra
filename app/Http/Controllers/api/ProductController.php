<?php

namespace App\Http\Controllers\api;

use App\Product;
use App\ProductImage;
use App\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductPost;
use App\Http\Controllers\api\ApiResponseController;

class ProductController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //para que el join devuelva todos los productos ninguno de los campos relacionados en tablas externas debe ser null
        $products = Product::
        join('product_categories','product_categories.id','=','products.product_category_id')->
        join('product_images','product_images.product_image_id','=','products.id')->
        select('products.*','product_categories.id as product_category_id','product_categories.name as category','product_images.name as image')->
        orderBy('products.created_at','desc')->paginate(10);
        return $this->successResponse(['products'=>$products,'message'=>'Products retrieved successfully.']);

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
               $product->description = $request['description'];
               $product->stock = $request['stock'];
               $product->price = $request['price'];
               $product->discount_percent = $request['discount_percent'];
               $product->state = $request['state'];
               $product->product_category_id = $request['product_category_id'];
               $product->save();

               $filename = time() .".". $request->image->extension();
               $request->image->move(public_path('images'),$filename);
               $productimage = new ProductImage();
               $productimage->name = $filename;
               $productimage->product_image_id = $product->id;
               $productimage->save();

            return $this->successResponse([ 'data'=>$product,'image' => $productimage,'message' =>'Product created successfully.']);

            }
            return $this->successResponse(['message' => 'Error al validar']);



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
            return $this->successResponse(['message'=>'Product not found.']);
        }

        return $this->successResponse(['product'=>$product,'message'=>'Product retrieved successfully.']);

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
        $product->description = $request['description'];
        $product->stock = $request['stock'];
        $product->price = $request['price'];
        $product->discount_percent = $request['discount_percent'];
        $product->state = $request['state'];
        $product->product_category_id = $request['product_category_id'];
        $product->save();

        $productimage = ProductImage::select('select * from product_images where product_images.product_image_id = ?', [$product->id]);

        if ($request->hasFile('image')) {

            $filename  = time() .".". $request->image->extension();
            $request->image->move(public_path('images'),$filename);
            $productimage->name = $filename;
            $productimage->product_image_id = $request['id'];
            $productimage = DB::update('update product_images set name = ?, product_image_id = ? where product_image_id = ?', [$filename,$request['id'],$product->id]);

        }

        return $this->successResponse(['product'=>$product, 'message'=>'Product updated successfully.']);
        }
        return $this->successResponse(['message' => 'Error al validar']);


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
        return $this->successResponse(['message'=>'Product deleted successfully.']);
    }






}
