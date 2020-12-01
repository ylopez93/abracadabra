<?php

namespace App\Http\Controllers\api;

use App\Order;
use App\UserProduct;
use App\OrderProduct;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderPut;
use App\Http\Requests\StoreOrderPost;
use App\Http\Controllers\api\ApiResponseController;
use App\Http\Controllers\api\UserProductController;
use App\Messenger;

class OrderController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::
        join('users', 'users.id', '=', 'orders.user_id')
        ->select('orders.id as order','orders.code','orders.user_name','orders.user_phone','orders.user_address',
        'orders.pickup_date','orders.pickup_time_from','orders.pickup_time_to','orders.message','orders.state',
        'orders.payment_type','orders.payment_state','orders.transportation_cost','users.id as user','users.name',
        'users.email','users.rol_id')
        ->orderBy('orders.created_at', 'desc')
        ->whereNull('orders.deleted_at')
        ->whereNull('orders.messenger_id')
        ->get();


        return $this->successResponse([$orders, 'Orders retrieved successfully.']);
    }

    public function odersAsigned()
    {
        $orders = Order::
        join('messengers', 'messengers.id', '=', 'orders.messenger_id')
        ->join('users', 'users.id', '=', 'orders.user_id')
        ->join('rols', 'users.rol_id', '=', 'rols.id')
        ->select('orders.id as order','orders.code','orders.user_name','orders.user_phone','orders.user_address',
        'orders.pickup_date','orders.pickup_time_from','orders.pickup_time_to','orders.message','orders.state',
        'orders.payment_type','orders.payment_state','orders.transportation_cost','users.name as user','users.id',
        'users.email','rols.name','messengers.id as messenger','messengers.name as messenger name','messengers.surname',
        'messengers.ci','messengers.phone','messengers.email as messenger email','messengers.address','messengers.vehicle_registration',
        'messengers.image')
        ->orderBy('orders.created_at', 'desc')
        ->whereNull('orders.deleted_at')
        ->whereNull('messengers.deleted_at')
        ->get();
        return $this->successResponse([$orders, 'Orders retrieved successfully.']);
    }

    public function ordersFinished($userId){
        $orders = Order::
        join('users', 'users.id', '=', 'orders.user_id')
        ->join('rols', 'users.rol_id', '=', 'rols.id')
        ->select('orders.id as order','orders.code','orders.user_name','orders.user_phone','orders.user_address',
        'orders.pickup_date','orders.pickup_time_from','orders.pickup_time_to','orders.message','orders.state',
        'orders.payment_type','orders.payment_state','orders.transportation_cost','users.name as user','users.id',
        'users.email','rols.name')
        ->where('orders.user_id',[$userId])
        ->where('orders.state','=','cancelada')
        ->orWhere('orders.state','=','entregada')
        ->whereNull('orders.deleted_at')
        ->get();

        return $this->successResponse([$orders,'orders retrieved successfully.']);
    }

    public function ordersActive($userId){
        $orders = Order::
        join('users', 'users.id', '=', 'orders.user_id')
        ->join('rols', 'users.rol_id', '=', 'rols.id')
        ->select('orders.id as order','orders.code','orders.user_name','orders.user_phone','orders.user_address',
        'orders.pickup_date','orders.pickup_time_from','orders.pickup_time_to','orders.message','orders.state',
        'orders.payment_type','orders.payment_state','orders.transportation_cost','users.name as user','users.id',
        'users.email','rols.name')
        ->where('orders.user_id',[$userId])
        ->where('orders.state','=','nueva')
        ->orWhere('orders.state','=','en_progreso')
        ->orWhere('orders.state','=','asignada')
        ->whereNull('orders.deleted_at')
        ->get();

        return $this->successResponse([$orders,'orders retrieved successfully.']);
    }

    public function orderProduct(Order $order)
    {
        $id= $order->id;

        $products = OrderProduct::
        join('products','products.id','=','order_products.product_id')->
        join('orders','orders.id','=','order_products.order_id')->
        select('products.name as product','order_products.quantity',
        'order_products.total')->
        where('orders.id',[$id])->
        orderBy('order_products.created_at','desc')->
        whereNull('orders.deleted_at')->
        whereNull('products.deleted_at')->
        get();

        $order = Order::
        join('users', 'users.id', '=', 'orders.user_id')
        ->join('rols', 'users.rol_id', '=', 'rols.id')
        ->select('orders.id as order','orders.code','orders.user_name','orders.user_phone','orders.user_address',
        'orders.pickup_date','orders.pickup_time_from','orders.pickup_time_to','orders.message','orders.state',
        'orders.payment_type','orders.payment_state','orders.transportation_cost','users.name as user','users.id',
        'users.email','rols.name')
        ->where('orders.id',[$id])
        ->whereNull('orders.deleted_at')
        ->get();


        $messenger = Messenger::
        join('orders','orders.messenger_id','=','messengers.id')->
        select('messengers.*')->
        where('orders.id',[$id])->
        whereNotNull('orders.messenger_id')->
        get();

        return $this->successResponse([$order,$products,$messenger,'Products retrieved successfully.']);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $v_order = new StoreOrderPost();
        $validator = $request->validate($v_order->rules());
        $cadena = Str::random(5);
        if ($validator) {
            $order = new Order();
            $order->code = 'ABRA' . $cadena;
            $order->user_name = $request['user_name'];
            $order->user_phone = $request['user_phone'];
            $order->user_address = $request['user_address'];
            $order->pickup_time_from = $request['pickup_time_from'];
            $order->pickup_time_to = $request['pickup_time_to'];
            $order->message = $request['message'];
            $order->municipie_id = 3;
           // $order->payment_type = $request['payment_type'];

            $order->delivery_time_to = $request['delivery_time_to'];
            $order->delivery_time_from = $request['delivery_time_from'];
            $order->state = 'nueva';
           // $order->payment_state = 'undone';
            $order->delivery_type = 'standard';
            $order->messenger_id = $request['messenger_id'];
            $order->user_id = $request['user_id'];
            $order->transportation_cost = $request['transportation_cost'];
            $order->save();

            $Productos = UserProduct::select('user_products.*')
                ->where('user_id', $order->user_id)
                ->whereNull('deleted_at')
                ->get();

            foreach ($Productos as $producto) {

                $order_product = new OrderProduct();
                $order_product->product_id = $producto->product_id;
                $order_product->order_id = $order->id;
                $order_product->quantity = $producto->qty_unit;
                $order_product->total = $producto->total_price;
                $order_product->save();

                $this->deleteProductCart($producto->id);
            }

            $productsOrder = DB::select('select order_products.* from order_products where order_products.order_id = ?', [$order->id]);

            return $this->successResponse(['order' => $order, 'products' => $productsOrder, 'Order created successfully.']);
        }
        return response()->json([
            'message' => 'Error al validar'
        ], 201);
    }

    // eliminar productos del carrito

    public function deleteProductCart($id)
    {
        $userproduct = UserProduct::findOrFail($id);
        $userproduct->delete();
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::find($id);

        if (is_null($order)) {
            return $this->successResponse('Order not found.');
        }

        return $this->successResponse([$order, 'Product retrieved successfully.']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {

        $v_order = new StoreOrderPut();
        $validator = $request->validate($v_order->rules());
        if ($validator) {

            $order->delivery_time_to = $request['delivery_time_to'];
            $order->delivery_time_from = $request['delivery_time_from'];
            $order->pickup_date = $request['pickup_date'];
            $order->state = $request['state'];
            $order->payment_state = 'undone';
            $order->messenger_id = $request['messenger_id'];
            $order->transportation_cost = $request['transportation_cost'];
            $order->save();

            return $this->successResponse([$order,'Order update successfully.']);
        }
        return response()->json([
            'message' => 'Error al validar'
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return $this->successResponse('Order deleted successfully.');
    }
}