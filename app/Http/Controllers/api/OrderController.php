<?php

namespace App\Http\Controllers\api;

use App\User;
use App\Order;
use App\Product;
use App\Locality;
use App\Messenger;
use App\UserProduct;
use App\OrderProduct;
use App\Mail\SendMail;
use App\OrdersExpress;
use App\DeliveriesCost;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\SendMailMessenger;
use App\Mail\SendMailOrderCancel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderPut;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\StoreOrderPost;
use App\Http\Requests\StoreOrderStatePut;
use App\Http\Controllers\api\MailController;
use App\Http\Requests\StoreDeliveryCostPost;
use App\Http\Controllers\api\ApiResponseController;
use App\Http\Controllers\api\UserProductController;
use App\Mail\SendMailMessengerMototaxi;
use App\Mail\SendMailMessengerReasigned;
use App\OrdersMototaxi;

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
        join('users','users.id', '=','orders.user_id')
        ->join('deliveries_costs', 'deliveries_costs.id', '=', 'orders.delivery_cost_id')
        ->join('rols', 'users.rol_id', '=', 'rols.id')
        ->select('orders.id as order','orders.code','orders.user_name','orders.user_phone','orders.user_address',
        'orders.pickup_date','orders.pickup_time_from','orders.pickup_time_to','orders.message','orders.state',
        'orders.payment_type','orders.payment_state','deliveries_costs.tranpostation_cost','users.id as user','users.name',
        'users.email','rols.name as rol')
        ->whereNull('orders.deleted_at')
        ->get();


        return $this->successResponse([$orders,'Orders retrieved successfully.']);
    }


    public function ordersFinished($userId){

        $orders = Order::
        join('users', 'users.id', '=', 'orders.user_id')
        ->join('rols', 'users.rol_id', '=', 'rols.id')
        ->join('deliveries_costs', 'deliveries_costs.id', '=', 'orders.delivery_cost_id')
        ->select('orders.id as order','orders.code','orders.user_name','orders.user_phone','orders.user_address',
        'orders.pickup_date','orders.pickup_time_from','orders.pickup_time_to','orders.message','orders.state',
        'orders.payment_type','orders.payment_state','deliveries_costs.tranpostation_cost','orders.message_cancel','users.name as user','users.id',
        'users.email','rols.name as rol','orders.message_cancel')
        ->where([
            ['orders.user_id', '=', [$userId]],
            ['orders.state','=','cancelada'],
        ])->orWhere([
            ['orders.user_id', '=', [$userId]],
            ['orders.state','=','entregada']
        ])
        ->whereNull('orders.deleted_at')
        ->get();

        $ordersExpress = OrdersExpress::
        join('users', 'users.id', '=', 'orders_expresses.user_id')
        ->join('rols', 'users.rol_id', '=', 'rols.id')
        ->join('deliveries_costs', 'deliveries_costs.id', '=', 'orders_expresses.delivery_cost_id')
        ->join('localities as localityR', 'localityR.id', '=', 'orders_expresses.locality_id_r')
        ->join('localities as localityD', 'localityD.id', '=', 'orders_expresses.locality_id_d')
        ->select('orders_expresses.id as order','orders_expresses.code','orders_expresses.name_r','orders_expresses.address_r','orders_expresses.cell_r',
        'orders_expresses.phone_r','localityR.name as locality_remitente','orders_expresses.name_d','localityD.name as locality_destinatario','orders_expresses.address_d',
        'orders_expresses.cell_d','orders_expresses.phone_d','orders_expresses.object_details','orders_expresses.weigth','orders_expresses.state','orders_expresses.message',
        'deliveries_costs.tranpostation_cost','users.id as user','users.name','users.email','rols.name as rol',
        'orders_expresses.message_cancel')
        ->where([
            ['orders_expresses.user_id', '=', [$userId]],
            ['orders_expresses.state','=','cancelada'],
        ])->orWhere([
            ['orders_expresses.user_id', '=', [$userId]],
            ['orders_expresses.state','=','entregada']
        ])
        ->whereNull('orders_expresses.deleted_at')
        ->get();

        $ordersMototaxi = OrdersMototaxi::
        join('users','users.id', '=','orders_mototaxis.user_id')
        ->join('deliveries_costs', 'deliveries_costs.id', '=', 'orders_mototaxis.delivery_cost_id')
        ->join('rols', 'users.rol_id', '=', 'rols.id')
        ->join('localities as localityF', 'localityF.id', '=', 'orders_mototaxis.locality_from_id')
        ->join('localities as localityT', 'localityT.id', '=', 'orders_mototaxis.locality_to_id')
        ->select('orders_mototaxis.id as order','orders_mototaxis.code','orders_mototaxis.cell','orders_mototaxis.address_from',
        'localityF.name as locality_from','localityT.name as locality_to','orders_mototaxis.address_to',
        'orders_mototaxis.state','deliveries_costs.tranpostation_cost','users.id as user','users.name','users.email',
        'orders_mototaxis.message_cancel')
        ->where([
            ['orders_mototaxis.user_id', '=', [$userId]],
            ['orders_mototaxis.state','=','cancelada'],
        ])->orWhere([
            ['orders_mototaxis.user_id', '=', [$userId]],
            ['orders_mototaxis.state','=','entregada']
        ])
        ->whereNull('orders_mototaxis.deleted_at')
        ->get();

        return $this->successResponse(['orders'=>$orders,'orders_express'=>$ordersExpress,'orders_mototaxi'=>$ordersMototaxi,'orders retrieved successfully.']);
    }

    public function ordersActive($userId){

        $orders = Order::
        join('users', 'users.id', '=', 'orders.user_id')
        ->join('rols', 'users.rol_id', '=', 'rols.id')
        ->join('deliveries_costs', 'deliveries_costs.id', '=', 'orders.delivery_cost_id')
        ->select('orders.id as order','orders.code','orders.user_name','orders.user_phone','orders.user_address',
        'orders.pickup_date','orders.pickup_time_from','orders.pickup_time_to','orders.message','orders.state',
        'orders.payment_type','orders.payment_state','deliveries_costs.tranpostation_cost','users.name as user','users.id',
        'users.email','rols.name as rol')
        ->where([
            ['orders.user_id', '=', [$userId]],
            ['orders.state','=','nueva'],
        ])->orWhere([
            ['orders.user_id', '=', [$userId]],
            ['orders.state','=','en_progreso']
        ])
        ->orWhere([
            ['orders.user_id', '=', [$userId]],
            ['orders.state','=','asignada']
        ])
        ->whereNull('orders.deleted_at')
        ->get();

        $ordersExpress = OrdersExpress::
        join('users', 'users.id', '=', 'orders_expresses.user_id')
        ->join('rols', 'users.rol_id', '=', 'rols.id')
        ->join('deliveries_costs', 'deliveries_costs.id', '=', 'orders_expresses.delivery_cost_id')
        ->join('localities as localityR', 'localityR.id', '=', 'orders_expresses.locality_id_r')
        ->join('localities as localityD', 'localityD.id', '=', 'orders_expresses.locality_id_d')
        ->select('orders_expresses.id as order','orders_expresses.code','orders_expresses.name_r','orders_expresses.address_r','orders_expresses.cell_r',
        'orders_expresses.phone_r','localityR.name as locality_remitente','orders_expresses.name_d','localityD.name as locality_destinatario','orders_expresses.address_d',
        'orders_expresses.cell_d','orders_expresses.phone_d','orders_expresses.object_details','orders_expresses.weigth','orders_expresses.state','orders_expresses.message',
        'deliveries_costs.tranpostation_cost','users.id as user','users.name','users.email','rols.name as rol')
        ->where([
            ['orders_expresses.user_id', '=', [$userId]],
            ['orders_expresses.state','=','nueva'],
        ])->orWhere([
            ['orders_expresses.user_id', '=', [$userId]],
            ['orders_expresses.state','=','en_progreso']
        ])
        ->orWhere([
            ['orders_expresses.user_id', '=', [$userId]],
            ['orders_expresses.state','=','asignada']
        ])
        ->whereNull('orders_expresses.deleted_at')
        ->get();

        $ordersMototaxi = OrdersMototaxi::
        join('users','users.id', '=','orders_mototaxis.user_id')
        ->join('deliveries_costs', 'deliveries_costs.id', '=', 'orders_mototaxis.delivery_cost_id')
        ->join('rols', 'users.rol_id', '=', 'rols.id')
        ->join('localities as localityF', 'localityF.id', '=', 'orders_mototaxis.locality_from_id')
        ->join('localities as localityT', 'localityT.id', '=', 'orders_mototaxis.locality_to_id')
        ->select('orders_mototaxis.id as order','orders_mototaxis.code','orders_mototaxis.cell','orders_mototaxis.address_from',
        'localityF.name as locality_from','localityT.name as locality_to','orders_mototaxis.address_to',
        'orders_mototaxis.state','deliveries_costs.tranpostation_cost','users.id as user','users.name','users.email')
        ->where([
            ['orders_mototaxis.user_id', '=', [$userId]],
            ['orders_mototaxis.state','=','nueva'],
        ])->orWhere([
            ['orders_mototaxis.user_id', '=', [$userId]],
            ['orders_mototaxis.state','=','en_progreso']
        ])->orWhere([
            ['orders_mototaxis.user_id', '=', [$userId]],
            ['orders_mototaxis.state','=','asignada']
        ])
        ->whereNull('orders_mototaxis.deleted_at')
        ->get();


        return $this->successResponse(['orders'=>$orders,'orders_express'=>$ordersExpress,'orders_mototaxi'=>$ordersMototaxi,'orders retrieved successfully.']);
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
        join('localities', 'localities.id', '=', 'orders.locality_id')
        ->join('municipies', 'municipies.id', '=', 'localities.municipie_id')
        ->join('users', 'users.id', '=', 'orders.user_id')
        ->join('rols', 'users.rol_id', '=', 'rols.id')
        ->join('deliveries_costs', 'deliveries_costs.id', '=', 'orders.delivery_cost_id')
        ->select('orders.id as order','orders.code','orders.user_name','orders.user_phone','orders.user_address',
        'orders.pickup_date','orders.pickup_time_from','orders.pickup_time_to','orders.message','orders.state',
        'orders.payment_type','orders.payment_state','orders.delivery_time_to','orders.delivery_time_from',
        'deliveries_costs.tranpostation_cost','users.name as user','users.id','users.email','rols.name as rol',
        'localities.name as locality','municipies.name as municipie')
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
      public function store(Request $request){

        $string = $request['lonlat'];
        $arrayLocationsTo = explode(';',$string);
        $module =  'ABRAME';
        $LocationsFrom = DeliveryCostController::locationByModule($module);
        $LongitudeFrom = json_decode($LocationsFrom->original['longitude_from']);
        $LatitudeFrom = json_decode($LocationsFrom->original['latitude_from']);


        $v_order = new StoreOrderPost();
        $validator = $request->validate($v_order->rules());
        $cadena = Str::random(5);
        if ($validator) {
            $order = new Order();
            $order->code = 'ABRAME' . $cadena;
            $order->user_name = $request['user_name'];
            $order->user_phone = $request['user_phone'];
            $order->user_address = $request['user_address'];
            $order->pickup_time_from = $request['pickup_time_from'];
            $order->pickup_time_to = $request['pickup_time_to'];
            $order->message = $request['message'];
            $order->locality_id = $request['locality_id'];
           // $order->payment_type = $request['payment_type'];

            $order->delivery_time_to = $request['delivery_time_to'];
            $order->delivery_time_from = $request['delivery_time_from'];
            $order->state = 'nueva';
           // $order->payment_state = 'undone';
            $order->delivery_type = 'standard';
            $order->messenger_id = $request['messenger_id'];
            $order->user_id = $request['user_id'];

            $v_delivery = new StoreDeliveryCostPost();
            $validator = $request->validate($v_delivery->rules());
            if($validator){
               $delivery = new DeliveriesCost();
               $delivery->from_municipality_id = $request['from_municipality_id'];
               $delivery->to_municipality_id = $request['to_municipality_id'];
               $delivery->latitude_from = $LatitudeFrom;
               $delivery->longitude_from = $LongitudeFrom;
               $delivery->latitude_to = $arrayLocationsTo[1];
               $delivery->longitude_to = $arrayLocationsTo[0];
               $delivery->distance = $request['distance'];

               $transportationCost = DeliveryCostController::transportationCost($request);
               $costoTransportacion = json_decode($transportationCost->original['costoTransportacion']);
               $delivery->tranpostation_cost = $costoTransportacion;
               $delivery->save();
            }

            $order->delivery_cost_id = $delivery->id;
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

             //mandar un email al mensajero con los datos de la orden

            $productsOrder = DB::select('select products.`name`,order_products.quantity,order_products.total from order_products join products ON products.id = order_products.product_id where order_products.order_id = ?', [$order->id]);

            if($order->state = 'nueva'){

                $result = $this->sendEmail($order,$productsOrder);
                if(empty($result)){

                    return $this->successResponse(['order' => $order, 'products' => $productsOrder,'costoTransportacion'=>$transportationCost,'message'=>'Order new is created successfully.']);
                }
            }

            return $this->successResponse(['order' => $order, 'products' => $productsOrder,'costoTransportacion'=>$transportationCost,'message'=>'Order new is created successfully.']);
    }

    return response()->json([
        'message' => 'Error al validar'
    ], 201);

  }

     //funcion para enviar mensaje al usuario
     public function sendEmail($order,$productsOrder){

        $orderasignada = Order::findOrFail($order->id);
        $usuario= User::findOrFail($order->user_id);
        $locality = Locality::findOrFail($order->locality_id);
        $costDelivery = DeliveriesCost::findOrFail($order->delivery_cost_id);
        $title = 'Su Orden Ha sido creada!!!! Gracias por elegir Abracadabra';

        $customer_details = [
        'name' => $usuario->get('name'),
        'email' => $usuario->get('email')
        ];
        $order_details = [
             'Codigo' => $orderasignada->get('code'),
             'Nombre' => $orderasignada->get('user_name'),
             'Teléfono' => $orderasignada->get('user_phone'),
             'Dirección' => $orderasignada->get('user_address'),
             'Localidad' => $locality->get('name'),
            'Hora de entrega desde' => $orderasignada->get('pickup_time_from'),
             'Hora de entrega hasta' => $orderasignada->get('pickup_time_to'),
             'Mensaje' => $orderasignada->get('message'),
             'Costo de Transportacion' => $costDelivery->get('tranpostation_cost'),
             'Productos'=>$productsOrder

        ];

           $sendmail = Mail::to($customer_details['email'])
           ->send(new SendMail($title, $customer_details,$order_details));
           if (empty($sendmail)) {
             return response()->json(['message'
             => 'Mail Sent Sucssfully'], 200);
             }else{
                 return response()->json(['message' => 'Mail Sent fail'], 400);
                }

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
        $mesengerOld = null;
        $v_order = new StoreOrderPut();
        $validator = $request->validate($v_order->rules());
        if ($validator) {

            //hacer una consulta a la bd para verificar si existe messenger_id y si lo hay,
            // guardarlo para, enviar sms de cancelacion o reasignacion
            if($order->messenger_id != null){
                $mesengerOld = Messenger::findOrFail($order->messenger_id);
            }

            $order->delivery_time_to = $request['delivery_time_to'];
            $order->delivery_time_from = $request['delivery_time_from'];
            $order->pickup_date = $request['pickup_date'];
            $order->state = $request['state'];
            $order->payment_state = 'undone';
            $order->messenger_id = $request['messenger_id'];
            $order->message_cancel = $request['message_cancel'];
            $order->save();

            //mandar un email al mensajero con los datos de la orden

            $productsOrder = DB::select('select products.`name`,order_products.quantity,order_products.total from order_products join products ON products.id = order_products.product_id where order_products.order_id = ?', [$order->id]);

            if($request->state == 'asignada'){
                if($mesengerOld != $order->messenger_id){

                    $result =  $this->sendEmailCancelOrAsigned($order,$productsOrder);
                if(empty($result)){

                    return $this->successResponse(['order' => $order, 'products' => $productsOrder,'Order asigned successfully.']);
                }
                }

                $result =  $this->sendEmailCancelOrAsigned($order,$productsOrder);
                if(empty($result)){

                    return $this->successResponse(['order' => $order, 'products' => $productsOrder,'Order asigned successfully.']);
                }
            }

            //mandar un email al $this->sendEmailCancelOrAsigned($order,$productsOrder);usuario con los datos de la orden
            if($request->state == 'cancelada' ){

                $result = $this->sendEmailCancelOrAsigned($order,$productsOrder);
                if(empty($result)){
                    return $this->successResponse(['order' => $order, 'products' => $productsOrder,'Order cancel successfully.']);
                }
            }
      }
        return response()->json([
            'message' => 'Error al validar'
        ], 201);

    }

    //funcion para enviar mensaje al mensajero cuando la orden es asignada
    public function sendEmailCancelOrAsigned ($order,$productsOrder) {

        $orderasignada = Order::findOrFail($order->id);
        $mensajero = Messenger::findOrFail($order->messenger_id);
        $locality = Locality::findOrFail($order->locality_id);
        $costDelivery = DeliveriesCost::findOrFail($order->delivery_cost_id);
        $title = 'Le ha sido asignada una nueva orden';

        $customer_details = [
        'name' => $mensajero->get('name'),
        'email' => $mensajero->get('email')
        ];
        $order_details = [
             'Codigo' => $orderasignada->get('code'),
             'Nombre' => $orderasignada->get('user_name'),
             'Teléfono' => $orderasignada->get('user_phone'),
             'Dirección' => $orderasignada->get('user_address'),
             'Localidad' => $locality->get('name'),
            'Hora de entrega desde' => $orderasignada->get('pickup_time_from'),
             'Hora de entrega hasta' => $orderasignada->get('pickup_time_to'),
             'Mensaje' => $orderasignada->get('message'),
             'Costo de Transportacion' => $costDelivery->get('tranpostation_cost'),
             'Productos'=>$productsOrder,
             'Message_Cancel'=> $orderasignada->get('message_cancel')
        ];

          if($orderasignada->state == 'asignada'){
            $sendmail = Mail::to($customer_details['email'])
            ->send(new SendMailMessenger($title, $customer_details,$order_details));
            if (empty($sendmail)) {
              return response()->json(['message'
              => 'Mail Sent Sucssfully'], 200);
              }else{
                  return response()->json(['message' => 'Mail Sent fail'], 400);
                 }

          }if($orderasignada->state == 'cancelada'){
            $sendmail = Mail::to($customer_details['email'])
            ->send(new SendMailOrderCancel($title, $customer_details,$order_details));
            if (empty($sendmail)) {
              return response()->json(['message'
              => 'Mail Sent Sucssfully'], 200);
              }else{
                  return response()->json(['message' => 'Mail Sent fail'], 400);
                 }

          }else{
            $sendmail = Mail::to($customer_details['email'])
            ->send(new SendMailMessengerReasigned($title, $customer_details,$order_details));
            if (empty($sendmail)) {
              return response()->json(['message'
              => 'Mail Sent Sucssfully'], 200);
              }else{
                  return response()->json(['message' => 'Mail Sent fail'], 400);
                 }
          }

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