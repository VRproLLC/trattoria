@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container">
            <div class="other_page">
                <div class="top_point_block user_account_section">
                    <p class="title_other_page">{{trans('main.cabinet')}}:</p>
                    <div class="wrap_tab_map">
                        <div class="show_tab_click">
                            <div class="one_tab_show this_not_border active">
                                <div class="line_account_data">
                                    <img src="{{asset('image/user_account.svg')}}" alt="">
                                    <div>
                                        <p>{{trans('main.name')}}:</p>
                                        <form action="" class="mini_submit_form">
                                            <input type="text" value="{{auth()->user()->name}}" id="name" name="name">
                                        </form>
                                    </div>
                                </div>
                                <div class="line_account_data">
                                    <img src="{{asset('image/phone_account.svg')}}" alt="">
                                    <div>
                                        <p>{{trans('main.telephone')}}:</p>
                                        <p class="black_name_user">{{auth()->user()->phone}}</p>
                                    </div>
                                </div>
                                <div class="tab_click">
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                        <input type="submit" value="{{trans('main.exit')}}">
                                    </form>
                                    <div
                                        class="one_click_remove_account maps_point remove_account"
                                        data-title="{{ trans('main.title_delete') }}"
                                        data-text="{{ trans('main.text_delete') }}"
                                        data-confirm="{{ trans('main.confirmButtonText_delete') }}"
                                        data-cancel="{{ trans('main.cancelButtonText_delete') }}"
                                    >{{trans('main.delete')}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
