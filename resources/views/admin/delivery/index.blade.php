@extends('layouts.admin')

@section('content')
    <header>
        <div class="line_header">
            <div>
                <div class="logo">
                    <img src="{{asset('image/logo.svg')}}" alt="">
                </div>
            </div>
            <div>
                <div class="exit_admin_button">
                    <a href="{{ admin_url('/') }}">Назад</a>
                </div>
                <div class="exit_admin_button">
                    <a href="{{ admin_url('auth/logout') }}">Выход</a>
                </div>
            </div>
        </div>
    </header>
    <div class="content">
        <div class="container">
            <div class="wrap_block_bufet">
                @include('admin.delivery.content')
            </div>
        </div>
    </div>

    <div class="overlay_modal"></div>

    <div class="modal_order_admin append_about_order_block">
        {{--@include('partials.modals.admin_order_detail')--}}
    </div>

    <div class="modal_order_admin append_edit_order_block">
        {{-- @include('partials.modals.admin_order_edit')--}}
    </div>

    <div class="modal_order_admin append_adds_order_block">
        {{--@include('partials.modals.admin_order_add_product')--}}
    </div>
    <div class="modal_order_admin append_archive_result"></div>
    <div class="modal_order_admin archive_modal append_search_result"></div>
@endsection
