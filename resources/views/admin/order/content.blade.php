<div class="one_admin_block">
    <div class="top_admin_block">
        <p>Нові</p>
        @if($new_orders->count() > 0)
        <p class="new_green_count color" id="counter">{{$new_orders->count()}}</p>
        @else
        <p class="new_green_count" id="counter">{{$new_orders->count()}}</p>
        @endif
    </div>
    <div class="bottom_admin_block">
        @foreach($new_orders as $new_order)

        <a href="{{route('admin.dashboard.detail')}}" data-id="{{$new_order->id}}" class="border_order_wrap first_steps order_{{$new_order->id}}">
            <div class="line_number_order_price_date">
                <div class="brown_number_order">
                    @if(!empty($new_order->iiko_order_number))
                    №{{$new_order->iiko_order_number}}
                    @else
                    -
                    @endif
                </div>
                <div class="time_date_order">
                    17 Жовтня 2023
                    <dottag></dottag>
                    {{$new_order->time}}
                </div>
                <div class="green_price_new">
                    {{$new_order->full_price}} ₴
                </div>
            </div>
            @foreach($new_order->items as $item)
            <div class="each_product_order">
                <div class="count_product_order">
                    {{$item->amount ?? 0}}X
                </div>
                <div class="name_product_order">
                    @if(isset($item->product))
                    {{$item->product->name }}
                    @else
                    Имя
                    @endif
                </div>
            </div>
            @endforeach
            <div class="type_pay_order">
                <div>
                    з собою
                </div>
                <div>
                    ЗАРАЗ
                </div>
                <div>
                    {{$new_order->payment_type->name ?? 'Не выбран'}}
                </div>
            </div>
        </a>

        <!--
        <a href="{{route('admin.dashboard.detail')}}" data-id="{{$new_order->id}}" class="border_order_wrap first_steps order_{{$new_order->id}}">
            <div class="actual_data">
                <div class="line_about_dish">
                    <div>
                        <p>Номер заказа:</p>
                        <p class="number_black">
                            @if(!empty($new_order->iiko_order_number))
                            {{$new_order->iiko_order_number}}
                            @else
                            Не указан
                            @endif
                        </p>
                    </div>
                    <div>
                        <p>Время выдачи:</p>
                        <p class="number_black">{{$new_order->time}}</p>
                    </div>
                </div>
                @foreach($new_order->items as $item)
                <div class="one_product_block">
                    <div class="image_product">
                        @if(isset($item->product->asset_image))
                        <img src="{{$item->product->asset_image}}" alt="">
                        @endif
                    </div>
                    <div class="name_product_text">
                        <p class="name_product">
                            @if(isset($item->product))
                            {{$item->product->name }}
                            @else
                            Имя
                            @endif
                        </p>
                        <div class="line_link_bottom_product left_align_box">
                            <p class="price_dish">
                                {{$item->price_per_one}} ₴
                            </p>
                            <p class="weight_dish">{{$item->product->weight ?? 0}}</p>
                            <div class="count_product_text"> {{$item->amount ?? 0}} шт</div>
                        </div>
                    </div>
                </div>
                @endforeach
                <div class="line_about_dish">
                    <div>
                        <p>К оплате:</p>
                        <p class="number_green">{{$new_order->full_price}} грн</p>
                    </div>
                    <div>
                        <p>Способ оплаты:</p>
                        <p class="number_black">{{$new_order->payment_type->name ?? 'Не выбран'}}</p>
                    </div>
                </div>
            </div>
        </a>
-->

        @endforeach

    </div>
