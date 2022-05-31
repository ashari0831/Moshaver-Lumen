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
        $validated = $this->validate(request(), [
            'text' => 'string',
            'is_confirmed' => 'boolean'
        ]);
        if(Rate::find($comment)->update($validated)){
            return response()->json(Rate::find($comment), 200);
        } else {
            return response()->json("update failed", 400);
        }
        
    }

    public function destroy($comment){
        return response()->json(Rate::find($comment)->delete(), 204);
    }

    public function store(){
        $req_arr = request()->all();
        $req_arr['user_id'] = auth()->user()->id;
        return response()->json(Rate::create($req_arr));
    }

    public function paticular_advisor_rates($advisor){
        return response()->json(Rate::where('advisor_id', $advisor)->with('user')->get());
    }

    public function list_users_comments(){
        $rates = Rate::all();
        $result = [];
        foreach ($rates as $rate){
            $num_of_rates_each_advisor = Rate::where('advisor_id', $rate->advisor_id)->count();
            array_push($result, [
                'user_id' => $rate->advisor->user->id,
                'advisor_id' => $rate->advisor_id,
                'first_name' => $rate->advisor->user->first_name,
                'last_name' => $rate->advisor->user->last_name,
                'num_of_comments' => $num_of_rates_each_advisor
            ]);
        }
        return response()->json($result, 200);
    }
}
