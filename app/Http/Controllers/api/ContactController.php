<?php

namespace App\Http\Controllers\api;

use App\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\api\ApiResponseController;
use App\Http\Requests\StoreContactPost;

class ContactController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = Contact::all();
        return $this->successResponse([$contacts,'Messengers retrieved successfully.']);
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
        $v_contact = new StoreContactPost();
        $validator = $request->validate($v_contact->rules());
        if($validator){
           $contacts = new Contact();
           $contacts->location = $request['location'];
           $contacts->email = $request['email'];
           $contacts->phone = $request['phone'];
           $contacts->movil_phone = $request['movil_phone'];
           $contacts->description = $request['description'];
           $contacts->latitude = $request['latitude'];
           $contacts->longitude = $request['longitude'];
           $contacts->price_first_km = $request['price_first_km'];
           $contacts->price_km = $request['price_km'];
           $contacts->save();

        // $product = Product::create($request);
        return $this->successResponse([$contacts, 'Contact created successfully.']);

        }
        return response()->json([
            'message' => 'Error al validar'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contact = Contact::find($id);

        if(is_null($contact)){
            return $this->successResponse('Contact  not found.');
        }

        return $this->successResponse([$contact,'Contact retrieved successfully.']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        $v_contact = new StoreContactPost();
        $validator = $request->validate($v_contact->rules());
        if($validator){
        $contact->location = $request['location'];
        $contact->email = $request['email'];
        $contact->phone = $request['phone'];
        $contact->movil_phone = $request['movil_phone'];
        $contact->description = $request['description'];
        $contact->latitude = $request['latitude'];
        $contact->longitude = $request['longitude'];
        $contact->price_first_km = $request['price_first_km'];
        $contact->price_km = $request['price_km'];
        $contact->save();
        return $this->successResponse([$contact, 'Contact updated successfully.']);
        }
        return response()->json([
            'message' => 'Error al validar'
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
        return $this->successResponse('Contact deleted successfully.');
    }
}
