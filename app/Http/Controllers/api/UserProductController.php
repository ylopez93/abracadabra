<?php

namespace App\Http\Controllers\api;

use App\Product;
use App\UserProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\api\ApiResponseController;

class UserProductController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getContent($userId)
    {
        $productsCar = DB::select('select products.name as product, user_products.product_id,products.price,
        user_products.total_price,product_images.name as image,user_products.qty,user_products.id,user_products.qty_unit
        from user_products
        INNER JOIN users ON users.id = user_products.user_id
        INNER JOIN products ON products.id = user_products.product_id
        INNER JOIN product_images ON products.id = product_images.product_image_id
        where user_products.user_id = ? AND user_products.deleted_at IS NULL', [$userId]);

        return $this->successResponse([$productsCar, 'Products retrieved successfully.']);
    }

    public function emptyCart(Request $request){

        $compras = UserProduct::where('user_products.user_id',[$request['userId']])->first();
        if ($compras){
            return $this->successResponse(false);
        }
        else{
            return $this->successResponse(true);
        }

    }

    //parametros: idUser
    //agregar producto al carrito

    public function addItem(Request $request)
    {

        $userId = auth()->user()->id;
        $product = Product::findOrFail($request->id);
        $productExist = DB::select('select user_products.product_id from user_products where user_products.deleted_at IS NULL AND user_products.product_id = ?', [$request->id]);

        if ($productExist == null) {
            if($product->stock != 0){

                $itemCart = new UserProduct();
                $itemCart->user_id = $userId;
                $itemCart->product_id = $product->id;
                $itemCart->qty = 1;
                $itemCart->qty_unit = $itemCart->qty_unit + $itemCart->qty;
                $itemCart->unit_price = $product->price;
                $itemCart->total_price = $itemCart->total_price + $itemCart->unit_price;
                $itemCart->save();

                $product->stock = $product->stock - $itemCart->qty_unit;
                if($product->stock == 0){
                    $product->state = 'archived';
                    //enviar algun aviso de que el producto est agotado y cambio su estado
                }
                $product->save();

            }else {
                return $this->successResponse(['el producto no esta disponible']);
            }

        } else {
            return $this->successResponse(['el producto ya esta en el carrito']);
        }

        $countCart = $this->countCart($userId);

        return $this->successResponse([$countCart, 'Products retrieved successfully.']);
    }

    //cantidad de productos del carrito

    public function countCart($userId)
    {

        $countCart = DB::table('user_products')
            ->select(DB::raw('sum(qty) as count'))
            ->where('user_products.user_id', '=', $userId)
            ->whereNull('deleted_at')
            ->get();

        if($countCart[0]->count == null  ){
            $countCart[0]->count = 0;
            return $this->successResponse([$countCart, 'Products retrieved successfully.']);
        }

        return $this->successResponse([$countCart, 'Products retrieved successfully.']);
    }


    //precio total de todos los productos del cariito

    public function TotalPricetCart($userId)
    {

        $TotalPrice = DB::table('user_products')
            ->select(DB::raw('sum(total_price) as count'))
            ->where('user_products.user_id', '=', $userId)
            ->whereNull('deleted_at')
            ->get();

        return $this->successResponse([$TotalPrice, 'Products retrieved successfully.']);
    }


    // actualizar carrito

    public function updateCart(Request $request)
    {
        $productExist = UserProduct::findOrFail($request->id);
        $product = Product::findOrFail($productExist->product_id);
        $stock = $product->stock;
        $qty_old = $productExist->qty_unit;

        $posible_product = $qty_old + $stock;

        if ($posible_product >= $request->qty_unit) {

            $productExist->qty_unit = $request->qty_unit;
            $productExist->total_price = $productExist->unit_price * $request->qty_unit;
            $productExist->save();

            $product->stock = $product->stock + $qty_old - $productExist->qty_unit;
            if($product->stock == 0){
                $product->state = 'archived';
                //enviar algun aviso de que el producto est agotado y cambio su estado
            }
            $product->save();

        } else {

            return $this->successResponse(['Por favor revise su cantidad a actualizar, es mayor que el producto disponible: '.$product->stock]);
        }
$product->state = 'archived';
        return $this->successResponse([$productExist, 'Cantidad Actualizada.']);
    }

    //eliminar carrito

    public function clearCart(Request $request)
    {
        $userId = auth()->user()->id;
        DB::delete('delete from user_products where user_id = ?', [$userId]);
        return $this->successResponse('Cart empty successfully.');
    }

    public function deleteProductCart(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $userproduct = UserProduct::findOrFail($request->id);

        $product->stock = $product->stock + $userproduct->qty_unit;
        if($product->stock != 0){
           $product->state = 'published';
        }
        $product->save();
        $userproduct->delete();



 }
}