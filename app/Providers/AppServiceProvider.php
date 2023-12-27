<?php

namespace App\Providers;

use App\Models\Language;
use App\Services\LanguageService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Models\Order\Order;
use Illuminate\Support\Facades\Cookie;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        LanguageService::setLang();

        Schema::defaultStringLength(191);

        View::composer(['partials.header'], function ($view){
            $view->with('languages', Language::where('active', 1)->orderBy('sort')->get());
        });

        View::composer(['partials.footer'], function ($view){
            $amount = 0;
            if(auth()->check()){
                $order = Order::where('uuid', session('uuid'))
                    ->where('user_id', auth()->id())
                    ->where('organization_id', Cookie::get('organization_id'))->first();
                if($order){
                    $amount = $order->items->sum('amount');
                }
            }

            $view->with('cart_amount', $amount);
        });
    }
}
