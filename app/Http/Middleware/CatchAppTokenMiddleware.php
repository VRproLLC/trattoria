<?php

namespace App\Http\Middleware;

use Closure;

class CatchAppTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->user() && request()->has('android_token') && !empty(request('android_token'))) {
            $user = auth()->user();
            $user->onsignal_token = request('android_token');
            $user->save();
        }
        //TODO make ios token

        return $next($request);
    }
}
