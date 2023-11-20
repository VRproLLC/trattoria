<div class="one_product_block {{$product->in_stop_list == 1? 'in_stop_list' : ''}}">
    <div class="image_product click_product_image_subtrue" data-product-id="{{route('menu.show', ['id' => $product->id])}}">
        <img src="{{$product->asset_image}}" alt="">
    </div>
    <div class="name_product_text">
        <p class="name_product click_product_image_subtrue" data-product-id="{{route('menu.show', ['id' => $product->id])}}">{{$product->name }}</p>
        <p class="click_product_image_subtrue" data-product-id="{{route('menu.show', ['id' => $product->id])}}">{{ $product->description }}</p>
        <div class="line_link_bottom_product">
{{--            <a href="{{route('favorite.store')}}" class="link_like {{$product->is_favorite ? 'active' : ''}}" data-id-product="{{$product->id}}"></a>--}}
            <p class="weight_dish">{{$product->weight}}</p>
            <p class="price_dish">
                {{$product->price}} ₴
            </p>
            <div data-product-id="{{$product->id}}" data-link="/order/add_to_cart" class="button_pseudo_add_cart new_add_buttons_to_cart">
                <img src="{{asset('image/cart_white.svg')}}" alt="">
            </div>
        </div>
    </div>
</div>
