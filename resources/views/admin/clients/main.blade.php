@include('admin.clients.filter_users')
<div class="box grid-box">
    <div class="box-body table-responsive">
        <table class="table table-hover grid-table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Дата регистрации</th>
                <th scope="col">Имя</th>
                <th scope="col">Телефон</th>
                <th scope="col">Последняя точка заказа</th>
                <th scope="col">Последний заказ</th>
                <th scope="col"></th>
            </thead>
            <tbody>

            @foreach($users as $item)
                <tr>
                    <th>{{$item->id}}</th>
                    <th>{{$item->created_at->format('d-m-Y')}}</th>
                    <th>{{$item->name}}</th>
                    <th>{{$item->phone}}</th>
                    @if($item->order !== null)
                        <th>{{ optional($item->order->organization()->first())->fullName }}</th>
                    @else
                        <th></th>
                    @endif
                    <th>{{$item->order->created_at?? null}}</th>
                    <th>
                        <a href="{{  route('admin.clients.show',['id' => $item->id]) }}"
                           class="btn btn-sm btn-default"
                           title="Добавить">
                            <span class="hidden-xs">Заказы</span>
                        </a></th>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="box-footer clearfix">
            {{ $users->links() }}
        </div>
    </div>
</div>
