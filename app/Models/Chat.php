<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Chat_user;

class Chat extends Model
{
    protected $guarded = [];

    /**
     * Get all of the chat_users for the Chat
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function chat_users()
    {
        return $this->hasMany(Chat_user::class, 'chat_id');
    }
}
