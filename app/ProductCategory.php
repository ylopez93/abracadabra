<?php

namespace App;

use App\Product;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{

    protected $fillable = ['name','image'];

    public function product(){
        return $this->hasMany(Product::class);
    }
}
