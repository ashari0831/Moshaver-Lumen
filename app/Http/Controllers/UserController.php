<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function store(){
        $validated = $this->validate(request(), [
            'email' => 'required|email|unique:users',
            'phone_number' => 'required|string|unique:users',
            'gender' => 'required',
            'password' => 'required|string|min:8',
            'year_born' => 'required|string',
            'first_name' => 'required',
            'last_name' => 'required'
        ]);

        $user = User::create($validated);
        return response()->json([$user], 201);
    }

    public function destroy($user){
        User::find($user)->delete();
        return response()->json([], 204);

    }

    public function show($user){
        return response()->json(User::find($user));
    }

    public function index(){
        return response()->json([User::all('id', 'image', 'first_name', 'last_name', 'created_at')], 200);
    }

}
