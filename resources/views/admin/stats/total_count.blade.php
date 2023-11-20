<ul class="list-group">
    @foreach($result as $product_id => $count)
        <li class="list-group-item">
            {{$products->where('id', $product_id)->first() ? $products->where('id', $product_id)->first()->name : 'Удален'}}
            <span class="badge">{{$count}} продаж</span>
        </li>
    @endforeach
</ul>
