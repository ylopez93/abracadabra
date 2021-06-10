<?php

namespace App\Http\Controllers\api;

use App\User;
use App\Order;
use App\Messenger;
use App\OrderProduct;
use App\OrdersExpress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessengerPost;
use App\Http\Controllers\api\ApiResponseController;
use App\OrdersMototaxi;

class MessengerController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messengers = Messenger::all();
        return $this->successResponse(['messengers'=>$messengers,'message'=>'Messengers retrieved successfully.']);
    }

    public function odersAsigned(Request $request)
    {
        $messenger = DB::select('select messengers.id from messengers where messengers.user_id = ?', [$request['userId']]);
        if(empty($messenger)){

            return $this->successResponse(['message'=>'El mensajero que busca no existe.']);
        }
        $messengerId = $messenger[0]->id;

        $orders = Order::
        join('messengers', 'messengers.id', '=', 'orders.messenger_id')
        ->join('users', 'users.id', '=', 'orders.user_id')
        ->join('rols', 'users.rol_id', '=', 'rols.id')
        ->join('deliveries_costs', 'deliveries_costs.id', '=', 'orders.delivery_cost_id')
        ->select('orders.id as order','orders.code','orders.user_name','orders.user_phone','orders.user_address',
        'orders.pickup_date','orders.pickup_time_from','orders.pickup_time_to','orders.message','orders.state',
        'orders.payment_type','orders.payment_state','deliveries_costs.tranpostation_cost','users.name as user','users.id',
        'users.email','rols.name as rol','messengers.id as messenger_id')
        ->orderBy('orders.created_at', 'desc')
        ->where('orders.messenger_id',[$messengerId])
        ->where('orders.state','asignada')
        ->whereNull('orders.deleted_at')
        ->whereNull('messengers.deleted_at')
        ->get();

        $ordersExpress = OrdersExpress::
        join('messengers', 'messengers.id', '=', 'orders_expresses.messenger_id')
        ->join('users', 'users.id', '=', 'orders_expresses.user_id')
        ->join('rols', 'users.rol_id', '=', 'rols.id')
        ->join('deliveries_costs', 'deliveries_costs.id', '=', 'orders_expresses.delivery_cost_id')
        ->join('localities as localityR', 'localityR.id', '=', 'orders_expresses.locality_id_r')
        ->join('localities as localityD', 'localityD.id', '=', 'orders_expresses.locality_id_d')
        ->select('orders_expresses.id as order','orders_expresses.code','orders_expresses.name_r','orders_expresses.address_r','orders_expresses.cell_r',
        'orders_expresses.phone_r','localityR.name as locality_remitente','orders_expresses.name_d','localityD.name as locality_destinatario','orders_expresses.address_d',
        'orders_expresses.cell_d','orders_expresses.phone_d','orders_expresses.object_details','orders_expresses.weigth','orders_expresses.state','orders_expresses.message',
        'deliveries_costs.tranpostation_cost','users.id as user','users.name','users.email','rols.name as rol','messengers.id as messenger_id')
        ->orderBy('orders_expresses.created_at', 'desc')
        ->where('orders_expresses.state', 'asignada')
        ->where('orders_expresses.messenger_id',[$messengerId])
        ->whereNull('orders_expresses.deleted_at')
        ->whereNull('messengers.deleted_at')
        ->get();

        $ordersMototaxi = OrdersMototaxi::
        join('messengers', 'messengers.id', '=', 'orders_mototaxis.messenger_id')
        ->join('users', 'users.id', '=', 'orders_mototaxis.user_id')
        ->join('rols', 'users.rol_id', '=', 'rols.id')
        ->join('deliveries_costs', 'deliveries_costs.id', '=', 'orders_mototaxis.delivery_cost_id')
        ->join('localities as localityR', 'localityR.id', '=', 'orders_mototaxis.locality_from_id')
        ->join('localities as localityD', 'localityD.id', '=', 'orders_mototaxis.locality_to_id')
        ->select('orders_mototaxis.id as order','orders_mototaxis.code','orders_mototaxis.cell','orders_mototaxis.address_from',
        'localityR.name as locality_from','localityD.name as locality_to','orders_mototaxis.address_to',
        'orders_mototaxis.state','deliveries_costs.tranpostation_cost','users.id as user','users.name','users.email','rols.name as rol','messengers.id as messenger_id')
        ->orderBy('orders_mototaxis.created_at', 'desc')
        ->where('orders_mototaxis.state', 'asignada')
        ->where('orders_mototaxis.messenger_id',[$messengerId])
        ->whereNull('orders_mototaxis.deleted_at')
        ->whereNull('messengers.deleted_at')
        ->get();

        return $this->successResponse(['orders'=>$orders,'orders_express'=>$ordersExpress,'orders_mototaxi'=>$ordersMototaxi,'message'=>'Orders retrieved successfully.']);
    }

    public function ordersFinished(Request $request){

        $messenger = DB::select('select messengers.id from messengers where messengers.user_id = ?', [$request['userId']]);
        $messengerId = $messenger[0]->id;

        $orders = Order::
        join('messengers', 'messengers.id', '=', 'orders.messenger_id')
        ->join('users', 'users.id', '=', 'orders.user_id')
        ->join('rols', 'users.rol_id', '=', 'rols.id')
        ->join('deliveries_costs', 'deliveries_costs.id', '=', 'orders.delivery_cost_id')
        ->select('orders.id as order','orders.code','orders.user_name','orders.user_phone','orders.user_address',
        'orders.pickup_date','orders.pickup_time_from','orders.pickup_time_to','orders.message','orders.state',
        'orders.payment_type','orders.payment_state','deliveries_costs.tranpostation_cost','orders.message_cancel','users.name as user',
        'users.id','users.email','rols.name as rol','messengers.id as messenger_id')
        ->where([
            ['orders.messenger_id', '=', [$messengerId]],
            ['orders.state','=','cancelada'],
        ])->orWhere([
            ['orders.messenger_id', '=', [$messengerId]],
            ['orders.state','=','entregada']
        ])
        ->whereNull('orders.deleted_at')
        ->get();

        $ordersExpress = OrdersExpress::
        join('messengers', 'messengers.id', '=', 'orders_expresses.messenger_id')
        ->join('users', 'users.id', '=', 'orders_expresses.user_id')
        ->join('rols', 'users.rol_id', '=', 'rols.id')
        ->join('deliveries_costs', 'deliveries_costs.id', '=', 'orders_expresses.delivery_cost_id')
        ->join('localities as localityR', 'localityR.id', '=', 'orders_expresses.locality_id_r')
        ->join('localities as localityD', 'localityD.id', '=', 'orders_expresses.locality_id_d')
        ->select('orders_expresses.id as order','orders_expresses.code','orders_expresses.name_r','orders_expresses.address_r','orders_expresses.cell_r',
        'orders_expresses.phone_r','localityR.name as locality_remitente','orders_expresses.name_d','localityD.name as locality_destinatario','orders_expresses.address_d',
        'orders_expresses.cell_d','orders_expresses.phone_d','orders_expresses.object_details','orders_expresses.weigth','orders_expresses.state','orders_expresses.message',
        'deliveries_costs.tranpostation_cost','users.id as user','users.name','users.email','rols.name as rol','messengers.id as messenger_id')
        ->where([
            ['orders_expresses.messenger_id', '=', [$messengerId]],
            ['orders_expresses.state','=','cancelada'],
        ])->orWhere([
            ['orders_expresses.messenger_id', '=', [$messengerId]],
            ['orders_expresses.state','=','entregada']
        ])
        ->whereNull('orders_expresses.deleted_at')
        ->get();

        $ordersMototaxi = OrdersMototaxi::
        join('messengers', 'messengers.id', '=', 'orders_mototaxis.messenger_id')
        ->join('users', 'users.id', '=', 'orders_mototaxis.user_id')
        ->join('rols', 'users.rol_id', '=', 'rols.id')
        ->join('deliveries_costs', 'deliveries_costs.id', '=', 'orders_mototaxis.delivery_cost_id')
        ->join('localities as localityR', 'localityR.id', '=', 'orders_mototaxis.locality_from_id')
        ->join('localities as localityD', 'localityD.id', '=', 'orders_mototaxis.locality_to_id')
        ->select('orders_mototaxis.id as order','orders_mototaxis.code','orders_mototaxis.cell','orders_mototaxis.address_from',
        'localityR.name as locality_from','localityD.name as locality_to','orders_mototaxis.address_to',
        'orders_mototaxis.state','deliveries_costs.tranpostation_cost','users.id as user','users.name','users.email','rols.name as rol','messengers.id as messenger_id')
        ->where([
            ['orders_mototaxis.messenger_id', '=', [$messengerId]],
            ['orders_mototaxis.state','=','cancelada'],
        ])->orWhere([
            ['orders_mototaxis.messenger_id', '=', [$messengerId]],
            ['orders_mototaxis.state','=','entregada']
        ])
        ->whereNull('orders_mototaxis.deleted_at')
        ->get();

        return $this->successResponse(['orders'=>$orders,'orders_express'=>$ordersExpress,'orders_mototaxi'=>$ordersMototaxi,'message'=>'orders retrieved successfully.']);
    }

    public function ordersActive(Request $request){

        $messenger = DB::select('select messengers.id from messengers where messengers.user_id = ?', [$request['userId']]);
        $messengerId = $messenger[0]->id;

        $orders = Order::
        join('messengers', 'messengers.id', '=', 'orders.messenger_id')
        ->join('users', 'users.id', '=', 'orders.user_id')
        ->join('rols', 'users.rol_id', '=', 'rols.id')
        ->join('deliveries_costs', 'deliveries_costs.id', '=', 'orders.delivery_cost_id')
        ->select('orders.id as order','orders.code','orders.user_name','orders.user_phone','orders.user_address',
        'orders.pickup_date','orders.pickup_time_from','orders.pickup_time_to','orders.message','orders.state',
        'orders.payment_type','orders.payment_state','deliveries_costs.tranpostation_cost','users.name as user','users.id',
        'users.email','rols.name as rol','messengers.id as messenger_id')
        ->where('orders.messenger_id',[$messengerId])
        ->Where('orders.state','=','en_progreso')
        ->whereNull('orders.deleted_at')
        ->get();

        $ordersExpress = OrdersExpress::
        join('messengers', 'messengers.id', '=', 'orders_expresses.messenger_id')
        ->join('users', 'users.id', '=', 'orders_expresses.user_id')
        ->join('rols', 'users.rol_id', '=', 'rols.id')
        ->join('deliveries_costs', 'deliveries_costs.id', '=', 'orders_expresses.delivery_cost_id')
        ->join('localities as localityR', 'localityR.id', '=', 'orders_expresses.locality_id_r')
        ->join('localities as localityD', 'localityD.id', '=', 'orders_expresses.locality_id_d')
        ->select('orders_expresses.id as order','orders_expresses.code','orders_expresses.name_r','orders_expresses.address_r','orders_expresses.cell_r',
        'orders_expresses.phone_r','localityR.name as locality_remitente','orders_expresses.name_d','localityD.name as locality_destinatario','orders_expresses.address_d',
        'orders_expresses.cell_d','orders_expresses.phone_d','orders_expresses.object_details','orders_expresses.weigth','orders_expresses.state','orders_expresses.message',
        'deliveries_costs.tranpostation_cost','users.id as user','users.name','users.email','rols.name as rol','messengers.id as messenger_id')
        ->where('orders_expresses.messenger_id',[$messengerId])
        ->where('orders_expresses.state','=','en_progreso')
        ->whereNull('orders_expresses.deleted_at')
        ->get();

        $ordersMototaxi = OrdersMototaxi::
        join('messengers', 'messengers.id', '=', 'orders_mototaxis.messenger_id')
        ->join('users', 'users.id', '=', 'orders_mototaxis.user_id')
        ->join('rols', 'users.rol_id', '=', 'rols.id')
        ->join('deliveries_costs', 'deliveries_costs.id', '=', 'orders_mototaxis.delivery_cost_id')
        ->join('localities as localityR', 'localityR.id', '=', 'orders_mototaxis.locality_from_id')
        ->join('localities as localityD', 'localityD.id', '=', 'orders_mototaxis.locality_to_id')
        ->select('orders_mototaxis.id as order','orders_mototaxis.code','orders_mototaxis.cell','orders_mototaxis.address_from',
        'localityR.name as locality_from','localityD.name as locality_to','orders_mototaxis.address_to',
        'orders_mototaxis.state','deliveries_costs.tranpostation_cost','users.id as user','users.name','users.email','rols.name as rol','messengers.id as messenger_id')
        ->where('orders_mototaxis.messenger_id',[$messengerId])
        ->where('orders_mototaxis.state','=','en_progreso')
        ->whereNull('orders_mototaxis.deleted_at')
        ->get();

        return $this->successResponse(['orders'=>$orders,'orders_express'=>$ordersExpress,'orders_mototaxi'=>$ordersMototaxi,'message'=>'orders retrieved successfully.']);
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
        $user = DB::select('select users.id from users where users.email = ?', [$request['email']]);
        $user_id = $user[0]->id;
        $v_messenger = new StoreMessengerPost();
        $validator = $request->validate($v_messenger->rules());
        if($validator){
           $messenger = new Messenger();
           $messenger->name = $request['name'];
           $messenger->surname = $request['surname'];
           $messenger->ci = $request['ci'];
           $messenger->phone = $request['phone'];
           $messenger->email = $request['email'];
           $messenger->address = $request['address'];
           $messenger->vehicle_registration = $request['vehicle_registration'];
           $messenger->user_id = $user_id;

           $filename = time() .".". $request->image->extension();
           $request->image->move(public_path('images'),$filename);
           $messenger->image = $filename;
           $messenger->save();

           //modificar el rol en la tabla user
           $userUpdate = User::findOrFail($messenger->user_id);
           $userUpdate->rol_id = '3';
           $userUpdate->save();

        return $this->successResponse(['messenger'=>$messenger,'message'=> 'Messenger created successfully.']);

        }
        return response()->json([
            'message' => 'Error al validar'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Messenger  $messenger
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $messenger = Messenger::find($id);

        if(is_null($messenger)){
            return $this->successResponse(['message'=>'Messenger  not found.']);
        }

        return $this->successResponse(['messenger'=>$messenger,'message'=>'Messenger retrieved successfully.']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Messenger  $messenger
     * @return \Illuminate\Http\Response
     */
    public function edit(Messenger $messenger)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Messenger  $messenger
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Messenger $messenger)
    {
        $v_messenger = new StoreMessengerPost();
        $validator = $request->validate($v_messenger->rules());

       if($validator){
        $messenger->name = $request['name'];
        $messenger->surname = $request['surname'];
        $messenger->ci = $request['ci'];
        $messenger->phone = $request['phone'];
        $messenger->email = $request['email'];
        $messenger->address = $request['address'];
        $messenger->vehicle_registration = $request['vehicle_registration'];
        $messenger->user_id = $request['user_id'];

        if ($request->hasFile('image')) {

            $filename = time() .".". $request->image->extension();
            $request->image->move(public_path('images'),$filename);
            $messenger->image = $filename;

        }

        $messenger->save();

        return $this->successResponse(['messenger'=>$messenger,'message'=> 'Messenger updated successfully.']);
        }
        return response()->json([
            'message' => 'Error al validar'
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Messenger  $messenger
     * @return \Illuminate\Http\Response
     */
    public function destroy(Messenger $messenger)
    {
        $messenger->delete();
        return $this->successResponse(['message'=>'Messenger deleted successfully.']);

   }
}