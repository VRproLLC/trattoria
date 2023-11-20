<!--    Модальник добавления товара к заказу-->
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
                {{$order->payment_type->name}}
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
    <div class="tab_click">
        @foreach($categories as $category)
            <div class="one_click {{$loop->first ? 'active' : ''}}">{{$category->name}}</div>
        @endforeach
    </div>
    <div class="center_order_prop">
        <div class="left_order_props">
           <form class="edit_to_form_admin" action="">
               <input type="hidden" name="order_id" value="{{$order->id}}">
            @foreach($categories as $category)
                <div class="one_tab_show {{$loop->first ? 'active' : ''}}">
                    @foreach($category->products as $product)
                        <div class="one_product_block">
                            <div class="image_product">
                                <img src="{{$product->asset_image}}" alt="">
                            </div>
                            <div class="name_product_text">
                                <p class="name_product">{{$product->name}}</p>
                                <div class="line_link_bottom_product">
                                    <p class="weight_dish">{{$product->weight}} г</p>
                                    <p class="price_dish">
                                        {{$product->price}} ₴
                                    </p>
                                </div>
                            </div>
                            <div class="checkbox_new_product">
                                <label>
                                    <input type="checkbox" name="product[{{$product->id}}]" value="1">
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
            </form>
        </div>
        <div class="right_order_props">
            {{--<div class="button_add_modal_product">--}}
                {{--Добавить блюдо--}}
            {{--</div>--}}
            <div class="button_success_order button_success_third" data-action="{{route('admin.dashboard.add_product_save')}}">
                Сохранить
            </div>
            <div class="cancel_prop_order">
                Отмена
            </div>
        </div>
    </div>
</div>

