@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container">
            <div class="other_page">
                <p class="title_other_page">{{trans('main.point_section')}}:</p>
                <div class="wrap_tab_map">
                    <div class="tab_click">
                        <div class="one_click maps_point active">{{trans('main.to_address')}}</div>
                        <div class="one_click maps_point">{{trans('main.to_map')}}</div>
                    </div>
                    <div class="show_tab_click ">
                        <div class="one_tab_show active this_not_border">
                            <div class="append_addres_block">
                                @foreach($organizations as $organization)
                                    @if($organization->isActive == 1)
                                        <a class="search_parent_block new_parent_search"
                                           href="{{route('main.set_pickup_place', ['id' => $organization->id])}}"
                                           data-current_organization="{{\Illuminate\Support\Facades\Cookie::get('organization_id')}}"
                                           data-organization="{{$organization->id}}"
                                           data-latitude="{{$organization->latitude}}"
                                           data-longitude="{{$organization->longitude}}"
                                           data-address="{{$organization->address}}"
                                           data-worktime="{{$organization->workTime}}">
                                            <div class="image_zvd">
                                                @if($organization->images)
                                                    <img src="{{asset($organization->images)}}" alt="">
                                                @else
                                                    <img src="{{ asset('image/image_bufet.jpg') }}" alt="">
                                                @endif
                                            </div>
                                            <p class="new_name_address"><img src="{{asset('image/localization.svg')}}"
                                                                             alt="">{{$organization->address}}</p>
                                            <p class="new_time_work"><img src="{{asset('image/time_work.svg')}}"
                                                                          alt="">{{$organization->workTime}}</p>
                                            <p class="new_info_point"><img src="{{asset('image/info_icon.svg')}}"
                                                                           alt="">{{ $organization->description }}</p>
                                        </a>
                                    @else
                                        <div class="search_parent_block new_parent_search">
                                            <div class="image_zvd">
                                                @if($organization->images)
                                                    <img src="{{asset($organization->images)}}" alt="">
                                                @else
                                                    <img src="{{ asset('image/image_bufet.jpg') }}" alt="">
                                                @endif
                                            </div>
                                            <p class="new_name_address"><img
                                                    src="{{asset('image/localization_gray.svg')}}"
                                                    alt="">{{$organization->address}}</p>
                                            <p class="new_time_work"><img src="{{asset('image/error_I.svg')}}"
                                                                          alt="">{{trans('main.order_not')}}</p>
                                            <p class="new_info_point"><img src="{{asset('image/info_icon.svg')}}"
                                                                           alt="">{{ $organization->description }}</p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="one_tab_show this_not_border map_tab_show">
                            <div id="map"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var map, markers;

        function initMap() {

            var center = {
                lat: 49.98159,
                lng: 36.234158
            }

            var mapOptions = {
                center: center,
                scrollwheel: true,
                zoom: 14
            }

            map = new google.maps.Map(document.getElementById('map'), mapOptions);

            markers = [];
            var marker = [];
            var contentString = [];
            var infowindow = [];
            $('.append_addres_block a').each(function (index) {
                if ($(this).attr('data-latitude').length > 0) {
                    marker[index] = new google.maps.Marker({
                        title: $(this).attr('data-address'),
                        position: {
                            lat: +$(this).attr('data-latitude'),
                            lng: +$(this).attr('data-longitude'),
                        },
                        icon: {
                            url: 'image/marker.svg'
                        },
                        map: map
                    });
                    contentString[index] =
                        '<div class="infowindow_link">' +
                        '<a href="' + $(this).attr('href') + '">' +
                        '<div class="left_icon_infowindow">' +
                        '<img src="image/bufet_logo.png" alt="">' +
                        '</div>' +
                        '<div class="right_text_infowindow">' +
                        '<p class="name_zvd_info">' + $(this).attr('data-address') + '</p>' +
                        '<p>' + $(this).attr('data-worktime') + '</p>' +
                        '</div>' +
                        '</a>' +
                        '</div>' +
                        '';

                    infowindow[index] = new google.maps.InfoWindow({
                        content: contentString[index],
                    });
                    marker[index].addListener("click", () => {
                        infowindow[index].open({
                            anchor: marker[index],
                            map,
                            shouldFocus: false,
                        });
                    });

                }
            });

        }
    </script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQwPhADcjl2Z0SUT7D-vRfd-0xTB-d76w&libraries=visualization,geometry,drawing,places&callback=initMap"></script>
@endsection
