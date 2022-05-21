<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Auth\Passwords\PasswordBrokerManager;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
     /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth:api', ['except' => ['login']]);
    // }

    

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
        
        if (! $token = auth()->attempt($credentials)) {
           
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        
        return $this->respondWithToken($token);

    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }
    //how to send image file in request and render it in browser. try postman
    public function update(){
        $this->validate(request(), [
            'email' => 'email|unique:users',
            'password' => 'min:8',
            'phone_number' => 'unique:users',
            'image' => 'image'
        ]);
        // if (!(auth()->user()->update(request()->all()))){
        //     return response()->json("update failed!", 400);
        // }
        $user = auth()->user();
        if (!empty(request()->password)){
            $user->password = app('hash')->make(request()->password);
        }
        if (!empty(request()->first_name)){
            $user->first_name = request()->first_name;
        }
        if (!empty(request()->last_name)){
            $user->last_name = request()->last_name;
        }
        if (!empty(request()->year_born)){
            $user->year_born = request()->year_born;
        }
        if (!empty(request()->phone_number)){
            $user->phone_number = request()->phone_number;
        }
        if (!empty(request()->email)){
            $user->email = request()->email;
            $user->email_confirmed_at = null;
            $user->sendEmailVerificationNotification();
        }

        if (!empty(request()->file('image'))) {
            $picName = request()->file('image')->getClientOriginalName();
            $picName = uniqid() . '_' . $picName;
            $path =  public_path('uploads' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR);
            // echo $path;
             // upload path
            echo $path;
            File::makeDirectory($path, 0777, true, true);
            request()->file('image')->move($path, $picName);
            $user->image = 'images/' . $picName;
        }
        if(!($user->save())) {
            return response()->json("update failed!", 400);
        }
        
        return response()->json([auth()->user()], 200);
        
        
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function profile_image($user){
        // $disk = Storage::disk('local');
        // return $disk;

        // $type = 'image/png';
        // $headers = ['Content-Type' => $type];
        // // $path = 'uploads/profile-images/6281ee085bece_Screenshot_2022-05-12_02-42-44.png';
        
        // $response = new BinaryFileResponse($contents, 200 , $headers);

        // return $response;
        $relative_path = User::find($user)->image;
        // $exists = Storage::disk('local')->exists('uploads/profile-images/6281ee085bece_Screenshot_2022-05-12_02-42-44.png');
        // $contents = Storage::get('uploads/profile-images/6281ee085bece_Screenshot_2022-05-12_02-42-44.png');
        $path = public_path('uploads/' . $relative_path);
        return response()->file($path);
    }

    public function profile_image_me(){
        $relative_path = auth()->user()->image;
        $path = public_path('uploads/' . $relative_path);
        return response()->file($path);
    }

    // 1. Send reset password email
    public function generateResetToken(Request $request)
    {
        // Check email address is valid
        $this->validate($request, ['email' => 'required|email']);

        // Send password reset to the user with this email address
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        return $response == Password::RESET_LINK_SENT
            ? response()->json(true)
            : response()->json(false);
    }

    // 2. Reset Password
    public function resetPassword(Request $request)
    {
        // Check input is valid
        $rules = [
            'token'    => 'required',
            'email' => 'required|string|email',
            'password' => 'required|min:8',
        ];
        $this->validate($request, $rules);
        
        // Reset the password
        $response = $this->broker()->reset(
        $this->credentials($request),
            function ($user, $password) {
                $user->password = app('hash')->make($password);
                $user->save();
            }
        );
        
        return $response == Password::PASSWORD_RESET
            ? response()->json(true)
            : response()->json(false);
    }

    /**
     * Get the password reset credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only('email', 'password', 'token');
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        $passwordBrokerManager = new PasswordBrokerManager(app());
        return $passwordBrokerManager->broker();
    }
}
