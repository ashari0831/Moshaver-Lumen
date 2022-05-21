<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\{User, Request, Rate, Advisor_history, Advisor_document};

class Advisor extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get all of the requests for the Advisor
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function requests()
    {
        return $this->hasMany(Request::class, 'receiver_id');
    }

    /**
     * Get all of the rates for the Advisor
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rates()
    {
        return $this->hasMany(Rate::class, 'advisor_id');
    }

    /**
     * Get all of the resumes for the Advisor
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function resumes()
    {
        return $this->hasMany(Advisor_history::class, 'advisor_id');
    }

    public function docs(){
        return $this->hasMany(Advisor_document::class, 'advisor_id');
    }
}
