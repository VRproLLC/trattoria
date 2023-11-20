@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container">
            <div class="other_page">
                <div class="top_point_block">
                    <p class="title_other_page">{{trans('main.events')}}:</p>
                    @foreach($events as $event)
                        <div class="event_block">
                            <p class="name_events">{{$event->title}}</p>
                            <p>{{$event->text}}</p>
                        </div>
                    @endforeach
                    @if($events->count() == 0)
                        <div class="event_block">
                            <p>{{trans('main.no_events')}}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
