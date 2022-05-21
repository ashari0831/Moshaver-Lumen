<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Advisor;

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
}
