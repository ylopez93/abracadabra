<?php

namespace App\Http\Controllers;

use App\Product;
use Cart;
use App\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\api\ApiResponseController;

class CartController extends ApiResponseController
{
    //metodo para mostrar el contenido del shoppingcart

    public function index(){

        // $cartItems = Cart::content();
        // return $this->successResponse([$cartItems,'Content CartShopping retrieved successfully.']);
    }

    //metodo para agregar un producto al shoppingcart, pasandole por parametros el id del producto

    public function addItem( Request $request){

        $userId = auth()->user()->id;
        $product = Product::findOrFail($request->product_id);
        $imageProduct = DB::select('select product_images.name from product_images where product_images.product_image_id = ?', [$request->product_id]);
        $image = $imageProduct[0]->name;

        // add the product to cart
        $sessionCart = Cart::session($userId)->add(array(
            'id' => $product->id, // inique row ID
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $product->stock,
            'attributes' => array(
                'image'=> $image,
                'description'=> $product->description,
                'discount_percent'=> $product->discount_percent,
            ),
            'associatedModel' => 'Product'
       ));

        if($sessionCart != null){

            $count = count(Cart::session($userId)->getContent());
            return $this->successResponse(['count'=>$count,'Content CartShopping created successfully.']);
        }

        return $this->errorResponse(['message' => 'Error no created cartshopping']);

    }

    public function add(Request $request){

        $product = Product::find($request->product_id);
        //$imageProduct = DB::select('select product_images.name from product_images where product_images.product_image_id = ?', [$request->product_id]);
        //$image = $imageProduct[0]->name;

        Cart::add(array(
            'id' => $product->id, // inique row ID
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $product->stock,
            'attributes' => array(
                //'image'=> $image,
                'description'=> $product->description,
                'discount_percent'=> $product->discount_percent,
            ),
            'associatedModel' => 'Product'
       ));

       return back()->with('success',"$product->name se ha agregado con exito al carrito");

    }

    public function cart(){
        $params = [
            'title'=> 'Shopping Cart Checkout',
        ];
        return view('checkout')->with($params);
    }


    // //los parametros son id del shoppingcart y en el request (productId,qly->q es la cantidad que se aumento)

    // public function update(Request $request,$id){

    //     $qty = $request->qty;
    //     $productId = $request->productId;
    //     $product = Product::findOrFail($productId);
    //     $stock = $product->stock;

    //     if($qty<$stock){

    //        $cart = Cart::update($id,$request->qty);
    //         return $this->successResponse([$cart,'CartShopping is update successfully.']);
    //     }else{
    //         return $this->errorResponse(['Please check your qty is more than product stock']);
    //     }

    // }

}


