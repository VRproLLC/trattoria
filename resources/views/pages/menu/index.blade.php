@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container">
            <div class="other_page">
                @include('partials.organization.top_point_block', ['organization' => $organization])
                <div class="bottom_point_block">
                    <p class="title_other_page">{{trans('main.our_menu')}}:</p>
                    {{--<form class="search_form form_dish" action="" method="get">--}}
                        {{--<input type="text" placeholder="{{trans('main.search_bl')}} ...">--}}
                        {{--<button type="submit">--}}
                            {{--<img src="{{asset('image/search.svg')}}" alt="">--}}
                        {{--</button>--}}
                    {{--</form>--}}
                    <div class="block_line_dish">
                        <div class="tab_click">
                            @foreach($categories as $category)
                                <div class="one_click {{$loop->first ? 'active' : ''}}">{{$category->name}}</div>
                            @endforeach
                        </div>
                        <div class="show_tab_click this_products_list">
                            @foreach($categories as $category)
                                <div class="one_tab_show active">
                                <p class="title_other_page">{{$category->name}}:</p>
                                    @foreach($category->products as $product)
                                        @include('partials.product.item', ['product' => $product])
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="append_product_section">
    </div>

@endsection
