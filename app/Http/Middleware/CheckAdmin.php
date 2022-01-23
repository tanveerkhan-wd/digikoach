<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckAdmin
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

        if (Auth::check() && Auth::user()->user_type == 0) {
            return $next($request);
        }
        $error = "Access Prohibited";
        return redirect()->back()->with('middleware_error',$error);
        die();
    }
}
