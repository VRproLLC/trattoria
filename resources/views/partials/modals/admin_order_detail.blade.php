<div class="close_modal_prop_orders"></div>
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
                {{$order->payment_type->name ?? 'Не указан'}}
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
            @foreach($order->items as $item)
            <div class="line_props_product_info">
                <div>
                    <p>{{$item->amount}} шт</p>
                    <div class="name_products">{{$item->product->name}}</div>
                </div>
                <p class="price_to_prop">{{$item->price_per_one}} ₴</p>
            </div>
            @endforeach
            <div class="bottom_prop_order">
                <p>Общая стоимость заказа:</p>
                <p class="price_to_prop">{{$order->full_price}} ₴ </p>
            </div>
        </div>
        <div class="right_order_props">
            @if($order->order_status == \App\Enums\OrderEnum::$NEW_ORDER)
            <div class="button_edit_order" data-action="{{route('admin.dashboard.edit')}}" data-id="{{$order->id}}">
                Редактировать
            </div>
            <div class="button_success_order button_success_first" data-action="{{route('admin.dashboard.submit_order')}}" data-id="{{$order->id}}">
                Принять
            </div>
            <div class="red_button_cancel" data-orderId="{{$order->id}}">
                Отменить заказ
            </div>
            @endif
            @if($order->order_status == \App\Enums\OrderEnum::$IN_PROCESS)
            <div class="button_success_order success_change_status" data-action="{{route('admin.dashboard.finish_order')}}" data-id="{{$order->id}}">
                Готово
            </div>
            @endif
            @if($order->order_status == \App\Enums\OrderEnum::$FINISHED)
            <div class="button_success_order button_complete_order" data-action="{{route('admin.dashboard.give_away_order')}}" data-id="{{$order->id}}">
                Завершить
            </div>
            @endif
            <!--
            <div class="cancel_prop_order">
                Отмена
            </div>
-->
        </div>
    </div>
</div>
