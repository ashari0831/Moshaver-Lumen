<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    public function user_requests(){
        $user = auth()->user();
        return response()->json(DB::table('requests')
                                ->join('users', 'requests.sender_id', '=', 'users.id')
                                ->where('users.id', '=', $user->id)
                                ->get());
    }
    public function advisor_requests(){
        $user = auth()->user();
        return response()->json(DB::table('requests')
                                ->join('users', 'requests.sender_id', '=', 'users.id')
                                ->where('requests.receiver_id', '=', $user->advisor->id)
                                ->get());
    }
}
