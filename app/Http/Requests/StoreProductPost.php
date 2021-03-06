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
            'name'=> 'required|max:255',
            'description'=> 'required|max:255',
            'stock' => 'required|integer',
            'price' => 'required',
            'product_category_id' => 'required',
            //'image'=> 'required|mimes:jpeg,bmp,png|max:10240'
        ];
    }
}
