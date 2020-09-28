<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rol extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = ['name'];

    protected $hidden = ['created_at','updated_at'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
