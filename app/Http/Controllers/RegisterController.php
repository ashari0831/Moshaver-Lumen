<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\AuthController;

class RegisterController extends Controller
{
    //
    public function register(Request $req){
        $email = $req->email;
        $password = $req->password;
        $first_name = $req->first_name;
        $last_name = $req->last_name;
        $gender = $req->gender;
        $phone_number = $req->phone_number;
        

        if(empty($email) or empty($password) or empty($first_name) or empty($last_name) or empty($gender) or empty($phone_number)){
            return response()->json(['status'=>'error', 'message'=>'fill all the fields.']);
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return response()->json(['status'=>'error', 'message'=>'enter a valid email.']);
        }

        // if(strlen($password) < 6){
        //     return response()->json(['status'=>'error', 'message'=>'min 6 char']);
        // }

        if (User::where('email', '=', $email)->exists()){
            return response()->json(['status'=>'error', 'message'=>'use another email.']);
        }
        if (User::where('phone_number', '=', $phone_number)->exists()){
            return response()->json(['status'=>'error', 'message'=>'use another phone_number.']);
        }

        try {
            $user = new User();
            $user->email = $req->email;
            $user->first_name = $req->first_name;
            $user->last_name = $req->last_name;
            $user->gender = $req->gender;
            $user->phone_number = $req->phone_number;
            $user->password = app('hash')->make($req->password);

            if($user->save()) {
                $authcont = new AuthController();
                return $authcont->login($req);
            }

        } catch (\Exception $e) {
            return response()->json(['status'=>'error', 'message'=> $e->getMessage()]);
        }
    }
}
