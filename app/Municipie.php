<?php

namespace App;

use App\Province;
use Illuminate\Database\Eloquent\Model;

class Municipie extends Model
{
    protected $fillable = ['name','province_id'];

    public function province(){
        return $this->belongsTo(Province::class);
   }

   public function user(){
    return $this->hasMany(User::class);
}
}
