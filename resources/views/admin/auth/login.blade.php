@extends('layouts.admin')

@section('content')
    <header>
        <div class="line_header">
            <div class="logo">
                <img src="{{asset('image/logo.svg')}}" alt="">
            </div>
            <div class="text_admin_red">
                Администратор ресторана
            </div>
        </div>
    </header>
    <div class="content">
        <div class="container">
            <div class="form_page">
                <p class="title_page">Вход администратора</p>
                <form action="{{ admin_url('auth/login') }}" method="post">
                    @csrf
                    <label>
                        <p>Логин:</p>
                        <input type="text" name="username" value="{{ old('username') }}">
                        @if($errors->has('username'))
                            @foreach($errors->get('username') as $message)
                                <p class="error_text">{{$message}}</p>
                            @endforeach
                        @endif
                    </label>
                    <label>
                        <p>Пароль</p>
                        <input type="password" name="password">
                        @if($errors->has('password'))
                            @foreach($errors->get('password') as $message)
                                <p class="error_text">{{$message}}</p>
                            @endforeach
                        @endif
                    </label>
                    <input type="submit" value="Войти">
                </form>
            </div>
        </div>
    </div>
@endsection
