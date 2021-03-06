<?php

namespace App\Http\Controllers\api;

use App\User;
use App\Locality;
use App\Messenger;
use App\OrdersExpress;
use App\DeliveriesCost;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\SendMailExpress;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailMessengerExpress;
use App\Mail\SendMailMessengerReasigned;
use App\Mail\SendMailOrderCancelExpress;
use App\Http\Requests\StoreOrderStatePut;
use App\Http\Requests\StoreOrderExpressPut;
use App\Http\Requests\StoreDeliveryCostPost;
use App\Http\Requests\StoreOrderExpressPost;

class OrderExpressController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = OrdersExpress::
        join('users','users.id', '=','orders_expresses.user_id')
        ->join('deliveries_costs', 'deliveries_costs.id', '=', 'orders_expresses.delivery_cost_id')
        ->join('rols', 'users.rol_id', '=', 'rols.id')
        ->join('localities as localityR', 'localityR.id', '=', 'orders_expresses.locality_id_r')
        ->join('localities as localityD', 'localityD.id', '=', 'orders_expresses.locality_id_d')
        ->select('orders_expresses.id as order','orders_expresses.code','orders_expresses.name_r','orders_expresses.address_r','orders_expresses.cell_r',
        'orders_expresses.phone_r','localityR.name as locality_remitente','orders_expresses.name_d','localityD.name as locality_destinatario','orders_expresses.address_d',
        'orders_expresses.cell_d','orders_expresses.phone_d','orders_expresses.object_details','orders_expresses.weigth','orders_expresses.state','orders_expresses.message',
        'deliveries_costs.tranpostation_cost','users.id as user','users.name','users.email')
        ->whereNull('orders_expresses.deleted_at')
        ->get();

        return $this->successResponse(['orders'=>$orders,'message'=>'Orders retrieved successfully.']);
    }


    public function orderDetails(OrdersExpress $order)
    {
        $id= $order->id;

        $order = OrdersExpress::
        join('users', 'users.id', '=', 'orders_expresses.user_id')
        ->join('rols', 'users.rol_id', '=', 'rols.id')
        ->join('deliveries_costs', 'deliveries_costs.id', '=', 'orders_expresses.delivery_cost_id')
        ->join('localities as localityR', 'localityR.id', '=', 'orders_expresses.locality_id_r')
        ->join('localities as localityD', 'localityD.id', '=', 'orders_expresses.locality_id_d')
        ->select('orders_expresses.id as order','orders_expresses.code','orders_expresses.name_r','orders_expresses.address_r','orders_expresses.cell_r',
        'orders_expresses.phone_r','localityR.name as locality_remitente','orders_expresses.name_d','localityD.name as locality_destinatario','orders_expresses.address_d',
        'orders_expresses.cell_d','orders_expresses.phone_d','orders_expresses.object_details','orders_expresses.weigth','orders_expresses.state','orders_expresses.message',
        'deliveries_costs.tranpostation_cost','users.id as user','users.name','users.email','rols.name as rol')
        ->where('orders_expresses.id',[$id])
        ->whereNull('orders_expresses.deleted_at')
        ->get();


        $messenger = Messenger::
        join('orders_expresses','orders_expresses.messenger_id','=','messengers.id')->
        select('messengers.*')->
        where('orders_expresses.id',[$id])->
        whereNotNull('orders_expresses.messenger_id')->
        get();

        return $this->successResponse(['order'=>$order,'messenger'=>$messenger,'message'=>'Details retrieved successfully.']);

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
        $string_to = $request['lonlat_to'];
        $string_from = $request['lonlat_from'];
        $arrayLocationsTo = explode(';',$string_to);
        $arrayLocationsFrom = explode(';',$string_from);
        $module =  'ABRAEXPRESS';

        $v_order = new StoreOrderExpressPost();
        $validator = $request->validate($v_order->rules());
        $cadena = Str::random(5);
        if ($validator) {
            $order = new OrdersExpress();
            $order->code = $module . $cadena;
            $order->name_r = $request['name_r'];
            $order->address_r = $request['address_r'];
            $order->cell_r = $request['cell_r'];
            $order->phone_r = $request['phone_r'];
            $order->locality_id_r = $request['locality_id_r'];
            $order->name_d = $request['name_d'];
            $order->locality_id_d = $request['locality_id_d'];
            $order->address_d = $request['address_d'];
            $order->cell_d = $request['cell_d'];
            $order->phone_d = $request['phone_d'];
            $order->object_details = $request['object_details'];
            $order->weigth = $request['weigth'];
            $order->state = 'nueva';
            $order->message = $request['message'];
            $order->user_id = $request['user_id'];
            $order->messenger_id = $request['messenger_id'];
            $order->message_cancel = $request['message_cancel'];

            $v_delivery = new StoreDeliveryCostPost();
            $validator = $request->validate($v_delivery->rules());
            if($validator){
                $delivery = new DeliveriesCost();
                $delivery->from_municipality_id = $request['from_municipality_id'];
                $delivery->to_municipality_id = $request['to_municipality_id'];
                $delivery->longitude_to = $arrayLocationsTo[0];
                $delivery->latitude_to = $arrayLocationsTo[1];
                $delivery->longitude_from = $arrayLocationsFrom[0];
                $delivery->latitude_from = $arrayLocationsFrom[1];
                $delivery->distance = $request['distance'];

                $transportationCost = DeliveryCostController::transportationCost($request);
                $costoTransportacion = json_decode($transportationCost->original['costoTransportacion']);
                $delivery->tranpostation_cost = $costoTransportacion;
                $delivery->save();

            }
            $order->delivery_cost_id = $delivery->id;
            $order->save();

            // mandar un email al mensajero con los datos de la orden

            if($order->state = 'nueva'){

                $result = $this->sendEmail($order);
                if(empty($result)){

                    return $this->successResponse(['order' => $order,'costoDelivery'=>$transportationCost,'message'=>'Order new is created successfully.']);
                }

            }

        return response()->json([
            'message' => 'Error al validar'
        ], 201);
    }
    }

     //funcion para enviar mensaje al usuario
     public function sendEmail($order){

        $orderasignada = OrdersExpress::findOrFail($order->id);
        $usuario= User::findOrFail($order->user_id);
        $localityR = Locality::findOrFail($order->locality_id_r);
        $localityD = Locality::findOrFail($order->locality_id_d);
        $costDelivery = DeliveriesCost::findOrFail($order->delivery_cost_id);
        $title = 'Su Orden Ha sido creada!!!! Gracias por elegir Abracadabra';

        $customer_details = [
        'name' => $usuario->get('name'),
        'email' => $usuario->get('email')
        ];
        $order_details = [
             'Codigo' => $orderasignada->get('code'),
             'Nombre Remitente' => $orderasignada->get('name_r'),
             'Direcci??n Remitente' => $orderasignada->get('address_r'),
             'M??vil Remitente' => $orderasignada->get('cell_r'),
             'Tel??fono Remitente' => $orderasignada->get('phone_r'),
             'Localidad Remitente' => $localityR->get('name'),
             'Nombre Destinatario' => $orderasignada->get('name_d'),
             'Direcci??n Destinatario' => $orderasignada->get('address_d'),
             'M??vil Destinatario' => $orderasignada->get('cell_d'),
             'Localidad Destinatario' => $localityD->get('name'),
             'Detalles Objeto' => $orderasignada->get('object_details'),
             'Peso' => $orderasignada->get('weigth'),
             'Mensaje' => $orderasignada->get('message'),
             'Costo de Transportacion' => $costDelivery->get('tranpostation_cost'),

        ];

           $sendmail = Mail::to($customer_details['email'])
           ->send(new SendMailExpress($title, $customer_details,$order_details));
           if (empty($sendmail)) {
             return response()->json(['message'
             => 'Mail Sent Sucssfully'], 200);
             }else{
                 return response()->json(['message' => 'Mail Sent fail'], 400);
                }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\OrdersExpresses  $orderExpress
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = OrdersExpress::find($id);

        if (is_null($order)) {
            return $this->successResponse(['message'=>'Order not found.']);
        }

        return $this->successResponse(['order'=>$order,'message'=> 'Order retrieved successfully.']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OrdersExpress  $orderExpress
     * @return \Illuminate\Http\Response
     */
    public function edit(OrdersExpress $OrdersExpress)
    {
        //
    }

    /**
     * Update the specified resource in storage.

    *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OrdersExpress  $OrdersExpress
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrdersExpress $orderExpress)
    {
        $v_order = new StoreOrderExpressPut();
        $validator = $request->validate($v_order->rules());
        if ($validator) {

            if($orderExpress->state == "en_progreso" ){

                if($request['state'] == 'terminada'){
                    $orderExpress->state = $request['state'];
                    $orderExpress->save();
                }

            }
          elseif($orderExpress->messenger_id != null){

            $mesengerOld = Messenger::findOrFail($orderExpress->messenger_id);

            $orderExpress->object_details = $request['object_details'];
            $orderExpress->weigth = $request['weigth'];
            $orderExpress->state = $request['state'];
            $orderExpress->message = $request['message'];
            $orderExpress->messenger_id = $request['messenger_id'];
            $orderExpress->message_cancel = $request['message_cancel'];
            $orderExpress->save();
          }


           //mandar un email al mensajero con los datos de la orden
          switch ($request->state) {

            case 'asignada':
                if($mesengerOld == null){
                    $result =  $this->sendEmailCancelOrAsigned($orderExpress);
                    if(empty($result)){
                        return response()->json(['message' => 'Order asigned successfully.'], 201);
                    }
                }else{
                    if($mesengerOld != null && $mesengerOld != $orderExpress->messenger_id){
                    $result = $this->sendEmailReasigned($orderExpress,$mesengerOld);
                    if(empty($result)){
                        return response()->json(['message' => 'Order has been reasigned successfully.'], 201);
                        }
                  }
                }
                break;

            case 'cancelada':
                $result = $this->sendEmailCancelOrAsigned($orderExpress);
                if(empty($result)){
                    return response()->json(['message' => 'Order cancel successfully.'], 201);
                    }
                break;
        }

       }
        return response()->json([
            'message' => 'Error al validar'
        ], 201);
    }


    //funcion para enviar mensaje al mensajero cuando la orden es asignada
    public function sendEmailCancelOrAsigned ($order) {

        $orderasignada = OrdersExpress::findOrFail($order->id);
        $mensajero = Messenger::findOrFail($order->messenger_id);
        $email_mensajero = DB::select('select users.email from users where users.id = ?',[$mensajero->user_id]);
        $email = $email_mensajero[0]->email;
        $localityR = Locality::findOrFail($order->locality_id_r);
        $localityD = Locality::findOrFail($order->locality_id_d);
        $costDelivery = DeliveriesCost::findOrFail($order->delivery_cost_id);

        $customer_details = [
        'name' => $mensajero->name,
        'email' => $email
        ];
        $order_details = [
            'Codigo' => $orderasignada->code,
            'Nombre Remitente' => $orderasignada->name_r,
            'Direcci??n Remitente' => $orderasignada->address_r,
            'M??vil Remitente' => $orderasignada->cell_r,
            'Tel??fono Remitente' => $orderasignada->phone_r,
            'Localidad Remitente' => $localityR->name,
            'Nombre Destinatario' => $orderasignada->name_d,
            'Direcci??n Destinatario' => $orderasignada->address_d,
            'M??vil Destinatario' => $orderasignada->cell_d,
            'Localidad Destinatario' => $localityD->name,
            'Detalles Objeto' => $orderasignada->object_details,
            'Peso' => $orderasignada->weigth,
            'Mensaje' => $orderasignada->message,
            'Costo de Transportacion' => $costDelivery->tranpostation_cost,
            'Message_Cancel' => $orderasignada->message_cancel,

        ];

        switch ($orderasignada->state) {
            case 'asignada':
             $title = 'Le ha sido asignada una nueva orden';

              $sendmail = Mail::to($customer_details['email'])
              ->send(new SendMailMessengerExpress($title, $customer_details,$order_details));
              if (empty($sendmail)) {
              return response()->json(['message'
              => 'Mail Sent Sucssfully'], 200);
              }else{
                  return response()->json(['message' => 'Mail Sent fail'], 400);
                 }
                break;

            case 'cancelada':
                $title = 'Orden Cancelada';

                $sendmail = Mail::to($customer_details['email'])
                ->send(new SendMailOrderCancelExpress($title, $customer_details,$order_details));
                if (empty($sendmail)) {
                  return response()->json(['message'
                  => 'Mail Sent Sucssfully'], 200);
                  }else{
                      return response()->json(['message' => 'Mail Sent fail'], 400);
                     }
                break;
        }

    }

    public function sendEmailReasigned($order,$mesengerOld){

        $title = 'Orden Reasignada';
        $orderasignada = OrdersExpress::findOrFail($order->id);
        $mesengerOld = Messenger::findOrFail($order->messenger_id);
        $email_mensajero = DB::select('select users.email from users where users.id = ?',[$mesengerOld->user_id]);
        $email = $email_mensajero[0]->email;

        $customer_details = [
            'name' => $mesengerOld->name,
            'email' => $email
            ];
        $order_details = [
             'Codigo' => $orderasignada->code
        ];

        $sendmail = Mail::to($customer_details['email'])
            ->send(new SendMailMessengerReasigned($title, $customer_details,$order_details));
            if (empty($sendmail)) {
              return response()->json(['message'
              => 'Mail Sent Sucssfully'], 200);
              }else{
                  return response()->json(['message' => 'Mail Sent fail'], 400);
                 }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OrdersExpress  $orderExpress
     * @return \Illuminate\Http\Response
     */
    public function destroyExpress(Request $request)
    {
        $orderExpress = OrdersExpress::findOrFail($request['id']);
        $orderExpress->delete();
        return $this->successResponse(['message'=>'Order deleted successfully.']);
    }
}
