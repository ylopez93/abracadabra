<?php

namespace App;

use App\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductImage extends Model
{

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = ['product_image_id','name'];

    protected $hidden = ['created_at','updated_at'];

    public function product(){
        return $this->belongsTo(Product::class);
   }

}
