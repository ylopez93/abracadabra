<?php

namespace App;

use App\ProductImage;
use App\ProductCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'description', 'stock', 'price', 'discount_percent', 'state', 'product_category_id'];

    protected $hidden = ['created_at','updated_at'];

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
