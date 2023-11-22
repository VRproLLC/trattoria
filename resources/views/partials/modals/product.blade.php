<div class="gray_section_overlay">
    <div class="close_section_products"></div>
    <div class="icon_product_modal">
        <img src="{{$product->asset_image}}" alt="">
    </div>
    <div class="text_about_products">
        <p class="name_card_product">{{$product->name }}</p>
        <p>{{ $product->description }}</p>
        <div class="line_details_product">
            <div>
                <p class="price_dish">
                    {{$product->price}} ₴
                </p>
                <p class="weight_dish">{{$product->weight}}</p>
            </div>
{{--            <div class="wrap_l_dlike">--}}
{{--                <p>{{trans('main.in_favorites')}}</p>--}}
{{--                <a href="{{route('favorite.store')}}" class="link_like {{$product->is_favorite ? 'active' : ''}}" data-id-product="{{$product->id}}">--}}
{{--                </a>--}}
{{--            </div>--}}
        </div>
         <textarea name="" placeholder="{{trans('main.comment_dishes')}}..."></textarea>
        <div class="line_plus_minus_count_addcart">
            <div class="wrap_plus_minus">
                <div class="minus">
                    <img src="{{asset('image/minus.svg')}}" alt="">
                </div>
                <input type="text" readonly value="1">
                <div class="plus">
                    <img src="{{asset('image/plus.svg')}}" alt="">
                </div>
            </div>
            <a href="" data-id-product="{{$product->id}}" data-link="{{route('order.add_to_cart')}}" class="add_to_cart_button"><img src="{{asset('image/cart_white.svg')}}" alt="">{{trans('main.add_to_cart')}}</a>
        </div>
    </div>
</div>
