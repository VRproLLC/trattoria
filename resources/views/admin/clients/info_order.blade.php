<div class="col-md-4">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Исновная ифнормация</h3>
            <div class="box-tools pull-right">
            </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body" style="display: block;">
            <ul class="list-group">
                <li class="list-group-item">
                    Номер заказа
                    <span class="badge">{{ $order->iiko_order_number ?? 'Не указан' }}</span>
                </li>
                <li class="list-group-item">
                    Время создания
                    <span class="badge">{{ $order->created_at->format('d:m:Y H:i:s') ?? 'Не указан' }}</span>
                </li>

                <li class="list-group-item">
                    Цена
                    <span class="badge">{{ $order->full_price }} грн</span>
                </li>
                <li class="list-group-item">
                    Состояние
                    <span class="badge">{{\App\Enums\OrderEnum::$STATUSES[$order->order_status]}}</span>
                </li>
            </ul>
        </div>
        <div class="box-body" style="display: block;">
            <div class="box-header with-border">
                <h3 class="box-title">История</h3>
                <div class="box-tools pull-right">
                </div><!-- /.box-tools -->
            </div><!-- /.box-header -->
            <ul class="list-group">

                @if(collect($order->timestamp_at)->get('created_at'))
                    <li class="list-group-item">
                        Новый заказ
                        <span
                            class="badge">{{Carbon\Carbon::parse(collect($order->timestamp_at)->get('created_at'))->format('H:i m-d-Y') }} </span>
                    </li>
                @endif

                @if(collect($order->timestamp_at)->get('in_process'))
                    <li class="list-group-item">
                        Готовится
                        <span
                            class="badge">{{Carbon\Carbon::parse(collect($order->timestamp_at)->get('in_process'))->format('H:i m-d-Y') }} </span>
                    </li>
                @endif
                @if(collect($order->timestamp_at)->get('finished'))
                    <li class="list-group-item">
                        Готов
                        <span
                            class="badge">{{Carbon\Carbon::parse(collect($order->timestamp_at)->get('finished'))->format('H:i m-d-Y') }} </span>
                    </li>
                @endif
                @if(collect($order->timestamp_at)->get('completion'))
                    <li class="list-group-item">
                        Выдан
                        <span
                            class="badge">{{Carbon\Carbon::parse(collect($order->timestamp_at)->get('completion'))->format('H:i m-d-Y') }} </span>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Заказ</h3>
            <div class="box-tools pull-right">
            </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body" style="display: block;">
            <ul class="list-group">
                @foreach($result as $product_id => $count)
                    <li class="list-group-item">
                        {{$products->where('id', $product_id)->first() ? $products->where('id', $product_id)->first()->name : 'Удален'}}
                        <span class="badge">{{$count}} шт</span>
                    </li>
                @endforeach
            </ul>

        </div>
    </div>
</div>
