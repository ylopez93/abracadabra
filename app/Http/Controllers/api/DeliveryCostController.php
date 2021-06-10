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

    public static function locationByModule($module){

            $longitude_fromBD = DB::select('select contacts.longitude from contacts  where contacts.module = ?', [$module]);
            $longitude_from = $longitude_fromBD[0]->longitude;
            $latitude_fromBD = DB::select('select contacts.latitude from contacts  where contacts.module = ?', [$module]);
            $latitude_from = $latitude_fromBD[0]->latitude;
            return response()->json([
                'longitude_from'=>$longitude_from,
                'latitude_from'=>$latitude_from
            ]);


    }


    public static function transportationCost(Request $request){

        $priceTotal = 0;
        $price_Km = 0;
        $FirstKm = DB::select('select contacts.price_first_km from contacts');
        $price_FirstKm = $FirstKm[0]->price_first_km;
        $Km = DB::select('select contacts.price_km from contacts');
        $price_Km = $Km[0]->price_km;

        $distance_Km = $request['distance'] / 1000;

            if($distance_Km < 1){

                $priceTotal = $distance_Km * $price_Km;
            }
            if($distance_Km >= 1){

                $Km = ($distance_Km - 1) * $price_Km;
                $priceTotal = round($price_FirstKm + $Km);

            }
             return response()->json([
                'costoTransportacion'=>$priceTotal
            ]);
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
           $delivery->latitude_from = $request['latitude_from'];
           $delivery->longitude_from = $request['longitude_from'];
           $delivery->latitude_to = $request['latitude_to'];
           $delivery->longitude_to = $request['longitude_to'];
           $delivery->distance = $request['distance'];
           $delivery->duration = $request['duration'];
           $transportationCost = $this->transportationCost($request);
           $delivery->tranpostation_cost = $transportationCost;
           $delivery->save();

        // $product = Product::create($request);
        return $this->successResponse(['delivery'=>$delivery,'message'=>'Deliveries created successfully.']);

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
        return $this->successResponse(['message'=>'DeliveryCost deleted successfully.']);
    }
}