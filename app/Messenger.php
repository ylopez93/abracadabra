<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Messenger extends Model
{
    protected $fillable = [
        'name', 'surname', 'ci','vehicle_registration','image',
    ];
}
