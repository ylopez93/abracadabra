<?php

namespace App;

use App\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = ['name','image','module'];

    protected $hidden = ['created_at','updated_at'];

    public function product(){
        return $this->hasMany(Product::class);
    }

}
