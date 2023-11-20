@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container">
            <div class="form_page">
                <p class="title_page">{{trans('main.enter_title')}}</p>
                <form action="{{ route('login') }}" method="post">
                    @csrf
                    <label>
                        <p>{{trans('main.number_tel')}}:</p>
                        <input type="tel" name="phone" placeholder="+38 (xxx) xxx xx xx" value="{{old('phone')}}">
                        @include('partials.errors.default', ['name' => 'phone'])
                    </label>
                    <label>
                        <p>{{trans('main.password_enter')}}</p>
                        <input type="password" name="password">
                        @include('partials.errors.default', ['name' => 'password'])
                    </label>
                    <input type="submit" value="{{trans('main.enter_button_texts')}}">
                </form>
                <a class="remind_pass" href="{{ route('password.request') }}">{{trans('main.password_remind')}}</a>
                <p class="reg_link">{{trans('main.not_reg_text')}}. <a href="{{ route('register') }}">{{trans('main.register_link')}}</a></p>
            </div>
        </div>
    </div>
@endsection
