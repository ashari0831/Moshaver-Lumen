<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\{User, Advisor};

class Request extends Model
{
    /**
     * Get the user that owns the Request
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the advisor that owns the Request
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function advisor()
    {
        return $this->belongsTo(Advisor::class, 'receiver_id');
    }
}
