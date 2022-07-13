<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Advisor;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Advisor_document extends Model
{
    use HasFactory;
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
