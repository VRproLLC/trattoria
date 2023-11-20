<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Product\Category;
use App\Models\Product\Product;
use Illuminate\Support\Facades\Cookie;

class MenuController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
        $this->middleware('selected_organization');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $organization = Organization::where('id', Cookie::get('organization_id'))->firstOrFail();
        $categories = Category::where('organization_id', $organization->id)->where('isDeleted', 0)->where('isIncludedInMenu', 1)->orderBy('sort')->whereNull('parentGroup')->get();

        return view('pages.menu.index', compact('organization', 'categories'));
    }

    public function show($id)
    {
        $product = Product::where('id', $id)->firstOrFail();

        return view('partials.modals.product', compact('product'));
    }
}
