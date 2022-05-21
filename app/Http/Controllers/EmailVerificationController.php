<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class EmailVerificationController extends Controller
{
    /**
  * Request an email verification email to be sent.
  *
  * @param  Request  $request
  * @return Response
  */
  public function emailRequestVerification(Request $request)
  {
    if ( $request->user()->hasVerifiedEmail() ) {
        return response()->json('Email address is already verified.');
    }
    
    $request->user()->sendEmailVerificationNotification();
    
    return response()->json('Email request verification sent to '. auth()->user()->email);
  }
  /**
  * Verify an email using email and token from email.
  *
  * @param  Request  $request
  * @return Response
  */
  public function emailVerify(Request $request)
  {
    $this->validate($request, [
      'token' => 'required|string',
    ]);
    JWTAuth::getToken();
    JWTAuth::parseToken()->authenticate();
    if ( ! $request->user() ) {
        return response()->json('Invalid token', 401);
    }
    
    if ( $request->user()->hasVerifiedEmail() ) {
        return response()->json('Email address '.$request->user()->getEmailForVerification().' is already verified.');
    }
    $request->user()->markEmailAsVerified();
    return response()->json('Email address '. $request->user()->email.' successfully verified.');
  }
}
