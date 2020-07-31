<?php

namespace App;

use App\Country;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $fillable = ['name','country_id'];

    public function country(){
        return $this->belongsTo(Country::class);

   }

   public function municipie(){
    return $this->hasMany(Municipie::class);
}
}
