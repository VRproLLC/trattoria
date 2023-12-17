<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\UserEvent;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class EventController extends Controller
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
        $events = UserEvent::query()
            ->where('user_id', auth()->id())
            ->latest()
            ->limit(20)
            ->get();

        return view('pages.event.index', compact('events'));
    }
}
