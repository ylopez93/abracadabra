<?php

namespace App;

use App\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $hidden = ['created_at','updated_at'];

    protected $fillable = ['latitude','longitude','order_code'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }


}
