<?php

namespace App\Http\Controllers\api;

use App\ProductCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductCategoryController extends ApiResponseController
{
    public function index(){

        return $this->successResponse(ProductCategory::all());

    }
}
