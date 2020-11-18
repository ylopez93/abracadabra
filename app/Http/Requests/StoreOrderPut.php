<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderPut extends FormRequest
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

        'delivery_time_to'=> 'required',
        'delivery_time_from'=> 'required',
        'pickup_date' => 'required|date',
        'state'=> 'required',
        //'payment_state'=> 'required',
        'messenger_id' => 'required',
        'transportation_cost' => 'required',

        ];
    }
}
