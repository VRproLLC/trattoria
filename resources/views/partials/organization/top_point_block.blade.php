<div class="top_point_block">
    <p class="title_other_page">{{trans('main.point_issue')}}:</p>
    <div class="append_addres_block">
        <div>
            <img src="{{asset('image/01.svg')}}" alt="">
            <div>
                <p class="name_address">{{$organization->address}}</p>
                <a href="tel:{{$organization->phone}}">{{$organization->phone}}</a>
                <p>{{$organization->workTime}}</p>
            </div>
        </div>
    </div>
</div>
