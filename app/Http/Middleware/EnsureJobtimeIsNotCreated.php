<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Advisor_daily_time;


class EnsureJobtimeIsNotCreated
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
        if(Advisor_daily_time::where('advisor_id', auth()->user()->advisor->id)->exists()){
            $response = "برای این مشاور رکورد تایم کاری موجود هست. از همین اندپوینت با متد پوت یا پچ استفاده شود";
        } else {
            return $next($request);
        }
        

        // Post-Middleware Action

        return response()->json([$response], 403);
    }
}
