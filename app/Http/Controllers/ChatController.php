<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Message, Chat};
use App\Events\MessageSent;

class ChatController extends Controller
{
    public function chatrooms()
    {
        return Chat::all();
    }

    /**
     * Fetch all messages
     *
     * @return Message
     */
    public function fetchMessages($chat_id)
    {
    return Message::where('chat_id', $chat_id)->with('user')->orderBy('created_at', 'DESC')->get();
    }

    /**
     * Persist message to database
     *
     * @param  Request $request
     * @return Response
     */
    public function sendMessage($chat_id)
    {
    $user = auth()->user();

    $message = $user->messages()->create([
        'text' => request()->text,
        'user_id' => $user->id,
        'chat_id' => $chat_id
    ]);

    broadcast(new MessageSent($message))->toOthers();

    return $message;
    }
}
