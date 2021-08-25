<?php

namespace App\Http\Controllers\api;

use App\User;
use App\Locality;
use App\Messenger;
use App\DeliveriesCost;
use App\OrdersLoquesea;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\SendMailLoquesea;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailMessengerLoquesea;
use App\Mail\SendMailMessengerReasigned;
use App\Mail\SendMailOrderCancelLoquesea;
use App\Http\Requests\StoreDeliveryCostPost;
use App\Http\Requests\StoreOrderLoqueseaPut;
use App\Http\Requests\StoreOrderLoqueseaPost;

class OrdersLoqueseaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = OrdersLoquesea::
        join('users','users.id', '=','orders_loqueseas.user_id')
        ->join('deliveries_costs', 'deliveries_costs.id', '=', 'orders_loqueseas.delivery_cost_id')
        ->join('rols', 'users.rol_id', '=', 'rols.id')
        ->join('localities', 'localities.id', '=', 'orders_loqueseas.locality_id_d')
        ->select('orders_loqueseas.id as order','orders_loqueseas.code','orders_loqueseas.from','orders_loqueseas.to',
        'orders_loqueseas.phone','orders_loqueseas.pedido','orders_loqueseas.state','orders_loqueseas.message','localities.name',
        'users.id as user','users.name','users.email')
        ->whereNull('orders_loqueseas.deleted_at')
        ->get();

        return $this->successResponse(['orders'=>$orders,'message'=>'Orders retrieved successfully.']);
    }

    public function orderDetails(OrdersLoquesea $order)
    {
        $id= $order->id;

        $order = OrdersLoquesea::
        join('users','users.id', '=','orders_loqueseas.user_id')
        ->join('deliveries_costs', 'deliveries_costs.id', '=', 'orders_loqueseas.delivery_cost_id')
        ->join('rols', 'users.rol_id', '=', 'rols.id')
        ->join('localities', 'localities.id', '=', 'orders_loqueseas.locality_id_d')
        ->select('orders_loqueseas.id as order','orders_loqueseas.code','orders_loqueseas.from','orders_loqueseas.lonlat_to','orders_loqueseas.to',
        'orders_loqueseas.phone','orders_loqueseas.pedido','orders_loqueseas.state','orders_loqueseas.message','localities.name',
        'users.id as user','users.name','users.email')
        ->where('orders_loqueseas.id',[$id])
        ->whereNull('orders_loqueseas.deleted_at')
        ->get();


        $messenger = Messenger::
        join('orders_loqueseas','orders_loqueseas.messenger_id','=','messengers.id')->
        select('messengers.*')->
        where('orders_loqueseas.id',[$id])->
        whereNotNull('orders_loqueseas.messenger_id')->
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
        $arrayLocationsTo = explode(';',$string_to);
        $module =  'ABRALOQUESEA';

        $v_order = new StoreOrderLoqueseaPost();
        $validator = $request->validate($v_order->rules());
        $cadena = Str::random(5);
        if ($validator) {
            $order = new OrdersLoquesea();
            $order->code = $module . $cadena;
            $order->from = $request['from'];
            $order->to = $request['to'];
            $order->phone = $request['phone'];
            $order->pedido = $request['pedido'];
            $order->state = 'nueva';
            $order->message = $request['message'];
            $order->message_cancel = $request['message_cancel'];
            $order->locality_id_d = $request['locality_id_d'];
            $order->user_id = $request['user_id'];
            $order->messenger_id = $request['messenger_id'];


            $localityD = Locality::findOrFail($order->locality_id_d);
            $to_municipality_id = $localityD->municipie_id;

            $v_delivery = new StoreDeliveryCostPost();
            $validator = $request->validate($v_delivery->rules());
            if($validator){
                $delivery = new DeliveriesCost();
                $delivery->to_municipality_id = $to_municipality_id;
                $delivery->longitude_to = $arrayLocationsTo[0];
                $delivery->latitude_to = $arrayLocationsTo[1];
                $delivery->tranpostation_cost = $request['costo'];
                $delivery->save();

            }
            $order->delivery_cost_id = $delivery->id;
            $order->save();

            // mandar un email al mensajero con los datos de la orden

            if($order->state = 'nueva'){

                $result = $this->sendEmail($order);
                if(empty($result)){

                    return $this->successResponse(['order' => $order,'message'=>'Order new is created successfully.']);
                }

            }

        return response()->json([
            'message' => 'Error al validar'
        ], 201);
    }
  }


   //funcion para enviar mensaje al usuario
     public function sendEmail($order){

        $orderasignada = OrdersLoquesea::findOrFail($order->id);
        $usuario= User::findOrFail($order->user_id);
        $localityD = Locality::findOrFail($order->locality_id_d);
        $costDelivery = DeliveriesCost::findOrFail($order->delivery_cost_id);
        $title = 'Su Orden Ha sido creada!!!! Gracias por elegir Abracadabra';

        $customer_details = [
        'name' => $usuario->name,
        'email' => $usuario->email
        ];
        $order_details = [
             'Codigo' => $orderasignada->code,
             'Buscar en' => $orderasignada->from,
             'Entregar en' => $orderasignada->to,
             'Teléfono' => $orderasignada->phone,
             'Pedido' => $orderasignada->pedido,
             'Mensaje' => $orderasignada->message,
             'Localidad Destinatario' => $localityD->name,
        ];

           $sendmail = Mail::to($customer_details['email'])
           ->send(new SendMailLoquesea($title, $customer_details,$order_details));
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
     * @param  \App\OrdersLoquesea  $OrdersLoquesea
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = OrdersLoquesea::find($id);

        if (is_null($order)) {
            return $this->successResponse(['message'=>'Order not found.']);
        }

        return $this->successResponse(['order'=>$order,'message'=> 'Order retrieved successfully.']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OrdersLoquesea  $OrdersLoquesea
     * @return \Illuminate\Http\Response
     */
    public function edit(OrdersLoquesea $OrdersLoquesea)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OrdersLoquesea  $OrdersLoquesea
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrdersLoquesea $OrdersLoquesea)
    {
        $mesengerOld = null;
        $v_order = new StoreOrderLoqueseaPut();
        $validator = $request->validate($v_order->rules());
        if ($validator) {

            if($OrdersLoquesea->state == "en_progreso" ){

                if($request['state'] == 'terminada'){
                    $OrdersLoquesea->state = $request['state'];
                    $OrdersLoquesea->save();
                }

            }
            elseif($OrdersLoquesea->messenger_id != null){

                $mesengerOld = Messenger::findOrFail($OrdersLoquesea->messenger_id);

                $OrdersLoquesea->state = $request['state'];
                $OrdersLoquesea->message = $request['message'];
                $OrdersLoquesea->messenger_id = $request['messenger_id'];
                $OrdersLoquesea->message_cancel = $request['message_cancel'];
                $OrdersLoquesea->save();
            }

             //mandar un email al mensajero con los datos de la orden

             switch ($request->state) {

                case 'asignada':
                    if($mesengerOld == null){
                        $result =  $this->sendEmailCancelOrAsigned($OrdersLoquesea);
                        if(empty($result)){
                        return response()->json(['message'=>'Order asigned successfully.'], 201);
                        }
                    }else{
                        if($mesengerOld != null && $mesengerOld != $OrdersLoquesea->messenger_id){
                        $result = $this->sendEmailReasigned($OrdersLoquesea,$mesengerOld);
                        if(empty($result)){
                            return response()->json(['message'=>'Order has been reasigned successfully.'], 201);
                            }
                      }
                    }
                    break;

                case 'cancelada':
                    $result = $this->sendEmailCancelOrAsigned($OrdersLoquesea);
                    if(empty($result)){
                        return response()->json(['message'=>'Order has been canceled successfully.'], 201);
                        }
                    break;
            }

        return response()->json([
            'message' => 'Error al validar'
        ], 201);
    }
    }

     //funcion para enviar mensaje al mensajero cuando la orden es asignada
     public function sendEmailCancelOrAsigned ($OrdersLoquesea) {

        $orderasignada = OrdersLoquesea::findOrFail($OrdersLoquesea->id);
        $mensajero = Messenger::findOrFail($OrdersLoquesea->messenger_id);
        $email_mensajero = DB::select('select users.email from users where users.id = ?',[$mensajero->user_id]);
        $email = $email_mensajero[0]->email;
        $localityD = Locality::findOrFail($OrdersLoquesea->locality_id_d);
        $costDelivery = DeliveriesCost::findOrFail($OrdersLoquesea->delivery_cost_id);


        $customer_details = [
        'name' => $mensajero->name,
        'email' => $email
        ];

        $order_details = [
            'Codigo' => $orderasignada->code,
            'Lugar' => $orderasignada->from,
            'Direccion Destino' => $orderasignada->to,
            'Teléfono' => $orderasignada->phone,
            'Pedido' => $orderasignada->pedido,
            'Localidad Destinatario' => $localityD->name,
            'Costo' => $costDelivery->tranpostation_cost,
            'Mensaje' => $orderasignada->message,
            'Message_Cancel' => $orderasignada->message_cancel
       ];

       switch ($orderasignada->state) {
        case 'asignada':
            $title = 'Le ha sido asignada una nueva orden';

            $sendmail = Mail::to($customer_details['email'])
            ->send(new SendMailMessengerLoquesea($title, $customer_details,$order_details));
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
            ->send(new SendMailOrderCancelLoquesea($title, $customer_details,$order_details));
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
        $orderasignada = OrdersLoquesea::findOrFail($order->id);
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
     * @param  \App\OrdersLoquesea  $OrdersLoquesea
     * @return \Illuminate\Http\Response
     */
    public function destroyLoquesea(Request $request)
    {
        $orderLoquesea = OrdersLoquesea::findOrFail($request['id']);
        $orderLoquesea->delete();
        return $this->successResponse(['message'=>'Order deleted successfully.']);
    }
}