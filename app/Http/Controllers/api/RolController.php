<?php

namespace App\Http\Controllers\api;

use App\Rol;
use App\Order;
use App\OrdersExpress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreRolPost;
use App\Http\Controllers\Controller;
use App\Http\Controllers\api\ApiResponseController;

class RolController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rol = Rol::all();
        return $this->successResponse([$rol,'Rol retrieved successfully.']);
    }

    public function findUsersByRol($rol){

        $users = DB::table('users')->select('*')
        ->where('rol_id','=',$rol)
        ->whereNull('deleted_at')
        ->get();

        return $this->successResponse([$users, 'Users retrieved successfully.']);

    }

    public function ordersFinished(){

        $orders = Order::
        join('users', 'users.id', '=', 'orders.user_id')
        ->join('rols', 'users.rol_id', '=', 'rols.id')
        ->join('deliveries_costs', 'deliveries_costs.id', '=', 'orders.delivery_cost_id')
        ->select('orders.id as order','orders.code','orders.user_name','orders.user_phone','orders.user_address',
        'orders.pickup_date','orders.pickup_time_from','orders.pickup_time_to','orders.message','orders.state',
        'orders.payment_type','orders.payment_state','deliveries_costs.tranpostation_cost','orders.message_cancel','users.name as user','users.id',
        'users.email','rols.name as rol')
        ->where('orders.state','=','cancelada')
        ->orWhere('orders.state','=','entregada')
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
        ->where('orders_expresses.state','=','cancelada')
        ->orWhere('orders_expresses.state','=','entregada')
        ->whereNull('orders_expresses.deleted_at')
        ->get();

        return $this->successResponse(['orders'=>$orders,'orders_express'=>$ordersExpress,'orders retrieved successfully.']);
    }

    public function ordersActive(){

        $orders = Order::
        join('users', 'users.id', '=', 'orders.user_id')
        ->join('rols', 'users.rol_id', '=', 'rols.id')
        ->join('deliveries_costs', 'deliveries_costs.id', '=', 'orders.delivery_cost_id')
        ->select('orders.id as order','orders.code','orders.user_name','orders.user_phone','orders.user_address',
        'orders.pickup_date','orders.pickup_time_from','orders.pickup_time_to','orders.message','orders.state',
        'orders.payment_type','orders.payment_state','deliveries_costs.tranpostation_cost','users.name as user','users.id',
        'users.email','rols.name as rol')
        ->where('orders.state','=','nueva')
        ->orWhere('orders.state','=','en_progreso')
        ->orWhere('orders.state','=','asignada')
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
        ->where('orders_expresses.state','=','nueva')
        ->orWhere('orders_expresses.state','=','en_progreso')
        ->orWhere('orders_expresses.state','=','asignada')
        ->whereNull('orders_expresses.deleted_at')
        ->get();

        return $this->successResponse(['orders'=>$orders,'orders_express'=>$ordersExpress,'orders retrieved successfully.']);
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
        $v_rol = new StoreRolPost();
        $validator = $request->validate($v_rol->rules());
        if($validator){
           $rol = new Rol();
           $rol->name = $request['name'];
           $rol->save();

        // $product = Product::create($request);
        return $this->successResponse([$rol, 'Rol created successfully.']);

        }
        return response()->json([
            'message' => 'Error al validar'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function show(Rol $rol)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function edit(Rol $rol)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rol $rol)
    {
        $v_rol = new StoreRolPost();
        $validator = $request->validate($v_rol->rules());
        if($validator){
           $rol->name = $request['name'];
           $rol->save();

        return $this->successResponse([$rol, 'Rol updated successfully.']);

        }
        return response()->json([
            'message' => 'Error al validar'
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rol $rol)
    {
        $rol->delete();
        return $this->successResponse('Rol deleted successfully.');
    }
}