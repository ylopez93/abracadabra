<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderExpressPost extends FormRequest
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

        'name_r'=> 'required|max:255',
        'address_r'=> 'required|max:255',
        // 'cell_r'=> 'required|max:255',
        // 'phone_r'=> 'required|max:255',
        'name_d' => 'required',
        'address_d' => 'required',
        // 'cell_d'=> 'required|max:255',
        'object_details'=> 'required|max:255',

        ];
    }
}