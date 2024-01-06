@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container">
            <div class="other_page">
                <div class="top_point_block user_account_section">
                    <p class="title_other_page">{{trans('main.actual')}}:</p>
                    <div class="wrap_tab_map">
                        <div class="tab_click">
                            <div class="one_click maps_point active">{{trans('main.orders_new')}}</div>
                            <div class="one_click maps_point">{{trans('main.seren_new')}}</div>
                        </div>
                        <div class="show_tab_click">
                            <div class="one_tab_show this_not_border active">
                                <div class="block_line_dish">
                                    @foreach($orders as $order)
                                        @if($order->organization)
                                            <div class="actual_data">
                                                <!--
<div class="line_about_dish">
                                            <div>
                                                <p>{{trans('main.point_of_issue')}}:</p>
                                                <p class="number_black">{{$order->organization->address}}</p>
                                            </div>
                                            <div>
                                                <p>{{trans('main.to_pay')}}:</p>
                                                <p class="number_green">{{$order->full_price}} грн</p>
                                            </div>
                                        </div>
-->
                                                <div class="line_about_dish">
                                                    <div>
                                                        <p>{{trans('main.number_order')}}:</p>
                                                        {{-- <p class="number_black">{{empty($order->iiko_order_number) ? $order->id : $order->iiko_order_number}}</p>--}}
                                                        <p class="number_black">{{empty($order->iiko_order_number) ? '-' : $order->iiko_order_number}}</p>
                                                    </div>
                                                    <div>
                                                        <p>{{trans('main.time_order')}}:</p>
                                                        <p class="number_black">{{$order->created_at->format('d-m-y')." ".$order->time}}</p>
                                                    </div>
                                                    <div>
                                                        <p>{{trans('main.to_pay')}}:</p>
                                                        <p class="number_green">{{$order->full_price}} грн</p>
                                                    </div>
                                                </div>
                                                @foreach($order->items as $item)
                                                    @if($item->product)
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
                                                                    @if($item->product->weight)
                                                                        <p class="weight_dish">{{$item->product->weight}}
                                                                            г</p>
                                                                    @endif
                                                                    <div class="count_product_text">{{$item->amount}}
                                                                        шт
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                                <div class="links_tel_map_icons">
                                                    <div class="addres_to_line">
                                                        <img src="{{asset('image/localization.svg')}}" alt="">
                                                        {{$order->organization->address}}
                                                    </div>
                                                    <div class="links_to_line">
                                                        @if($order->organization->address)
                                                            <a href='https://www.google.com/maps/place/{{urlencode($order->organization->address)}}'
                                                               target="_blank">
                                                                <img src="{{asset('image/PathToButton.svg')}}" alt="">
                                                            </a>
                                                        @endif
                                                        @if($order->organization->phone)
                                                            <a href="tel:{{$order->organization->phone}}">
                                                                <img src="{{asset('image/CallToButton.svg')}}" alt="">
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                                <!--
                                        <div class="line_about_dish">
                                            <div>
                                                <p>{{trans('main.point_of_issue')}}:</p>
                                                <p class="number_black">{{$order->organization->address}}</p>
                                            </div>
                                            <div>
                                                <p>{{trans('main.to_pay')}}:</p>
                                                <p class="number_green">{{$order->full_price}} грн</p>
                                            </div>
                                        </div>
-->
                                                @if($order->order_status == \App\Enums\OrderEnum::$NEW_ORDER)
                                                    <div class="line_status_orders">
                                                        <div class="line_icon_status"><img
                                                                src="{{asset('image/status1.svg')}}" alt=""> Статус
                                                            заказа:
                                                        </div>
                                                        <div class="red_status">{{$order->order_status_text}}</div>
                                                    </div>
                                                    <div class="red_remove_new_order" data-orderId="{{ $order->id }}">
                                                        {{trans('main.clear_new_order_button')}}
                                                    </div>
                                                @endif
                                                @if($order->order_status == \App\Enums\OrderEnum::$IN_PROCESS)
                                                    <div class="line_status_orders">
                                                        <div class="line_icon_status"><img
                                                                src="{{asset('image/status2.svg')}}" alt=""> Статус
                                                            заказа:
                                                        </div>
                                                        <div class="red_status">{{$order->order_status_text}}</div>
                                                    </div>
                                                @endif
                                                @if($order->order_status == \App\Enums\OrderEnum::$FINISHED)
                                                    <div class="line_status_orders">
                                                        <div class="line_icon_status"><img
                                                                src="{{asset('image/status3.svg')}}" alt=""> Статус
                                                            заказа:
                                                        </div>
                                                        <div class="green_status">{{$order->order_status_text}}</div>
                                                    </div>
                                                @endif
                                                @if($order->order_status == \App\Enums\OrderEnum::$GIV_AWAY)
                                                    <div class="line_status_orders">
                                                        <div class="line_icon_status"><img
                                                                src="{{asset('image/status4.svg')}}" alt=""> Статус
                                                            заказа:
                                                        </div>
                                                        <div class="green_status">{{$order->order_status_text}}</div>
                                                    </div>
                                                @endif
                                                @if($order->order_status == \App\Enums\OrderEnum::$CANCELED)
                                                    <div class="line_status_orders">
                                                        <div class="line_icon_status"><img
                                                                src="{{asset('image/status5.svg')}}" alt=""> Статус
                                                            заказа:
                                                        </div>
                                                        <div class="red_status">{{$order->order_status_text}}</div>
                                                    </div>
                                                @endif
                                                @if($order->order_status == \App\Enums\OrderEnum::$DELIVERED)
                                                    <div class="line_status_orders">
                                                        <div class="line_icon_status"><img
                                                                    src="{{asset('image/status4.svg')}}" alt=""> Статус
                                                            заказа:
                                                        </div>
                                                        <div class="red_status">{{$order->order_status_text}}</div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="one_tab_show this_not_border">
                                @foreach($events as $event)
                                    <div class="event_block">
                                        <p class="name_events">{{$event->title}}</p>
                                        <p> @if(collect($event->values)->get('type') == 'created')
                                                {{ trans('events.order_start', ['id' => collect($event->values)->get('orderId')]) }}
                                            @endif
                                            @if(collect($event->values)->get('type') == 'finish')
                                                {{ trans('events.order_dode', ['id' => collect($event->values)->get('orderId')]) }}
                                            @endif
                                            @if(collect($event->values)->get('type') == 'cancellation')
                                                {{ trans('events.cancellation') }}
                                            @endif
                                        </p>
                                    </div>
                                @endforeach
                                @if($events->count() == 0)
                                    <div class="event_block">
                                        <p>{{trans('main.no_events')}}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="append_product_section">
        </div>
@endsection
