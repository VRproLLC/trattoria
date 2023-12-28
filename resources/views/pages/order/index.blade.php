@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container">
            <div class="other_page">
                <div class="top_point_block">
                    @if($order && $order->items->count() > 0)
                        <p class="title_other_page">{{trans('main.your_order')}}:</p>
                        <div class="wrap_checkout_section">
                            <form action="{{route('order.store')}}" method="post" class="read_orders_back"
                                  id="orderForm">
                                @csrf
                                <div class="product_checkout">
                                    @foreach($order->items as $item)
                                        <div class="one_product_block_cart">
                                            <div class="image_product_cart">
                                                <img src="{{$item->product->asset_image}}" alt="">
                                            </div>
                                            <div class="name_product_text_cart">
                                                <p class="name_product">{{$item->product->name}}</p>
                                                <div class="line_plus_minus_count_addcart_cart">
                                                    <div class="wrap_plus_minus"
                                                         data-id-product="{{$item->product->id}}"
                                                         data-link="{{route('order.add_to_cart')}}">
                                                        <div class="minus">
                                                            <img src="{{asset('image/minus.svg')}}" alt="">
                                                        </div>
                                                        <input type="text" readonly value="{{$item->amount}}">
                                                        <div class="plus">
                                                            <img src="{{asset('image/plus.svg')}}" alt="">
                                                        </div>
                                                    </div>
                                                    <p class="append_green_price">
                                                        {{$item->product->price}} ₴
                                                    </p>
                                                    <button type="button" class="delete_prod" style="cursor: pointer">
                                                        <svg id="Layer_1" data-name="Layer 1"
                                                             xmlns="http://www.w3.org/2000/svg"
                                                             viewBox="0 0 105.16 122.88">
                                                            <defs>
                                                                <style>
                                                                    .cls-1 {
                                                                        fill-rule: evenodd;
                                                                    }

                                                                </style>
                                                            </defs>
                                                            <title>delete</title>
                                                            <path class="cls-1"
                                                                  d="M11.17,37.16H94.65a8.4,8.4,0,0,1,2,.16,5.93,5.93,0,0,1,2.88,1.56,5.43,5.43,0,0,1,1.64,3.34,7.65,7.65,0,0,1-.06,1.44L94,117.31v0l0,.13,0,.28v0a7.06,7.06,0,0,1-.2.9v0l0,.06v0a5.89,5.89,0,0,1-5.47,4.07H17.32a6.17,6.17,0,0,1-1.25-.19,6.17,6.17,0,0,1-1.16-.48h0a6.18,6.18,0,0,1-3.08-4.88l-7-73.49a7.69,7.69,0,0,1-.06-1.66,5.37,5.37,0,0,1,1.63-3.29,6,6,0,0,1,3-1.58,8.94,8.94,0,0,1,1.79-.13ZM5.65,8.8H37.12V6h0a2.44,2.44,0,0,1,0-.27,6,6,0,0,1,1.76-4h0A6,6,0,0,1,43.09,0H62.46l.3,0a6,6,0,0,1,5.7,6V6h0V8.8h32l.39,0a4.7,4.7,0,0,1,4.31,4.43c0,.18,0,.32,0,.5v9.86a2.59,2.59,0,0,1-2.59,2.59H2.59A2.59,2.59,0,0,1,0,23.62V13.53H0a1.56,1.56,0,0,1,0-.31v0A4.72,4.72,0,0,1,3.88,8.88,10.4,10.4,0,0,1,5.65,8.8Zm42.1,52.7a4.77,4.77,0,0,1,9.49,0v37a4.77,4.77,0,0,1-9.49,0v-37Zm23.73-.2a4.58,4.58,0,0,1,5-4.06,4.47,4.47,0,0,1,4.51,4.46l-2,37a4.57,4.57,0,0,1-5,4.06,4.47,4.47,0,0,1-4.51-4.46l2-37ZM25,61.7a4.46,4.46,0,0,1,4.5-4.46,4.58,4.58,0,0,1,5,4.06l2,37a4.47,4.47,0,0,1-4.51,4.46,4.57,4.57,0,0,1-5-4.06l-2-37Z"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="comment_adds_product_to_cart @if($item->comment !== null) has_comment @endif">
                                                <textarea name="comment" data-id="{{ $item->id }}"
                                                          placeholder="Додати коментар до блюда ...">{{ $item->comment }}</textarea>
                                                <div class="right_add_del_edit_field">
                                                    <div class="add_comment_field"></div>
                                                    <div class="remove_comment_field"></div>
                                                    <div class="edit_comment_field"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="one_product_block_cart" style="display: none">
                                        <div class="image_product_cart">
                                            <img src="{{asset('image/pribori.png')}}" alt="">
                                        </div>
                                        <div class="name_product_text_cart">
                                            <p class="name_product">{{trans('main.count_devices')}}</p>
                                            <div class="line_plus_minus_count_addcart_cart">
                                                <div class="wrap_plus_minus">
                                                    <div class="minus devices_plus_minus">
                                                        <img src="{{asset('image/minus.svg')}}" alt="">
                                                    </div>
                                                    <input type="text" readonly value="1" name="number_of_devices">
                                                    <div class="plus devices_plus_minus">
                                                        <img src="{{asset('image/plus.svg')}}" alt="">
                                                    </div>
                                                </div>
                                                <p class="append_green_price">
                                                    0 ₴
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="wrap_total_price">
                                    <p>{{trans('main.total_price')}}:</p>
                                    <div class="append_total_price">
                                        {{$order->full_price}} ₴
                                    </div>
                                </div>
                                <div class="cart_point_block">
                                    <p class="title_other_page">{{trans('main.point_issue')}}:</p>
                                    <div class="append_addres_block">
                                        <div class="image_zvd">
                                            <img src="{{asset($organization->images)}}" alt="">
                                        </div>
                                        @if($organization->address !== null)
                                            <p class="new_name_address"><img src="{{asset('image/localization.svg')}}"
                                                                             alt="">{{$organization->address}}</p>
                                        @endif
                                        @if($organization->workTime !== null)
                                            <p class="new_time_work"><img src="{{asset('image/time_work.svg')}}"
                                                                          alt="">{{$organization->workTime}}</p>
                                        @endif
                                        <!--
                                <div>
                                    <img src="{{asset('image/01.svg')}}" alt="">
                                    <div>
                                        <p class="name_address">{{$organization->address}}</p>
                                        <p>{{$organization->workTime}}</p>
                                    </div>
                                </div>
