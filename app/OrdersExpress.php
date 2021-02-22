<?php

namespace App;

use App\User;
use App\Locality;
use App\Messenger;
use App\DeliveriesCost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdersExpress extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'code',
        'name_r',
        'address_r',
        'cell_r',
        'phone_r',
        'locality_id_r',
        'name_d',
        'locality_id_d',
        'address_d',
        'cell_d',
        'phone_d',
        'object_details',
        'weigth',
        'state',
        'message',
        'delivery_cost_id',
        'user_id',
        'messenger_id',
        'message_cancel'

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