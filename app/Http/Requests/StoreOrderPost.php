<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderPost extends FormRequest
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

        'user_name'=> 'required|max:255',
        'user_phone'=> 'required|max:255',
        'user_address'=> 'required|max:255',
        'message'=> 'required|max:255',
        'pickup_time_from' => 'required',
        'pickup_time_to' => 'required',
        'message'=> 'required|max:255',

        ];



    }
}
