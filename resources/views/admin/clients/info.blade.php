<div class="panel panel-default">
    <div class="row" style="padding: 10px 10px 10px 10px">
        <div class="col-xs-6 col-sm-4">
            <ul class="list-group">
                <li class="list-group-item">Имя: {{ $user->name }}</li>
                <li class="list-group-item">Телефон: {{ $user->phone }}</li>
                <li class="list-group-item">Дата регистрации: {{ $user->created_at }}</li>
            </ul>
        </div>
        <div class="col-xs-6 col-sm-4">
            <ul class="list-group">
                <li class="list-group-item">
                    Выполнено: {{$user->orders()->where('order_status', \App\Enums\OrderEnum::$GIV_AWAY)->count()}}</li>
                <li class="list-group-item">
                    Отменено: {{$user->orders()->where('order_status', \App\Enums\OrderEnum::$CANCELED)->count()}}</li>
                <li class="list-group-item">Последний
                    заказ: {{$user->orders()->where('order_status', \App\Enums\OrderEnum::$GIV_AWAY)->orderBy('created_at', 'desc')->first()->created_at ?? null}}</li>
            </ul>
        </div>
    </div>
</div>
@include('admin.clients.filter', [
    'organization' => $organization,
    'user' => $user
])
@if($orders->count() > 0)
    <div class="box grid-box">
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover grid-table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Создан</th>
                    <th scope="col">Точка</th>
                    <th scope="col">Адрес</th>
                    <th scope="col">Стоимость</th>
                    <th scope="col">Состояние</th>
                    <th scope="col">Комментарий</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                    <tr data-id="{{ $order->id }}">
                        <th>{{$order->iiko_order_number}}</th>
                        <th>{{$order->created_at->format('d-m-Y')}}</th>
                        <th>{{$order->organization->fullName ?? ''}}</th>
                        <th>{{$order->organization->address ?? ''}}</th>
                        <th>{{$order->full_price}} грн</th>
                        <th>{{\App\Enums\OrderEnum::$STATUSES[$order->order_status]}}</th>
                        <th>{{$order->comment}}</th>
                        <th>
                            <a href="{{  route('admin.clients.order',['id' => $user->id, 'orderId' => $order->id]) }}"
                               class="btn btn-sm btn-default"
                               title="Добавить">
                                <span class="hidden-xs">Заказ</span>
                            </a>
                        </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
