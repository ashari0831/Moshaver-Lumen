<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Advisor;

class Advisor_document extends Model
{
    protected $guarded = [];

    /**
     * Get the advisor that owns the Advisor_document
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function advisor()
    {
        return $this->belongsTo(Advisor::class, 'advisor_id');
    }
}
