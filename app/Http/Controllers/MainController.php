<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Models\Order\Order;

class MainController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        $organizations = Organization::where('isActive', 1)
            ->with([
                'order',
                'products'
            ])
            ->get()
            ->sortBy('delta_distance');

        return view('pages.main.index', [
            'organizations' => $organizations->merge(
                Organization::where('isActive', 0)->get()->sortBy('delta_distance')
            )
        ]);
    }

    /**
     * @throws \Exception
     */
    public function set_pickup_place($id)
    {
        if(Cookie::get('organization_id', 0) != 0 && Cookie::get('organization_id') != $id){
            $order = Order::where('uuid', session('uuid'))
                ->where('user_id', auth()->id())
                ->where('organization_id', Cookie::get('organization_id'))
                ->first();

            if($order){
                $order->delete();
            }
        }

        Cookie::queue('organization_id', $id, 5000);

        return redirect()->route('menu.index');
    }

    public function privacypolicy()
    {
        return view('pages.privacypolicy.index');
    }

    public function set_geo(Request  $request){
        $latitude = $request->get('latitude');
        $longitude = $request->get('longitude');

        if($latitude !== null && $longitude  !== null) {
            Cookie::queue('geos', implode(',', [$latitude, $longitude]), 3);
        }
        return response()->json(['status' => 1]);
    }
}
