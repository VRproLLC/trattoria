<!--    Модальник редактирования заказа-->
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
                {{$order->full_price}} грн
            </p>
        </div>
<!--
        <div>
            <p>Способ оплаты:</p>
            <p class="black_text_orders">
                {{$order->payment_type->name ?? 'Не выбран'}}
            </p>
        </div>
-->
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
            <form class="serilize_first_form" action="">
                <label class="admin_custom_label_in_form">
                    <p class="admin_custom_p_in_form">Номер заказа:</p>
                    <input type="text" class="admin_custom_input_in_form" name="iiko_order_number" value="{{$order->iiko_order_number}}">
                </label>
                <hr>
                @foreach($order->items as $item)
                <div class="line_props_product_info border_to_both">
                    <div>
                        <input type="hidden" name="order_id" value="{{$order->id}}">
                        <p>{{$item->amount}} шт</p>
                        <div class="name_products">{{$item->product->name}}
                            <div class="wrap_plus_minus">
                                <div class="minus">
                                    <img src="{{asset('image/minus.svg')}}" alt="">
                                </div>
                                <input type="text" readonly value="{{$item->amount}}" name="order_item[{{$item->id}}]">
                                <div class="plus">
                                    <img src="{{asset('image/plus.svg')}}" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="price_to_prop">{{$item->price_per_one}} ₴</p>
                </div>
                @endforeach
            </form>
            <div class="bottom_prop_order">
                <p>Общая стоимость заказа:</p>
                <p class="price_to_prop">{{$order->full_price}} ₴ </p>
            </div>
        </div>
        <div class="right_order_props">
            <div class="button_add_modal_product button_add_second" data-action="{{route('admin.dashboard.add_product')}}" data-id="{{$order->id}}">
                Добавить блюдо
            </div>
            <div class="button_success_order button_success_second" data-action="{{route('admin.dashboard.update')}}" data-id="{{$order->id}}">
                Сохранить
            </div>
            <div class="cancel_prop_order">
                Отмена
            </div>
        </div>
    </div>
</div>
