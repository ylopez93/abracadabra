<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessengerPost extends FormRequest
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

            'name'=> 'required|string|max:255',
            'surname'=> 'required|string|max:255',
            'ci'=> 'required|string|max:11',
            'phone'=> 'required|string|max:255',
            'address'=> 'required|string|max:255',

        ];
    }
}