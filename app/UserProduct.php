<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserProduct extends Model
{
    protected $fillable = ['user_id','product_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
