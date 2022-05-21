<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\{Advisor, Request};
use Laravel\Scout\Searchable;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordInterface;
use Illuminate\Notifications\Notifiable;
use App\Traits\MustVerifyEmail;

class User extends Model implements AuthenticatableContract, AuthorizableContract,  JWTSubject, CanResetPasswordInterface
{
    use Authenticatable, Authorizable, HasFactory, Searchable, CanResetPasswordTrait, Notifiable, MustVerifyEmail;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $guarded = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    // protected $casts = [
    //     'email_confirmed_at' => 'datetime',
    // ];

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
        return [];
    }

    public function advisor()
    {
        return $this->hasOne(Advisor::class, 'user_id');
    }

    /**
     * Get all of the requests for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function requests()
    {
        return $this->hasMany(Request::class, 'sender_id');
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->only('first_name', 'last_name');

        // $related = $this->user->only('name', 'email');

        // Customize array...
        return $array;
        // return array_merge($array, $related);
    }

    // protected static function boot()
    // {
    //     parent::boot();
        
    //     static::saved(function ($model) {
    //         /**
    //          * If user email have changed email verification is required
    //          */
    //         if( $model->isDirty('email') ) {
    //             $model->setAttribute('email_confirmed_at', null);
    //             $model->sendEmailVerificationNotification();
    //         }
    //     });
    // }
}
