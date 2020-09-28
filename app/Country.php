<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $hidden = ['created_at','updated_at'];

    protected $fillable = ['name'];

    public function province()
    {
        return $this->hasMany(Province::class);
    }
}
