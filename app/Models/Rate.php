<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\{Advisor, User};

class Rate extends Model
{
    protected $guarded = [];
    /**
     * Get the advisor that owns the Rate
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function advisor()
    {
        return $this->belongsTo(Advisor::class, 'advisor_id');
    }

    /**
     * Get the user that owns the Rate
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
