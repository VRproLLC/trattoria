<?php

namespace App\Http\Controllers;

use App\Enums\OrderEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\RenameRequest;
use App\Models\Order\Order;
use Auth;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AccountController extends Controller
{
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
     * @return Renderable
     */
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->where('order_status', '<>', OrderEnum::$FILLS_ORDER)
            ->limit(20)->latest()->get();

        $google = 'https://www.google.com/maps/@';

        return view('pages.account.index', compact('orders', 'google'));
    }


    /**
     * @param RenameRequest $request
     * @return JsonResponse
     */
    public function rename(RenameRequest $request){

        auth()->user()->update([
            'name' => $request->get('name')
        ]);

        return response()->json([
            'status' => 1
        ]);
    }

    public function remove(){
        auth()->user()->update([
            'phone' => auth()->user()->phone . '_delete_' . Str::random(3)
        ]);

        auth()->user()->delete();

        Auth::logout();

        return response()->json([
            'status' => 1
        ]);
    }
}
