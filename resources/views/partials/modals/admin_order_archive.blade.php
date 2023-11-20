@if($order)
<div class="archive_modal_close"></div>
<div class="prop_order_section">
    <div class="top_order_prop">
        <div>
            <p>Номер заказа:</p>
            <p class="black_text_orders">
                @if(!empty($order->iiko_order_number))
                    {{$order->iiko_order_number}}
                @else
                    Не указан
                @endif
            </p>
        </div>
        <div>
            <p>Время оформления:</p>
            <p class="black_text_orders">
                {{$order->created_at->format('H:i d-m-Y')}}
            </p>
        </div>
        <div>
            <p>Время выдачи:</p>
            <p class="black_text_orders">
                {{$order->time}}
            </p>
        </div>
        <div>
            <p>К оплате:</p>
            <p class="green_text_orders">
                {{ $order->full_price }} грн
            </p>
        </div>
        <div>
            <p>Имя:</p>
            <p class="black_text_orders">
                {{$order->user->name}}
            </p>
        </div>
        <div>
            <p>Телефон:</p>
            <p class="black_text_orders">
                {{$order->user->phone}}
            </p>
        </div>
    </div>
    <div class="center_order_prop">
        <div class="left_order_props">
            @foreach($order->items as $item)
                <div class="line_props_product_info">
                    <div>
                        <p>{{$item->amount}} шт</p>
                        <div class="name_products">{{$item->product->name }}</div>
                    </div>
                    <p class="price_to_prop">{{$item->price_per_one}} ₴</p>
                </div>
            @endforeach
            <div class="bottom_prop_order">
                <p>Общая стоимость заказа:</p>
                <p class="price_to_prop">{{$order->full_price}}  ₴ </p>
            </div>
        </div>
        @if(collect($order->timestamp_at)->count() > 0)
        <div class="right_order_props new_order_props">
            <div class="container_line_order_status">
                @if(collect($order->timestamp_at)->get('created_at'))
                <div class="line_one_status_orders red_status">
                    <div>
                        <img src="{{ asset('/image/new_mini.svg') }}" alt="">
                        Новый заказ
                    </div>
                    <p>{{Carbon\Carbon::parse(collect($order->timestamp_at)->get('created_at'))->format('H:i d-m-Y') }}</p>
                </div>
                @endif
                @if(collect($order->timestamp_at)->get('in_process'))
                <div class="line_one_status_orders red_status">
                    <div>
                        <img src="{{ asset('image/pizza_mini.svg') }}" alt="">
                        Готовится
                    </div>
                    <p>{{Carbon\Carbon::parse(collect($order->timestamp_at)->get('in_process'))->format('H:i d-m-Y') }}</p>
                </div>
                @endif
                @if(collect($order->timestamp_at)->get('finished'))
                <div class="line_one_status_orders green_status">
                    <div>
                        <img src="{{ asset('image/ok_mini.svg') }}" alt="">
                        Готов
                    </div>
                    <p> {{Carbon\Carbon::parse(collect($order->timestamp_at)->get('finished'))->format('H:i d-m-Y') }}</p>
                </div>
                @endif
                @if(collect($order->timestamp_at)->get('completion'))
                <div class="line_one_status_orders green_status">
                    <div>
                        <img src="{{ asset('image/pizzabox_mini.svg') }}" alt="">
                        Выдан
                    </div>
                    <p>{{Carbon\Carbon::parse(collect($order->timestamp_at)->get('completion'))->format('H:i d-m-Y') }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@else
    <div class="archive_modal_close"></div>
    <div class="prop_order_section">
        Заказ не найден.
    </div>
@endif
