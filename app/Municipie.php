<?php

namespace App;

use App\Province;
use App\DeliveryCost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Municipie extends Model
{

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = ['name','price','province_id'];

    protected $hidden = ['created_at','updated_at','deleted_at'];

    public function province(){
        return $this->belongsTo(Province::class);
   }

   public function localities(){
    return $this->hasMany(Locality::class);
   }

    public function deliveries(){
        return $this->hasMany(DeliveryCost::class);
    }
}