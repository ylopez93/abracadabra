<?php

namespace App\Http\Controllers\api;

use App\Messenger;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\api\ApiResponseController;
use App\Http\Requests\StoreMessengerPost;

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
        return $this->successResponse([$messengers,'Messengers retrieved successfully.']);
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

           $filename = time() .".". $request->image->extension();
           $request->image->move(public_path('images'),$filename);
           $messenger->image = $filename;
           $messenger->save();

        // $product = Product::create($request);
        return $this->successResponse([$messenger, 'Messenger created successfully.']);

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
            return $this->errorResponse('Messenger  not found.');
        }

        return $this->successResponse([$messenger,'Messenger retrieved successfully.']);
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
        if ($request->hasFile('file')) {

            $filename = time() .".". $request->image->extension();
            $request->image->move(public_path('images'),$filename);
            $messenger->image = $filename;

        }

        $messenger->save();

        return $this->successResponse([$messenger, 'Messenger updated successfully.']);
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
        return $this->successResponse('Messenger deleted successfully.');
    }
}
