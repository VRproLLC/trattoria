<div class="box grid-box">
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover grid-table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Имя</th>
                <th scope="col">Адрес</th>
                <th scope="col">Новые заказы</th>
                <th scope="col">Готовятся</th>
                <th scope="col">Готовы к выдаче</th>
                <th scope="col">Выполнены</th>
            </tr>
            </thead>
            <tbody>
            @foreach($organization as $item)
{{--                @if($item->account->is_iiko == 1) table-primary  @else success @endif --}}
                <tr class=" ">
                    <th scope="row">{{$item->id}}</th>
                    <td>{{ $item->name }}</td>
                    <td><a class="btn btn-sm" href="{{  route('admin.dashboard.show',['id' => $item->id]) }}">{{ $item->address }}</a></td>
                    <td>{{ $item->order->where('order_status', App\Enums\OrderEnum::$NEW_ORDER)->where('created_at', '>', \Carbon\Carbon::today())->count() }}</td>
                    <td>{{ $item->order->where('order_status', App\Enums\OrderEnum::$IN_PROCESS)->where('created_at', '>',  \Carbon\Carbon::today())->count() }}</td>
                    <td>{{ $item->order->where('order_status', App\Enums\OrderEnum::$FINISHED)->where('created_at', '>',  \Carbon\Carbon::today())->count() }}</td>
                    <td>{{ $item->order->where('order_status', App\Enums\OrderEnum::$GIV_AWAY)->where('created_at', '>',  \Carbon\Carbon::today())->count() }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
