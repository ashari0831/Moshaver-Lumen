<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Message, Chat, User, Chat_user};
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

    public function particular_advisor_chats($advisor_user){
        $chat_users = Chat_user::where('user_id', $advisor_user)->with('chat')->get();

        $result = [];
        foreach ($chat_users as $cu){
            $contacts = User::whereHas('chat_users', function($q) use ($cu, $advisor_user){$q->where([['chat_id', $cu->chat->id], ['user_id', '!=', $advisor_user]]);})->get();
            foreach ($contacts as $c){
                array_push($result, [
                    "chat" => [
                        "chat_id" => $cu->chat->id,
                        "chat_start_datetime" => $cu->chat_start_datetime,
                        "end_session_datetime"=> $cu->end_session_datetime,
                        "contact"=> [
                            "id"=> $c->id,
                            "email"=> $c->email,
                            "first_name"=> $c->first_name,
                            "last_name"=> $c->last_name,
                            "email_confirmed_at"=> $c->email_confirmed_at,
                            "phone_number"=> $c->phone_number,
                            "status"=> $c->status
                        ]
                    ]
                ]);
            }
        }

        return response()->json($result);
    }
}
