<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductPost extends FormRequest
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
            'name'=> 'required|min:5|max:191',
            'code'=> 'required|max:255',
            'description'=> 'required|max:255',
            'stock' => 'required|max:8',
            'price' => 'required|max:8',
            'state' => 'required',
            'user_id' => 'required',
            'product_category_id' => 'required',

        ];
    }
}
