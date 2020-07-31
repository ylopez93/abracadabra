<?php

namespace App;

use App\ProductImage;
use App\ProductCategory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'code', 'description', 'stock', 'price', 'discount_percent', 'state', 'product_category_id'];


    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function image()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userProduct()
    {
        return $this->hasMany(UserProduct::class);
    }

    public function orderProduct()
    {
        return $this->hasMany(OrderProduct::class);
    }


}
