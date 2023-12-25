<!--<div class="close_modal_prop_orders"></div>-->
<div class="prop_order_section new_order_prop_section">
    <div class="new_modal_order">
        <div class="wrap_right_left_new_info_order">
            <div class="about_order_info_new_block">
                <div class="new_top_order_prop">
                    <div class="wrap_arr_inf_ordr">
                        <div class="arrow_back_new">
                            <svg width="92" height="92" viewBox="0 0 92 92" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="0.5" y="0.5" width="91" height="91" rx="5.5" stroke="#B8BCCA" />
                                <path d="M44.8167 36.4833L36.4833 44.8167C36.3316 44.9752 36.2126 45.1621 36.1333 45.3667C35.9666 45.7724 35.9666 46.2275 36.1333 46.6333C36.2126 46.8379 36.3316 47.0248 36.4833 47.1833L44.8167 55.5167C44.9721 55.672 45.1565 55.7953 45.3596 55.8794C45.5626 55.9635 45.7802 56.0068 46 56.0068C46.4438 56.0068 46.8695 55.8305 47.1833 55.5167C47.4972 55.2028 47.6735 54.7772 47.6735 54.3333C47.6735 53.8895 47.4972 53.4638 47.1833 53.15L41.6833 47.6667L54.3333 47.6667C54.7754 47.6667 55.1993 47.4911 55.5118 47.1785C55.8244 46.8659 56 46.442 56 46C56 45.558 55.8244 45.134 55.5118 44.8215C55.1993 44.5089 54.7754 44.3333 54.3333 44.3333L41.6833 44.3333L47.1833 38.85C47.3395 38.695 47.4635 38.5107 47.5481 38.3076C47.6328 38.1045 47.6763 37.8867 47.6763 37.6667C47.6763 37.4466 47.6328 37.2288 47.5481 37.0257C47.4635 36.8226 47.3395 36.6383 47.1833 36.4833C47.0284 36.3271 46.844 36.2031 46.641 36.1185C46.4379 36.0339 46.22 35.9903 46 35.9903C45.78 35.9903 45.5621 36.0339 45.359 36.1185C45.1559 36.2031 44.9716 36.3271 44.8167 36.4833Z" fill="#34394B" />
                            </svg>
                        </div>
                        <div class="about_order_line">
                            <div class="top_info_new_naqmber_dt">
                                <div class="brown_number_order">
                                    @if(!empty($order->iiko_order_number))
                                    №{{$order->iiko_order_number}}
                                    @else
                                    -
                                    @endif
                                </div>
                                <div class="time_date_order">
                                    17 Жовтня 2023
                                    <dottag></dottag>
                                    {{$order->time}}
                                </div>
                            </div>
                            <div class="lineinfo_status_box">
                                <div class="type_pay_order">
                                    <div>
                                        з собою
                                    </div>
                                    <div>
                                        ЗАРАЗ
                                    </div>
                                    <div>
                                        {{$order->payment_type->name ?? 'Не обрано'}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="link_buttonsremove_remove">
                        <div class="remove_button" data-orderId="{{$order->id}}">
                            <svg width="92" height="92" viewBox="0 0 92 92" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="92" height="92" rx="6" fill="#C4372B" />
                                <path d="M59.3333 32.6667L32.6666 59.3333M32.6666 32.6667L59.3333 59.3333" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="linkbutton_edit" data-action="{{route('admin.dashboard.edit')}}" data-id="{{$order->id}}">
                            <svg width="92" height="92" viewBox="0 0 92 92" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="92" height="92" rx="6" fill="#462E29" />
                                <path d="M34.3333 57.6667H36.7083L53 41.375L50.625 39L34.3333 55.2917V57.6667ZM31 61V53.9167L53 31.9583C53.3333 31.6528 53.7017 31.4167 54.105 31.25C54.5083 31.0833 54.9317 31 55.375 31C55.8194 31 56.25 31.0833 56.6667 31.25C57.0833 31.4167 57.4444 31.6667 57.75 32L60.0417 34.3333C60.375 34.6389 60.6183 35 60.7717 35.4167C60.925 35.8333 61.0011 36.25 61 36.6667C61 37.1111 60.9239 37.535 60.7717 37.9383C60.6194 38.3417 60.3761 38.7094 60.0417 39.0417L38.0833 61H31ZM51.7917 40.2083L50.625 39L53 41.375L51.7917 40.2083Z" fill="white" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="list_about_new_info_order_block">
                    @foreach($order->items as $item)
                    <div class="line_props_product_info">
                        <div>
                            <p>{{$item->amount}} x</p>
                            <div class="name_products">{{$item->product->name}}</div>
                        </div>
                        <p class="price_to_prop">{{$item->price_per_one}} ₴</p>
                    </div>
                    @endforeach
                </div>
                <div class="bottom_list_about_order_block">
                    <div class="wrap_total_new">
                        <p>Разом</p>
                        <p class="green_prices_new">{{$order->full_price}} грн</p>
                    </div>
                    <div class="count_prb">
                        <p>Прилади 2 шт</p>
                    </div>
                    <div class="about_user_info_new">
                        <div class="avatar_user_new">
                            <img src="{{asset('image/image_1.png')}}" alt="">
                        </div>
                        <div class="info_text_user_new">
                            <p>{{$order->user->name}}</p>
                            <p class="big_phone_blck">{{$order->user->phone}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="right_button_operation_order">
                <div class="button_success_new" data-action="{{route('admin.dashboard.finish_order')}}" data-id="{{$order->id}}">Готово</div>
                <div class="close_modal_button_new">Закрити</div>
            </div>
        </div>
    </div>

    <!--
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
        
        <div>
            <p>Способ оплаты:</p>
            <p class="black_text_orders">
                {{$order->payment_type->name ?? 'Не указан'}}
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
-->
    <!--
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
            
            <div class="cancel_prop_order">
                Отмена
            </div>

        </div>
    </div>
-->
</div>
