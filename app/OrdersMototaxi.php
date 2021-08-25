<?php

namespace App;

use App\User;
use App\Locality;
use App\Messenger;
use App\DeliveriesCost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdersMototaxi extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'code',
        'locality_from_id',
        'cell',
        'address_from',
        'locality_to_id',
        'address_to',
        'state',
        'delivery_cost_id',
        'message_cancel',
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