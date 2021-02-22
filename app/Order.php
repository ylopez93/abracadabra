<?php

namespace App;

use App\User;
use App\Locality;
use App\Messenger;
use App\OrderProduct;
use App\DeliveriesCost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'code',
        'user_name',
        'user_phone',
        'user_address',
        'pickup_date',
        'pickup_time_from',
        'pickup_time_to',
        'delivery_time_to',
        'delivery_time_from',
        'message',
        'state',
        'payment_type',
        'payment_state',
        'delivery_type',
        'messenger_id',
        'user_id',
        'locality_id',
        'delivery_cost_id',
        'message_cancel'

    ];

    protected $hidden = ['created_at','updated_at'];

    public function orderProduct()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function messenger()
    {
        return $this->belongsTo(Messenger::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function locality()
    {
        return $this->belongsTo(Locality::class);
    }

    public function delivery()
    {
        return $this->belongsTo(DeliveriesCost::class);
    }


}
