<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsSoftDeleted
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

        if (Auth::user()->deleted != '1'){
            return $next($request);
        }else{
            if (!$request->expectsJson()) {
                /* Auth::logout();
                Session::flush();
                Session::regenerate();
                return redirect()->route('login')->withErrors(['suspended' => 'Your account is deactivated']); */
            }else{
                Auth::logout();
                $error_messages = trans('message.error.user_deleted');
                return response()->json(['message' => $error_messages], 403, [], JSON_UNESCAPED_UNICODE);
            }
        }
    }
}
