<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Chat, Chat_user, Reservation};
use Illuminate\Support\Facades\Str;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function store(){
        $res_dt = new Carbon(request()->reservation_datetime);
        $dur_time = request()->duration_time;
        $user = auth()->user();
        $chat = Chat::create([
            'title' => uniqid() . Str($user->id) . Str(request()->advisor_user_id)
        ]);
        Chat_user::create([
            'chat_start_datetime' => $res_dt,
            'end_session_datetime' => $res_dt->addMinutes($dur_time),
            'user_id' => request()->advisor_user_id,
            'chat_id' => $chat->id
        ]);
        Chat_user::create([
            'chat_start_datetime' => $res_dt,
            'end_session_datetime' => $res_dt->addMinutes($dur_time),
            'user_id' => $user->id,
            'chat_id' => $chat->id

        ]);
        $res = Reservation::create([
            'chat_id' => $chat->id,
            'user_id' => $user->id,
            'advisor_user_id' => request()->advisor_user_id,
            'end_session_datetime' => $res_dt->addMinutes($dur_time),
            'reservation_datetime' => $res_dt
        ]);

        return response()->json([$res]);
    }
}
