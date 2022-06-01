<?php

namespace App\Http\Middleware;

use Closure;

class EnsureUserIsAdmin
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
        // Pre-Middleware Action
        if(auth()->user()->is_staff){
            $response = $next($request);
        } else {
            $response = "تنها ادمین به این اندبونت دسترسی دارد";
        }


        return response()->json([$response], 403);
    }
}
