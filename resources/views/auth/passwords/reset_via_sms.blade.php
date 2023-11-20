@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container">
            <div class="form_page">
                <p class="title_page">{{trans('main.password_remind')}}</p>
                <form action="{{route('password.update_via_sms')}}" method="post">
                    @csrf
                    <label>
                        <p>{{trans('main.number_tel')}}:</p>
                        <input type="tel" placeholder="+38 (xxx) xxx xx xx" value="{{old('phone', session('current_phone'))}}" name="phone">
                        @include('partials.errors.default', ['name' => 'phone'])
                    </label>
                    <label>
                        <p>{{trans('main.remind_sms')}}:</p>
                        <input type="text" name="code">
                        @include('partials.errors.default', ['name' => 'code'])
                    </label>
                    <label>
                        <p>{{trans('main.new_password')}}:</p>
                        <input type="password" name="password">
                        @include('partials.errors.default', ['name' => 'password'])
                    </label>
                    <input type="submit" value="{{ trans('main.button_reset_password') }}">
                </form>
                <a class="remind_pass" href="{{ route('login') }}">{{trans('main.cancel_link')}}</a>
            </div>
        </div>
    </div>
@endsection
