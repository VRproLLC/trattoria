<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddToFavoriteRequest;
use App\Models\Order\Order;
use App\Models\Organization;
use App\Models\Product\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Models\Product\Category;
use App\Models\UserEvent;
use Illuminate\Contracts\Support\Renderable;

class FavoriteController extends Controller
{

    public $organization_id;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())->where('order_status', '<>', 0)->limit(20)->latest()->get();

        $events = UserEvent::where('user_id', auth()->id())->latest()->limit(20)->get();

        return view('pages.favorite.index', compact('orders', 'events'));
    }

    public function store(AddToFavoriteRequest $request)
    {
        $organization = Organization::where('id', Cookie::get('organization_id'))->firstOrFail();
        $favorite = Favorite::where('product_id', $request->product_id)->where('organization_id', $organization->id)->where('user_id', auth()->id())->first();
        if($favorite){
            $favorite->delete();
            return response()->json(['success' => true]);
        }
        else{
            Favorite::create([
                'product_id' => $request->product_id,
                'organization_id' => $organization->id,
                'user_id' => auth()->id(),
            ]);
        }

        return response()->json(['success' => true]);
    }
}

