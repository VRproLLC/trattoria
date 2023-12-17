@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container">
            <div class="other_page">
                <div class="top_point_block">
                    <div class="cart_image_red_text">
                        <img src="{{asset('image/icon_empty_basket.svg')}}" alt=""><p class="big_red_text">{{trans('main.order_step_pay')}}</p>
                        <a href="{{route('favorite.index')}}" class="big_red_link">{{trans('main.order_step')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

