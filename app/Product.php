<?php

namespace App;

use App\ProductImage;
use App\ProductCategory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name','code','description','stock','price','discount_percent','state','user_id','product_category_id'];

    public function category(){
        return $this->belongsTo(ProductCategory::class);
   }

   public function imageProduct(){
    return $this->hasOne(ProductImage::class);
}
}
