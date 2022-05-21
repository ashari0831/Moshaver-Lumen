<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\{Advisor};

class EnsureUserIdIsNotRepetitious
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $advisor = Advisor::where('user_id', auth()->user()->id)->exists();
        if(!($advisor)){
            $response = $next($request);
        } else {
            $response = "You are an advisor already! cannot make another one.";
        }

        

        // Post-Middleware Action

        return response()->json($response, 403);;
    }
}
