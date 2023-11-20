<?php

namespace App\Http\Middleware;

use App\Models\Order\Order;
use Closure;
use Illuminate\Support\Str;

class UidMiddleware
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
        if(!session('uuid')){
            $uuid = Str::uuid();
            while(Order::where('uuid', $uuid)->first()){
                $uuid = Str::uuid();
            }
            session(['uuid' => Str::uuid()]);
        }
        else{
            $order = Order::where('uuid', session('uuid'))->first();
            if($order && $order->order_status != 0){
                session()->forget('uuid');
            }
        }

        return $next($request);
    }
}
