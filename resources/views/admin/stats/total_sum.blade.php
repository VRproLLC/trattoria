<ul class="list-group">
    <li class="list-group-item">
        Общая сумма заказов:
        <span class="badge">{{$sum}} грн</span>
    </li>
    <li class="list-group-item">
        Средний чек:
        <span class="badge">{{$agv}} грн</span>
    </li>
    <li class="list-group-item">
        Всего заказов:
        <span class="badge">{{count($orders_count)}}</span>
    </li>
    @if(isset($end_orders->created_at))
    <li class="list-group-item">
        Последний заказ:
        <span class="badge">{{ $end_orders->created_at }}</span>
    </li>
    @endif
</ul>
