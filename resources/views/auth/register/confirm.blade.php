@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container">
            <div class="form_page">
                <p class="title_page">{{trans('main.register_title')}}</p>
                <form action="{{ route('register.confirmDone', ['id' => $register->id]) }}" method="post">
                    @csrf
                    <input type="hidden" name="phone" value="{{ $register->phone }}">
                    <label>
                        <p>{{trans('main.number_tel')}}:</p>
                        <input type="tel" name="phone" placeholder="+38 (xxx) xxx xx xx" required autocomplete="phone"
                               value="{{ $register->phone }}" disabled>
                        @include('partials.errors.default', ['name' => 'phone'])
                    </label>
                    <label>
                        <p>{{trans('main.your_name')}}:</p>
                        <input type="text" name="name" required autocomplete="name" value="{{Request::old('name')}}">
                        @include('partials.errors.default', ['name' => 'name'])
                    </label>
                    <label>
                        <p>{{trans('main.remind_sms')}}:</p>
                        <input type="text" name="code" required value="{{Request::old('code')}}">
                        @include('partials.errors.default', ['name' => 'code'])
                    </label>
                    <label>
                        <p>{{trans('main.password_enter')}}</p>
                        <input type="password" name="password" required autocomplete="new-password">
                        @include('partials.errors.default', ['name' => 'password'])
                    </label>
                    <label>
                        <p>{{trans('main.confirm_pass_password')}}:</p>
                        <input type="password" name="password_confirmation" required autocomplete="new-password">
                    </label>
                    <input type="submit" value="{{trans('main.register_link')}}">
                </form>
                <p class="reg_link">{{trans('main.yap_reg_text')}}. <a href="{{route('login')}}">{{trans('main.enter_button_texts')}}</a></p>
            </div>
        </div>
    </div>
@endsection