</div>
<div class="one_admin_block">
    <div class="top_admin_block">
        <p>Готується</p>
        @if($in_process_orders->count() > 0)
        <p class="new_green_count color" id="counter">{{$in_process_orders->count()}}</p>
        @else
        <p class="new_green_count" id="counter">{{$in_process_orders->count()}}</p>
        @endif
    </div>
    <div class="bottom_admin_block">
        @foreach($in_process_orders as $new_order)
        <a href="{{route('admin.dashboard.detail')}}" data-id="{{$new_order->id}}" class="border_order_wrap second_steps">
            <div class="line_number_order_price_date">
                <div class="brown_number_order">
                    @if(!empty($new_order->iiko_order_number))
                    №{{$new_order->iiko_order_number}}
                    @else
                    -
                    @endif
                </div>
                <div class="time_date_order">
                    17 Жовтня 2023
                    <dottag></dottag>
                    {{$new_order->time}}
                </div>
                <div class="green_price_new">
                    {{$new_order->full_price}} ₴
                </div>
            </div>
            @foreach($new_order->items as $item)
            <div class="each_product_order">
                <div class="count_product_order">
                    {{$item->amount ?? 0}}X
                </div>
                <div class="name_product_order">
                    @if(isset($item->product))
                    {{$item->product->name }}
                    @else
                    Имя
                    @endif
                </div>
            </div>
            @endforeach
            <div class="type_pay_order">
                <div>
                    з собою
                </div>
                <div>
                    ЗАРАЗ
                </div>
                <div>
                    {{$new_order->payment_type->name ?? 'Не выбран'}}
                </div>
            </div>
        </a>
        <!--
           <a href="{{route('admin.dashboard.detail')}}" data-id="{{$new_order->id}}" class="border_order_wrap second_steps">
            <div class="actual_data">
                <div class="line_about_dish">
                    <div>
                        <p>Номер заказа:</p>
                        <p class="number_black">
                            @if(!empty($new_order->iiko_order_number))
                            {{$new_order->iiko_order_number}}
                            @else
                            Не указан
                            @endif
                        </p>
                    </div>
                    <div>
                        <p>Время выдачи:</p>
                        <p class="number_black">{{$new_order->time}}</p>
                    </div>
                </div>
                @foreach($new_order->items as $item)
                @if(isset($item->product))
                <div class="one_product_block">
                    <div class="image_product">
                        <img src="{{$item->product->asset_image}}" alt="">
                    </div>
                    <div class="name_product_text">
                        <p class="name_product">{{$item->product->name}}</p>
                        <div class="line_link_bottom_product left_align_box">
                            <p class="price_dish">
                                {{$item->price_per_one}} ₴
                            </p>
                            <p class="weight_dish">{{$item->product->weight}}</p>
                            <div class="count_product_text"> {{$item->amount}} шт</div>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
                <div class="line_about_dish">
                    <div>
                        <p>К оплате:</p>
                        <p class="number_green">{{$new_order->full_price}} грн</p>
                    </div>
                    <div>
                        <p>Способ оплаты:</p>
                        <p class="number_black">{{$new_order->payment_type->name}}</p>
                    </div>
                </div>
            </div>
        </a>
-->
        @endforeach
    </div>
</div>
<div class="one_admin_block">
    <div class="top_admin_block">
        <p>Видача</p>
        @if($finished_orders->count() > 0)
        <p class="new_green_count color" id="counter">{{$finished_orders->count()}}</p>
        @else
        <p class="new_green_count" id="counter">{{$finished_orders->count()}}</p>
        @endif
    </div>
    <div class="bottom_admin_block">
        @foreach($finished_orders as $new_order)
        <a href="{{route('admin.dashboard.detail')}}" data-id="{{$new_order->id}}" class="border_order_wrap third_steps">
            <div class="line_number_order_price_date">
                <div class="brown_number_order">
                    @if(!empty($new_order->iiko_order_number))
                    №{{$new_order->iiko_order_number}}
                    @else
                    -
                    @endif
                </div>
                <div class="time_date_order">
                    17 Жовтня 2023
                    <dottag></dottag>
                    {{$new_order->time}}
                </div>
                <div class="green_price_new">
                    {{$new_order->full_price}} ₴
                </div>
            </div>
            @foreach($new_order->items as $item)
            <div class="each_product_order">
                <div class="count_product_order">
                    {{$item->amount ?? 0}}X
                </div>
                <div class="name_product_order">
                    @if(isset($item->product))
                    {{$item->product->name }}
                    @else
                    Имя
                    @endif
                </div>
            </div>
            @endforeach
            <div class="type_pay_order">
                <div>
                    з собою
                </div>
                <div>
                    ЗАРАЗ
                </div>
                <div>
                    {{$new_order->payment_type->name ?? 'Не выбран'}}
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>

<div class="one_admin_block">
    <div class="top_admin_block">
        <p>Готове</p>
        @if($delivery_orders->count() != '0')
        <p class="new_green_count color" id="counter">{{$delivery_orders->count()}}</p>
        @else
        <p class="new_green_count" id="counter">{{$delivery_orders->count()}}</p>
        @endif
    </div>
    <div class="bottom_admin_block">
        @foreach($delivery_orders as $new_order)
        <a href="{{route('admin.dashboard.detail')}}" data-id="{{$new_order->id}}" class="border_order_wrap third_steps">
            <div class="line_number_order_price_date">
                <div class="brown_number_order">
                    @if(!empty($new_order->iiko_order_number))
                    №{{$new_order->iiko_order_number}}
                    @else
                    -
                    @endif
                </div>
                <div class="time_date_order">
                    17 Жовтня 2023
                    <dottag></dottag>
                    {{$new_order->time}}
                </div>
                <div class="green_price_new">
                    {{$new_order->full_price}} ₴
                </div>
            </div>
            @foreach($new_order->items as $item)
            <div class="each_product_order">
                <div class="count_product_order">
                    {{$item->amount ?? 0}}X
                </div>
                <div class="name_product_order">
                    @if(isset($item->product))
                    {{$item->product->name }}
                    @else
                    Имя
                    @endif
                </div>
            </div>
            @endforeach
            <div class="type_pay_order">
                <div>
                    з собою
                </div>
                <div>
                    ЗАРАЗ
                </div>
                <div>
                    {{$new_order->payment_type->name ?? 'Не выбран'}}
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>
