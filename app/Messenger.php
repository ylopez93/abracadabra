<?php

namespace App;

use App\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Messenger extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name', 'surname', 'ci','vehicle_registration','image',
    ];

    public function order()
    {
        return $this->hasMany(Order::class);
    }
}
