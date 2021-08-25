<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = ['location,email,phone,movil_phone,description,latitude,longitude,price_first_km,price_km'];

    protected $hidden = ['created_at','updated_at'];

}