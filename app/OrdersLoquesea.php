<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdersLoquesea extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'code',
        'from',
        'to',
        'phone',
        'pedido',
        'state',
        'message',
        'message_cancel',
        'delivery_cost_id',
        'locality_id_d',
        'user_id',
        'messenger_id'
    ];
    protected $hidden = ['created_at','updated_at'];

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