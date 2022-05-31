<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\{Chat, User};

class Chat_user extends Model
{
    protected $guarded = [];

    /**
     * Get the chat that owns the Chat_user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }

    /**
     * Get the user that owns the Chat_user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
}
