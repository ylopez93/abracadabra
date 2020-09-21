<?php

namespace App\Http\Controllers\api;

use App\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\api\ApiResponseController;
use App\Http\Requests\StoreOrderPost;

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
        join('messengers','messengers.id','=','orders.messenger_id')->
        join('users','users.id','=','orders.user_id')->
        select('orders.*','users.*','messengers.*')->
        orderBy('orders.created_at','desc')->paginate(10);
        return $this->successResponse([$orders,'Orders retrieved successfully.']);
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

        $v_order = new StoreOrderPost();
        $validator = $request->validate($v_order->rules());
        if($validator){
           $order = new Order();
           $order->code = $request['code'];
           $order->user_name = $request['user_name'];
           $order->user_phone = $request['user_phone'];
           $order->user_address = $request['user_address'];
           $order->pickup_date = $request['pickup_date'];
           $order->pickup_time_from = $request['pickup_time_from'];
           $order->pickup_time_to = $request['pickup_time_to'];
           $order->delivery_time_to = $request['delivery_time_to'];
           $order->delivery_time_from = $request['delivery_time_from'];
           $order->message = $request['message'];
           $order->state = 'new';
           $order->payment_type = 'cash';
           $order->payment_state = 'undone';
           $order->delivery_type = 'standard';
           $order->messenger_id = $request['messenger_id'];
           $order->user_id = $request['user_id'];
           $order->transportation_cost = $request['transportation_cost'];
           $order->save();

        return $this->successResponse([$order, 'Order created successfully.']);

        }
        return response()->json([
            'message' => 'Error al validar'
        ], 201);

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

        if(is_null($order)){
            return $this->errorResponse('Order not found.');
        }

        return $this->successResponse([$order,'Product retrieved successfully.']);
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
        $v_order = new StoreOrderPost();
        $validator = $request->validate($v_order->rules());
        if($validator){
           $order->code = $request['code'];
           $order->user_name = $request['user_name'];
           $order->user_phone = $request['user_phone'];
           $order->user_address = $request['user_address'];
           $order->pickup_date = $request['pickup_date'];
           $order->pickup_time_from = $request['pickup_time_from'];
           $order->pickup_time_to = $request['pickup_time_to'];
           $order->delivery_time_to = $request['delivery_time_to'];
           $order->delivery_time_from = $request['delivery_time_from'];
           $order->message = $request['message'];
           $order->state = 'new';
           $order->payment_type = 'cash';
           $order->payment_state = 'undone';
           $order->delivery_type = 'standard';
           $order->messenger_id = $request['messenger_id'];
           $order->user_id = $request['user_id'];
           $order->transportation_cost = $request['transportation_cost'];
           $order->save();

        return $this->successResponse([$order, 'Order created successfully.']);

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
