<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Advisor, Rate};
use Illuminate\Support\Facades\DB;

class RateController extends Controller
{
    public function users_comments(){
        $user = auth()->user();
        // return response()->json($user->advisor->rates);
        return response()->json(DB::table('rates')
                                ->join('users', 'rates.user_id', '=', 'users.id')
                                ->where('rates.advisor_id' ,'=', $user->advisor->id)
                                ->get());
    }

    public function users_comments_by_advisor_id($advisor_id){
        // $user = auth()->user();
        return response()->json(DB::table('rates')
                                ->join('users', 'rates.user_id', '=', 'users.id')
                                ->where('rates.advisor_id' ,'=', $advisor_id)
                                ->get());
    }

    // public function unconfirmed_comments_for_admin(){
    //     return response()->json(DB::table('rates')
    //                             ->join('users', 'rates.user_id', '=', 'users.id')
    //                             ->where('rates.is_confirmed' ,'=', false)
    //                             ->get());
    // }

    public function index(){
        return response()->json(DB::table('rates')
                                ->join('users', 'rates.user_id', '=', 'users.id')
                                ->select('rates.id as id', 'rates.text', 'rates.rate', 'rates.created_at', 'users.id as user_id', 'users.first_name', 'users.last_name', 'users.image')
                                ->where('rates.is_confirmed' ,'=', false)
                                ->get());
    }

    public function update($comment){
        // request()->input('name', 'SAlly'); //default val
       
        return response()->json(Rate::find($comment)->update(request()->all()));
    }

    public function destroy($comment){
        return response()->json(Rate::find($comment)->delete(), 204);
    }

    public function store(){
        $req_arr = request()->all();
        $req_arr['user_id'] = auth()->user()->id;
        return response()->json(Rate::create($req_arr));
    }
}
