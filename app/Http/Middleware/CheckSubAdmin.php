<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckSubAdmin
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
        if (Auth::check() && Auth::user()->user_type == 1) {
            return $next($request);
        }
        $error = $translations['msg_access_prohibited'] ?? "Access Prohibited";
        return redirect()->back()->with('middleware_error',$error);
        die();
    }
}
