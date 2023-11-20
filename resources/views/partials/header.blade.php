<header>
    <div class="line_header">
        @if(request()->route()->getName() != 'login')
        <div class="return"><a href=""><img src="{{asset('image/back.svg')}}" alt=""></a></div>
        @endif
        <div class="logo">
            <img src="{{asset('image/logo.svg')}}" alt="">
        </div>
        <div class="toggle_lang_wrap">
            <div class="current_lang">{{app()->getLocale() == 'uk' ? 'Ua' : ucfirst(app()->getLocale())}}</div>
            <div class="toggle_lang">
                @foreach($languages as $language)
                    @if($language->value != app()->getLocale())
                        <a href="{{route('language.set', ['language' => $language->value])}}">{{$language->name}}</a>
                    @endif
                @endforeach
            </div>
        </div>
    </div> 
</header>
