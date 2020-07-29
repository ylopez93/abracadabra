<?php

namespace App;

use App\Product;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ['product_id','name'];

    public function productImage(){
         return $this->belongsTo(Product::class);
    }

}
