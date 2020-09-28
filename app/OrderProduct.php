<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderProduct extends Model
{

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = ['product_id','order_id','quantity','total'];

    protected $hidden = ['created_at','updated_at'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
