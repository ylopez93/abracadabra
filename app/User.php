<?php

namespace App;

use App\Rol;
use App\Messenger;
use App\Municipie;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name', 'email', 'password','rol_id','active'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','created_at','updated_at'
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'id' =>$this->id,
            'name' =>$this->name,
            'email' =>$this->email,
            'rol_id' =>$this->rol_id,
        ];
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }

    public function userProduct(){
        return $this->hasMany(UserProduct::class);
    }

    public function messenger()
    {
        return $this->hasOne(Messenger::class);
    }
}
