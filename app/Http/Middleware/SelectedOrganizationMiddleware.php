<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cookie;

class SelectedOrganizationMiddleware
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
        if(empty(Cookie::get('organization_id'))){
            return redirect()->route('main')->with([
                'error' => 'Необходимо указать точку выдачи'
            ]);
        }
        return $next($request);
    }
}
