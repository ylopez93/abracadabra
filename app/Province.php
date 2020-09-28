<?php

namespace App;

use App\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Province extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = ['name','country_id'];

    protected $hidden = ['created_at','updated_at'];

    public function country(){
        return $this->belongsTo(Country::class);

   }

   public function municipie(){
    return $this->hasMany(Municipie::class);
}
}
