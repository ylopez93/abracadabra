<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Locality extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = ['name','municipie_id'];

    protected $hidden = ['created_at','updated_at'];

    public function municipies(){
        return $this->belongsTo(Municipie::class);
   }

   public function orders(){
    return $this->hasMany(Order::class);
   }
}