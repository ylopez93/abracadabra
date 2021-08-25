<?php

namespace App;

use App\ApplicationJob;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationJobImage extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = ['application_jobs_id','name'];

    protected $hidden = ['created_at','updated_at'];

    public function applicationJobs(){
        return $this->belongsTo(ApplicationJob::class);
   }

}
