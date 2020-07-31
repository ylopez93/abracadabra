<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = ['name'];

    public function province()
    {
        return $this->hasMany(Province::class);
    }
}
