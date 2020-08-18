<?php

namespace App;

use App\Province;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Municipie extends Model
{

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = ['name','province_id'];

    public function province(){
        return $this->belongsTo(Province::class);
   }

   public function user(){
    return $this->hasMany(User::class);
}
}
