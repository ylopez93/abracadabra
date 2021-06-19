<?php

namespace App\Http\Controllers\api;
use App\User;
use App\Locality;
use App\Messenger;
use App\Municipie;
use App\OrderMototaxi;
use App\DeliveriesCost;
use App\OrdersMototaxi;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\SendMailMototaxi;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailMessengerMototaxi;
use App\Mail\SendMailMessengerReasigned;
use App\Http\Requests\StoreOrderStatePut;
use App\Mail\SendMailOrderCancelMototaxi;
use App\Http\Requests\StoreDeliveryCostPost;
use App\Http\Requests\StoreOrderMototaxiPut;
use App\Http\Requests\StoreOrderMototaxiPost;

class OrderMototaxiController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = OrdersMototaxi::
        join('users','users.id', '=','orders_mototaxis.user_id')
        ->join('deliveries_costs', 'deliveries_costs.id', '=', 'orders_mototaxis.delivery_cost_id')
        ->join('rols', 'users.rol_id', '=', 'rols.id')
        ->join('localities as localityF', 'localityF.id', '=', 'orders_mototaxis.locality_from_id')
        ->join('localities as localityT', 'localityT.id', '=', 'orders_mototaxis.locality_to_id')
        ->select('orders_mototaxis.id as order','orders_mototaxis.code','orders_mototaxis.cell','orders_mototaxis.address_from',
        'localityF.name as locality_from','localityT.name as locality_to','orders_mototaxis.address_to',
        'orders_mototaxis.state','deliveries_costs.tranpostation_cost','users.id as user','users.name','users.email')
        ->whereNull('orders_mototaxis.deleted_at')
        ->get();

        return $this->successResponse(['orders'=>$orders,'message'=>'Orders Mototaxi retrieved successfully.']);

    }

    public function orderDetailsMoto(OrdersMototaxi $order)
    {
        $id= $order->id;

        $order = OrdersMototaxi::
        join('users','users.id', '=','orders_mototaxis.user_id')
        ->join('deliveries_costs', 'deliveries_costs.id', '=', 'orders_mototaxis.delivery_cost_id')
        ->join('rols', 'users.rol_id', '=', 'rols.id')
        ->join('localities as localityF', 'localityF.id', '=', 'orders_mototaxis.locality_from_id')
        ->join('localities as localityT', 'localityT.id', '=', 'orders_mototaxis.locality_to_id')
        ->select('orders_mototaxis.id as order','orders_mototaxis.code','orders_mototaxis.cell','orders_mototaxis.address_from',
        'localityF.name as locality_from','localityT.name as locality_to','orders_mototaxis.address_to',
        'orders_mototaxis.state','deliveries_costs.tranpostation_cost','users.id as user','users.name','users.email')
        ->where('orders_mototaxis.id',[$id])
        ->whereNull('orders_mototaxis.deleted_at')
        ->get();


        $messenger = Messenger::
        join('orders_mototaxis','orders_mototaxis.messenger_id','=','messengers.id')->
        select('messengers.*')->
        where('orders_mototaxis.id',[$id])->
        whereNotNull('orders_mototaxis.messenger_id')->
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
        $module =  'ABRAMOTOTAXI';

        $v_order = new StoreOrderMototaxiPost();
        $validator = $request->validate($v_order->rules());
        $cadena = Str::random(5);
        if ($validator) {
            $order = new OrdersMototaxi();
            $order->code = $module . $cadena;
            $order->locality_from_id = $request['locality_from'];
            $order->cell = $request['cell_from'];
            $order->address_from = $request['adress_from'];
            $order->locality_to_id = $request['locality_to'];
            $order->address_to = $request['adress_to'];
            $order->state = 'nueva';
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

             //mandar un email al mensajero con los datos de la orden

            if($order->state = 'nueva'){

                $result = $this->sendEmail($order);
                if(empty($result)){

                    return $this->successResponse(['order' => $order,'message'=>'Order new is created successfully.']);
                }

            }
             return $this->successResponse(['order' => $order,'message'=>'Order new is created successfully.']);

        return response()->json([
            'message' => 'Error al validar'
        ], 201);
    }
    }

     //funcion para enviar mensaje al usuario
     public function sendEmail($order){

        $orderasignada = OrdersMototaxi::findOrFail($order->id);
        $usuario= User::findOrFail($order->user_id);
        $localityF = Locality::findOrFail($order->locality_from_id);
        $localityT = Locality::findOrFail($order->locality_to_id);
        $costDelivery = DeliveriesCost::findOrFail($order->delivery_cost_id);
        $municipalityF= Municipie::findOrFail($costDelivery->from_municipality_id);
        $municipalityT= Municipie::findOrFail($costDelivery->to_municipality_id);
        $title = 'Su Orden Ha sido creada!!!! Gracias por elegir Abracadabra';

        $customer_details = [
        'name' => $usuario->get('name'),
        'email' => $usuario->get('email')
        ];
        $order_details = [
             'Codigo' => $orderasignada->get('code'),
             'Municipio de Origen' => $municipalityF->get('name'),
             'Localidad de Origen' => $localityF->get('name'),
             'Dirección de Origen' => $orderasignada->get('address_from'),
             'Teléfono' => $orderasignada->get('cell'),
             'Localidad Destino' => $localityT->get('name'),
             'Municipio Destino' => $municipalityT->get('name'),
             'Dirección Destino' => $orderasignada->get('address_to'),
             'Costo de Transportacion' => $costDelivery->get('tranpostation_cost'),

        ];

           $sendmail = Mail::to($customer_details['email'])
           ->send(new SendMailMototaxi($title, $customer_details,$order_details));
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
     * @param  \App\OrdersMototaxi  $orderMototaxi
     * @return \Illuminate\Http\Response
     */
    public function show(OrdersMototaxi $orderMototaxi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OrdersMototaxi  $orderMototaxi
     * @return \Illuminate\Http\Response
     */
    public function edit(OrdersMototaxi $orderMototaxi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OrdersMototaxi  $orderMototaxi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrdersMototaxi $order)
    {
        $v_order = new StoreOrderMototaxiPut();
        $validator = $request->validate($v_order->rules());
        if ($validator) {

            if($order->messenger_id != null){
                $mesengerOld = Messenger::findOrFail($order->messenger_id);
            }

            $order->locality_from_id = $request['locality_from'];
            $order->cell = $request['cell_from'];
            $order->address_from = $request['adress_from'];
            $order->locality_to_id = $request['locality_to'];
            $order->address_to = $request['adress_to'];
            $order->state = $request['state'];
            $order->user_id = $request['user_id'];
            $order->messenger_id = $request['messenger_id'];
            $order->message_cancel = $request['message_cancel'];
            $order->save();

             //mandar un email al mensajero con los datos de la orden

             if($request->state == 'asignada'){

                if($mesengerOld != $order->messenger_id){

                    $result =  $this->sendEmailCancelOrAsigned($order);
                if(empty($result)){

                    return $this->successResponse(['order' => $order,'message'=>'Order asigned successfully.']);
                }
                }

                $result =  $this->sendEmailCancelOrAsigned($order);
                if(empty($result)){

                    return $this->successResponse(['order' => $order,'message'=>'Order asigned successfully.']);
                }
            }
            if($request->state == 'cancelada' ){

                $result = $this->sendEmailCancelOrAsigned($order);
                if(empty($result)){
                    return $this->successResponse(['order' => $order,'message'=>'Order cancel successfully.']);
                }
            }


        return response()->json([
            'message' => 'Error al validar'
        ], 201);
    }
    }

    //funcion para enviar mensaje al mensajero cuando la orden es asignada
    public function sendEmailCancelOrAsigned ($order) {

        $orderasignada = OrdersMototaxi::findOrFail($order->id);
        $mensajero = Messenger::findOrFail($order->messenger_id);
        $orderasignada = OrdersMototaxi::findOrFail($order->id);
        $localityF = Locality::findOrFail($order->locality_from_id);
        $localityT = Locality::findOrFail($order->locality_to_id);
        $costDelivery = DeliveriesCost::findOrFail($order->delivery_cost_id);
        $municipalityF= Municipie::findOrFail($costDelivery->from_municipality_id);
        $municipalityT= Municipie::findOrFail($costDelivery->to_municipality_id);
        $title = 'Le ha sido asignada una nueva orden';

        $customer_details = [
        'name' => $mensajero->get('name'),
        'email' => $mensajero->get('email')
        ];
        $order_details = [
            'Codigo' => $orderasignada->get('code'),
            'Municipio de Origen' => $municipalityF->get('name'),
            'Localidad de Origen' => $localityF->get('name'),
            'Dirección de Origen' => $orderasignada->get('address_from'),
            'Teléfono' => $orderasignada->get('cell'),
            'Localidad Destino' => $localityT->get('name'),
            'Municipio Destino' => $municipalityT->get('name'),
            'Dirección Destino' => $orderasignada->get('address_to'),
            'Costo de Transportacion' => $costDelivery->get('tranpostation_cost'),
            'Message_Cancel' => $orderasignada->get('message_cancel'),
        ];

          if($orderasignada->state == 'asignada'){
            $sendmail = Mail::to($customer_details['email'])
            ->send(new SendMailMessengerMototaxi($title, $customer_details,$order_details));
            if (empty($sendmail)) {
              return response()->json(['message'
              => 'Mail Sent Sucssfully'], 200);
              }else{
                  return response()->json(['message' => 'Mail Sent fail'], 400);
                 }

          }if($orderasignada->state == 'cancelada'){
            $sendmail = Mail::to($customer_details['email'])
            ->send(new SendMailOrderCancelMototaxi($title, $customer_details,$order_details));
            if (empty($sendmail)) {
              return response()->json(['message'
              => 'Mail Sent Sucssfully'], 200);
              }else{
                  return response()->json(['message' => 'Mail Sent fail'], 400);
                 }
          }

          else{
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
     * @param  \App\OrdersMototaxi  $orderMototaxi
     * @return \Illuminate\Http\Response
     */
    public function destroyMototaxi(Request $request)
    {
        $orderMototaxi = OrdersMototaxi::findOrFail($request['id']);
        $orderMototaxi->delete();
        return $this->successResponse(['message'=>'Order deleted successfully.']);
    }
}