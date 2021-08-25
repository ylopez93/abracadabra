<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveriesCost extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'tranpostation_cost',
        'distance',
        'from_municipality_id',
        'to_municipality_id',
        'latitude_from',
        'longitude_from',
        'latitude_to',
        'longitude_to'
    ];

    protected $hidden = ['created_at','updated_at'];

    public function orders(){
        return $this->hasMany(Order::class);
       }

       public function municipie(){
        return $this->belongsTo(Municipie::class);
   }
}
