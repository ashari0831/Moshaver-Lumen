<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Chat, Chat_user, Reservation, User};
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

    public function index(){
        $reservations = auth()->user()->reservations;
        $res_user = [];
        foreach ( $reservations as $res ) {
            array_push($res_user, [$res->user, $res]);
        }
        return response()->json($res_user);
    }

    public function show($advisor_user_id){
        $reservations = User::find($advisor_user_id)->reservations;
        $res_user = [];
        foreach ( $reservations as $res ) {
            array_push($res_user, [$res->user, $res]);
        }
        return response()->json($res_user);
    }

    public function destroy($reservation){
        try{
            $res = Reservation::find($reservation);
            Chat_user::where('chat_id', $res->chat_id)->delete();
            Chat::where('chat_id', $res->chat_id)->delete();
            $res->delete();
        }catch(\Exception $e){
            return response()->json(['چنین رزروی وجود ندارد']);
        }
    }

    public function admin_reseve_details(){
        $reservations = Reservation::all();

        $result = [];
        foreach ($reservations as $res){
            // $advisor =  User::where('users.id', $res->advisor_user_id)->join('advisors', 'advisors.user_id', '=', 'users.id')->get();
            $advisor =  $res->advisor;
            $user = $res->user;
            $now = Carbon::now();
            if ($now >= $res->reservation_datetime && $now <= $res->end_session_datetime){
                $session_stat = "در حال انجام";
            } elseif($now < $res->reservation_datetime) {
                $session_stat = "رزرو شده";
            } elseif($now > $res->end_session_datetime) {
                $session_stat = "به اتمام رسیده";
            }

            array_push($result, [
                "advisor_id"=> $advisor->id,
                "advisor_image"=> $advisor->image,
                "advisor_first_name"=> $advisor->first_name,
                "advisor_last_name"=> $advisor->last_name,
                "user_id"=> $user->id,
                "user_image"=> $user->image,
                "user_first_name"=> $user->first_name,
                "user_last_name"=> $user->last_name,
                "reserve_date"=> $res->created_at->toDateString(),
                "session_status"=> $session_stat
            ]);
        }
        return response()->json($result);
    }
}
