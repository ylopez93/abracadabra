<?php

namespace App\Http\Controllers\api;

use App\DeliveriesCost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDeliveryCostPost;
use App\Http\Controllers\api\ApiResponseController;

class DeliveryCostController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public static function transportationCost(Request $request){

        $priceTotal = 0;

       if($request['to_municipality_id'] == 0){
            return 0;
        }
         elseif($request['from_municipality_id'] > 0 & $request['to_municipality_id'] > 0){

            $priceFrom = DB::select('select municipies.price from municipies where municipies.id = ?', [$request['from_municipality_id']]);
            $priceTo = DB::select('select municipies.price from municipies where municipies.id = ?', [$request['to_municipality_id']]);
            $priceTotal = $priceFrom[0]->price + $priceTo[0]->price;

        }
        else{
             $priceTo = DB::select('select municipies.price from municipies where municipies.id = ?', [$request['to_municipality_id']]);
            $priceTotal = $priceTo[0]->price;
        }

        return $priceTotal;
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

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DeliveriesCost  $deliveryCost
     * @return \Illuminate\Http\Response
     */
    public function show(DeliveriesCost $deliveryCost)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DeliveriesCost  $deliveryCost
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,DeliveriesCost $deliveryCost)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DeliveriesCost  $deliveryCost
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DeliveriesCost $deliveryCost)
    {
        $v_delivery = new StoreDeliveryCostPost();
        $validator = $request->validate($v_delivery->rules());
        if($validator){
           $delivery = new DeliveriesCost();
           $delivery->from_municipality_id = $request['from_municipality_id'];
           $delivery->to_municipality_id = $request['to_municipality_id'];
           $transportationCost = $this->transportationCost($request['from_municipality_id'],$request['to_municipality_id']);
           $delivery->tranpostation_cost = $transportationCost;
           $delivery->save();

        // $product = Product::create($request);
        return $this->successResponse([$delivery,'Deliveries created successfully.']);

        }
        return response()->json([
            'message' => 'Error al validar'
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DeliveriesCost  $deliveryCost
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeliveriesCost $deliveryCost)
    {
        $deliveryCost->delete();
        return $this->successResponse('Municipie deleted successfully.');
    }
}