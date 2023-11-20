@if($orders->count() > 0)
<div class="close_modal_prop_orders"></div>
<div class="prop_order_section">
    <p class="top_title_line_bottom">Архів замовлень</p>
    <div class="line_archive_links">
        @foreach($orders as $order)
            <div class="one_click_section_archive" data-id="{{ $order->id }}">
                <div class="line_tps">
                    <p class="big_name">
                        @if(!empty($order->iiko_order_number))
                            № {{$order->iiko_order_number}}
                        @else
                            Номер не указан
                        @endif</p>
                    <p class="green_price">₴ {{ $order->full_price }}</p>
                </div>
                <div class="line_tps">
                    <p class="mini_black">{{$order->created_at->isoFormat('D MMMM YYYY, h:mm ')}}</p>
                    <p class="orange_count">x{{$order->items->count()}}</p>
                </div>
                <div class="orange_text">
                    @foreach($order->items as $key => $item)
                        @if($item->product !== null)

                            {{ !empty($item->product ) ? $item->product->name:'' }}

                            @if($key+1 < $order->items->count()),@endif
                            @if($key == 2)...
                                @break
                            @endif
                        @endif
                    @endforeach
                </div>
                <div class="bottom_line_infos_link">
                    @if($order->is_time == 1)
                        <p class="lite_green orange">На {{\Carbon\Carbon::parse($order->time)->format('H:i')}}</p>
                    @else
                        <p class="lite_green">На  {{\Carbon\Carbon::parse($order->time)->format('H:i')}}</p>
                    @endif
                    <p class="arrow_wrap archive_info">
                        <img src="{{asset('image/arrow_right_green.svg')}}" alt="">
                    </p>
                </div>
            </div>
        @endforeach
    </div>
    <div class="pagination_wrap">
        {{ $orders->links('vendor.pagination.admin') }}
        <div class="wrap_number_page_form">
            <form class="next_page_form">
                <p>Номер сторінки:</p>
                <input type="text" id="page" name="page">
                <button type="submit" style="cursor: pointer">Перейти</button>
            </form>
        </div>
    </div>
</div>
@else
    <div class="close_modal_prop_orders"></div>
    <div class="prop_order_section">
        Архив пока пуст.
    </div>
@endif
