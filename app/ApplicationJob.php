<?php

namespace App;

use App\ApplicationJobImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationJob extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name', 'surname', 'ci','phone','email','address','vehicle_registration','user_image_id','vehicle_image_id','state','employee_type'
    ];

    protected $hidden = ['created_at','updated_at'];

    public function images()
    {
        return $this->hasMany(ApplicationJobImage::class);
    }

}