-->
                            </div>
                            <div class="textarea_comment_wrap">
                                <p class="title_other_page">{{trans('main.comment_order')}}:</p>
                                <div class="wrap_textarea_comment">
                                    <textarea name="comment">{{$order->comment}}</textarea>
                                    @include('partials.errors.default', ['name' => 'comment'])
                                </div>
                            </div>
                            <div class="issue_delivery">
                                <p class="title_other_page">Доставка:</p>
                                <div class="line_wrap_checkbox time_check">
                                    <label class="line_one_checkbox">
                                        <input type="radio" name="is_delivery" value="1" {{old('is_delivery', 1) == 1 || $order->is_delivery == 1  ? 'checked' : '' }}>
                                        <span></span>
                                        <p>Доставка</p>
                                    </label>
                                    <label class="line_one_checkbox">
                                        <input type="radio" name="is_delivery" value="2" {{old('is_delivery') == 2 || $order->is_delivery == 2  ? 'checked' : '' }}>
                                        <span></span>
                                        <p>Самовывоз</p>
                                    </label>
                                </div>
                            </div>
                            <div class="show_hide_field_addr" @if( $order->is_delivery == 2) style="display: none" @endif">
                                <p class="title_other_page">Адрес доставки:</p>
                                <div class="wrap_input_delivery_enter">
                                    <input type="text" class="" name="address" value="{{ $order->address  }}" id="google_autocomplete_input">
                                    <div class="toggle_show_addr">
                                    </div>
                                </div>
                                <div class="total_sum_delivery">
                                    <p>Стоимость доставки:</p>
                                    <div class="apend_sum_del">
                                        <span class="sum_delivery">0</span> грн
                                    </div>
                                </div>
                            </div>
                            <div class="issue_time">
                                <p class="title_other_page">{{trans('main.time_issue')}}:</p>
                                <div class="line_wrap_checkbox time_check">
                                    <label class="line_one_checkbox">
                                        <input type="radio" name="time_issue" value="1" {{old('time_issue', 1) == 1 || $order->is_time == 1 ? 'checked' : '' }}>
                                        <span></span>
                                        <p>{{trans('main.time_now')}}</p>
                                    </label>
                                    <label class="line_one_checkbox">
                                        <input type="radio" name="time_issue" value="2" {{old('time_issue')==2  || $order->is_time == 2 ? 'checked' : '' }} class="time_step">
                                        <span></span>
                                        <p>{{trans('main.specify_time')}}</p>
                                    </label>
                                    @include('partials.errors.default', ['name' => 'time_issue'])
                                </div>
                                <div class="show_time">
                                    @if($order->time !== null && strtotime($order->time) > strtotime('00:00:00'))
                                        <input type="text" name="time" value="{{\Carbon\Carbon::parse($order->time)->format('H:i')}}" id="example" placeholder="{{ trans('main.select_time') }}">
                                        @include('partials.errors.default', ['name' => 'time'])
                                    @else
                                        <input type="text" name="time" value="{{ old('time') }}" id="example" placeholder="{{ trans('main.select_time') }}">
                                    @endif
                                    @include('partials.errors.default', ['name' => 'time'])
                                </div>
                            </div>
                            <div class="issue_time">
                                <p class="title_other_page">{{trans('main.method_pay')}}:</p>
                                <div class="line_wrap_checkbox">
                                    @if(empty($order->payment_type_id))
                                        @foreach($organization->payment_types->where('isDeleted', 0) as $payment_type)
                                            <label class="line_one_checkbox">
                                                <input class="pay_cash_card" type="radio" name="payment_type" {{$loop->first ? 'checked' : ''}} value="{{$payment_type->id}}" data-pay="{{ $payment_type->code }}">
                                                <span></span>
                                                <p>{{$payment_type->name}}</p>
                                            </label>
                                        @endforeach
                                    @else
                                        @foreach($organization->payment_types->where('isDeleted', 0) as $payment_type)
                                            <label class="line_one_checkbox">
                                                <input class="pay_cash_card" type="radio" name="payment_type" {{$payment_type->id == $order->payment_type_id ? 'checked' : ''}} value="{{$payment_type->id}}" data-pay="{{ $payment_type->code }}">
                                                <span></span>
                                                <p>{{$payment_type->name}}</p>
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                                @include('partials.errors.default', ['name' => 'payment_type'])
                            </div>
                            <button class="submit_checkout_button show_hide_field_send" type="submit">
                                <div class="lds-spinner">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>{{trans('main.subscribe')}}
                            </button>

                                <a class="submit_checkout_button show_hide_field_send_pay" style="display: none"
                                   href="{{ route('order.fondy', ['id' => $order->id]) }}">
                                    <div class="lds-spinner">
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                    </div>{{trans('main.pay_button')}}
                                </a>

                                <div class="red_remove_new_order_cart">
                                    {{trans('main.clear_cart_button')}}
                                </div>
                        </div>
                        </form>
                </div>
                @else
                    @if(!session()->has('prevent_back'))
                        <div class="cart_image_red_text">
                            <img src="{{asset('image/icon_empty_basket.svg')}}" alt="">
                            <p class="big_red_text">{{trans('main.cart_empty_red')}}</p>
                            <a href="{{route('main')}}" class="big_red_link">{{trans('main.main_step')}}</a>
                        </div>

                    @endif
                    @if(session()->has('prevent_back'))
                        <div class="cart_image_red_text">
                            <img src="{{asset('image/icon_success_basket.svg')}}" alt="">
                            <p class="big_red_text">{{trans('main.cart_success_text_empty')}}</p>
                            <a href="{{route('main')}}"
                               class="big_red_link">{{trans('main.next_after_complete_order')}}</a>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
    </div>
    <script
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQwPhADcjl2Z0SUT7D-vRfd-0xTB-d76w&libraries=visualization,geometry,drawing,places"></script>
    <script>
        var data = new Date();
        var hours = data.getHours();
        var minuts = data.getMinutes() + 25;
        var year = data.getFullYear();
        var month = data.getMonth() + 1;
        var date = data.getDate();
        var minuts2 = minuts;
        var checkHours = '';
        var checkMinuts = '';
        var selectHour = 0;
        var selectMinuts = 0;
        if (minuts < 10) {
            minuts2 = '0' + minuts;

        }
        new Rolldate({
            el: '#example',
            format: 'hh:mm',
            //        value: year+'-'+month+'-'+date+' '+hours+':'+minuts2+':00',
            lang: {
                title: '{{trans("main.now_text")}}, <span class="hours_append">' + hours + '</span>:<span class="minuts_append">' + minuts2 + '</span>',
                cancel: '',
                confirm: '{{trans("main.now_success")}}',
                year: '',
                month: '',
                day: '',
                hour: '',
                min: '',
                sec: ''
            },
            moveEnd: function (scroll) {
                if (scroll.wrapper.innerText.length > 71) {
                    checkMinuts = scroll.selectedIndex;
                } else {
                    checkHours = scroll.selectedIndex;
                }
                if (checkHours <= hours) {
                    if (checkMinuts <= minuts) {
                        //                    alert('{{trans("main.not_text_check_time")}}');
                        $('.modal_add_product_success .success_text').text('{{trans("main.not_text_check_time")}}');
                        $('.overlay.sure_overlay').fadeIn(100, function () {
                            $('.modal_add_product_success').fadeIn(100);
                        })
                    }
                }
                if (scroll.wrapper.innerText.length > 71) {
                    selectMinuts = scroll.selectedIndex;
                    if (scroll.selectedIndex < 10) {
                        selectMinuts = '0' + selectMinuts;
                    }
                    $('.minuts_append').text(selectMinuts);
                } else {
                    selectHour = scroll.selectedIndex;
                    if (scroll.selectedIndex < 10) {
                        selectHour = '0' + selectHour;
                    }
                    $('.hours_append').text(selectHour);
                }


            },
            confirm: function (data) {
                $('#example').val(data);
                formOrderUpdate();
            }
        });


        @if($organization)

        const latitude = '{{ $organization->latitude }}'
        const longitude = '{{ $organization->longitude }}'

        degreesToRadians = (degrees) => {
            return degrees * Math.PI / 180;
        }
        calculateDistance = (lat1, lon1, lat2, lon2) => {
            const earthRadiusKm = 6371;
            const dLat = degreesToRadians(lat2 - lat1);
            const dLon = degreesToRadians(lon2 - lon1);

            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(degreesToRadians(lat1)) * Math.cos(degreesToRadians(lat2)) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);

            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

            return earthRadiusKm * c;
        }

        const initGoogleMapAutocomplete = () => {
            const input = document.getElementById("google_autocomplete_input");
            const autocomplete = new google.maps.places.Autocomplete(input, {
                componentRestrictions: {country: ["ua"]},
                fields: ["address_components", "geometry"],
                strictBounds: false,
                types: ["address"],
            });
            autocomplete.addListener("place_changed", function () {
                const place = autocomplete.getPlace();

                if (place.geometry && place.geometry.location) {
                    const lat = place.geometry.location.lat();
                    const lng = place.geometry.location.lng();

                    const distance = calculateDistance(lat, lng, latitude, longitude);

                    if (distance > 0) {
                        document.querySelector('.sum_delivery').textContent = Math.round(distance * {{ $organization->delivery ?? 8 }});
                    }
                }
            });
        }
        initGoogleMapAutocomplete();
        @endif
    </script>
@endsection
