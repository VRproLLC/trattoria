<footer>
    <div class="container">
        <div class="line_footer_link">
            <a href="{{route('main')}}">
                <img src="{{request()->route()->getName() == 'main' ? asset('image/home_active.svg') : asset('image/home.svg')}}" alt="">
            </a>
<!--
            <a href="{{route('events')}}">
                <img src="{{request()->route()->getName() == 'events' ? asset('image/bell_active.svg') : asset('image/bell.svg')}}" alt="">
            </a>
-->
            <a href="{{route('favorite.index')}}">
                <img src="{{request()->route()->getName() == 'favorite.index' ? asset('image/icon_order_active.svg') : asset('image/icon_order.svg')}}" alt="">
            </a>
            <a href="{{route('order.index')}}">
                <img src="{{request()->route()->getName() == 'order.index' ? asset('image/credit-card_active.svg') : asset('image/credit-card.svg')}}" alt="">
                <span class="red_count_product">{{$cart_amount}}</span>
            </a>
            <a href="{{route('dashboard.index')}}">
                <img src="{{request()->route()->getName() == 'dashboard.index' ? asset('image/user_active.svg') : asset('image/user.svg')}}" alt="">
            </a>
        </div>
    </div>
</footer>
