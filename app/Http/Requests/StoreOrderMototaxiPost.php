<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderMototaxiPost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

            'locality_from'=> 'required',
            'cell_from'=> 'required|max:255',
            'adress_from'=> 'required|max:255',
            'from_municipality_id'=> 'required',
            'to_municipality_id'=> 'required',
            'locality_to' => 'required',
            'adress_to' => 'required|max:255',
            'state' => 'required',
            'delivery_cost_id'=> 'required',

        ];
    }
}