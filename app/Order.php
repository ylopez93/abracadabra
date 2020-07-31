<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'code','user_name',
        'user_phone',
        'user_address',
        'pickup_date',
        'pickup_time_from',
        'pickup_time_to',
        'message',
        'state',
        'payment_type',
        'payment_state',
        'delivery_type',
        'messenger_id',
        'municipie_id',
        'user_id',
        'transportation_cost'
    ];

    public function orderProduct()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function messenger()
    {
        return $this->belongsTo(Messenger::class);
    }

}
