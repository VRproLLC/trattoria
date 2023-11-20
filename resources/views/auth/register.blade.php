@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container">
            <div class="form_page">
                <p class="title_page">{{trans('main.register_title')}}</p>
                <form action="{{ route('register.send_sms') }}" method="post">
                    @csrf
                    <label>
                        <p>{{trans('main.number_tel')}}:</p>
                        <input type="tel" name="phone" placeholder="+38 (xxx) xxx xx xx" required autocomplete="phone"
                               value="{{old('phone')}}">
                        @include('partials.errors.default', ['name' => 'phone'])
                    </label>
                    <input type="submit" value="{{trans('main.register_link_start')}}">
                </form>
                <p class="reg_link">{{trans('main.yap_reg_text')}}. <a href="{{route('login')}}">{{trans('main.enter_button_texts')}}</a></p>
            </div>
        </div>
    </div>

@endsection
