@extends('admin.layout.base')

@section('title', 'Request details ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">@lang('admin.member.request_details')</h4>
            </div>
        </div>

        <div class="box box-block bg-white">
            <div class="row">
                <div class="col-md-12">
                    <dl class="row">
                        <dt class="col-sm-3">@lang('admin.member.booking_id') :</dt>
                        <dd class="col-sm-8">{{ $request->booking_id }}</dd>
                        <dt class="col-sm-3">@lang('admin.member.user_name') :</dt>
                        <dd class="col-sm-8">{{ $request->user_name }}</dd>

                        <dt class="col-sm-3">@lang('admin.member.driver_name') :</dt>
                        @if($request->provider)
                        <dd class="col-sm-8">{{ $request->provider->name }}</dd>
                        @else
                        <dd class="col-sm-8">@lang('admin.member.provider_not_yet_assigned')</dd>
                        @endif

                        <dt class="col-sm-3">@lang('admin.member.total_distance') :</dt>
                        <dd class="col-sm-8">{{ $request->distance ? $request->distance : '-' }} {{ $unit }}</dd>

                        @if($request->status == 'SCHEDULED')
                        <dt class="col-sm-3">@lang('admin.member.ride_scheduled_time') :</dt>
                        <dd class="col-sm-8">
                            @if($request->schedule_at != "0000-00-00 00:00:00")
                                {{ date('jS \of F Y h:i:s A', strtotime($request->schedule_at)) }} 
                            @else
                                - 
                            @endif
                        </dd>
                        @else
                        <dt class="col-sm-3">@lang('admin.member.ride_start_time') :</dt>
                        <dd class="col-sm-8">
                            @if($request->started_at != "0000-00-00 00:00:00")
                                {{ date('jS \of F Y h:i:s A', strtotime($request->started_at)) }} 
                            @else
                                - 
                            @endif
                         </dd>

                        <dt class="col-sm-3">@lang('admin.member.ride_end_time') :</dt>
                        <dd class="col-sm-8">
                            @if($request->finished_at != "0000-00-00 00:00:00") 
                                {{ date('jS \of F Y h:i:s A', strtotime($request->finished_at)) }}
                            @else
                                - 
                            @endif
                        </dd>
                        @endif

                        <dt class="col-sm-3">@lang('admin.member.pickup_address') :</dt>
                        <dd class="col-sm-8">{{ $request->s_address ? $request->s_address : '-' }}</dd>

                        <dt class="col-sm-3">Stop1 Address :</dt>
                        <dd class="col-sm-8">{{ $request->stop1_address ? $request->stop1_address : '-' }}</dd>

                        <dt class="col-sm-3">Stop2 Address :</dt>
                        <dd class="col-sm-8">{{ $request->stop2_address ? $request->stop2_address : '-' }}</dd>

                        <dt class="col-sm-3">@lang('admin.member.drop_address') :</dt>
                        <dd class="col-sm-8">{{ $request->d_address ? $request->d_address : '-' }}</dd>

                        <dt class="col-sm-3">Onboarding Waiting Time :</dt>
                        <dd class="col-sm-8">{{ $request->waiting_time ? $request->waiting_time : '-' }}</dd>

                        <dt class="col-sm-3">Trip Waiting Time :</dt>
                        <dd class="col-sm-8">{{ $request->stop_waiting_time ? $request->stop_waiting_time : '-' }}</dd>

                        @if($request->payment)
                        <dt class="col-sm-3">@lang('admin.member.base_price') :</dt>
                        <dd class="col-sm-8">{{ $request->payment->currency }} {{ $request->payment->base_fare }}</dd>

                        <dt class="col-sm-3">Flat Fare :</dt>
                        <dd class="col-sm-8">{{ $request->payment->currency }} {{ $request->payment->flat_fare }}</dd>

                        <dt class="col-sm-3">Minute Fare :</dt>
                        <dd class="col-sm-8">{{ $request->payment->currency }} {{ $request->payment->min_fare }}</dd>

                        <dt class="col-sm-3">Distance Fare :</dt>
                        <dd class="col-sm-8">{{ $request->payment->currency }} {{ $request->payment->distance_fare }}</dd>

                        <dt class="col-sm-3">Onboarding Waiting Fare :</dt>
                        <dd class="col-sm-8">{{ $request->payment->currency }} {{ $request->payment->waiting_fare }}</dd>

                        <dt class="col-sm-3">Stops Waiting Fare :</dt>
                        <dd class="col-sm-8">{{ $request->payment->currency }} {{ $request->payment->stop_waiting_fare }}</dd>

                        <dt class="col-sm-3">Toll Fare :</dt>
                        <dd class="col-sm-8">{{ $request->payment->currency }} {{ $request->payment->toll }}</dd>

                        <dt class="col-sm-3">Tax :</dt>
                        <dd class="col-sm-8">{{ $request->payment->currency }} {{ $request->payment->vat }}</dd>

                        <dt class="col-sm-3">Discount Price  :</dt>
                        <dd class="col-sm-8">{{ $request->payment->currency }} {{ $request->payment->discount }}</dd>

                        <dt class="col-sm-3">@lang('admin.member.total_amount') :</dt>
                        <dd class="col-sm-8">{{ $request->payment->currency }} {{ $request->payment->total }}</dd>


                        @endif

                        <dt class="col-sm-3">@lang('admin.member.ride_status') : </dt>
                        <dd class="col-sm-8">
                            {{ $request->status }}
                        </dd>

                    </dl>
                </div>
                <div class="col-md-12">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style type="text/css">
    #map {
        height: 450px;
    }
</style>
@endsection

@section('scripts')
<script type="text/javascript">
    var map;
    var zoomLevel = 11;

    function initMap() {

        map = new google.maps.Map(document.getElementById('map'));

        var marker = new google.maps.Marker({
            map: map,
            icon: '/asset/img/marker-start.png',
            anchorPoint: new google.maps.Point(0, -29)
        });

         var markerSecond = new google.maps.Marker({
            map: map,
            icon: '/asset/img/marker-end.png',
            anchorPoint: new google.maps.Point(0, -29)
        });

        var bounds = new google.maps.LatLngBounds();

        source = new google.maps.LatLng({{ $request->s_latitude }}, {{ $request->s_longitude }});
        destination = new google.maps.LatLng({{ $request->d_latitude }}, {{ $request->d_longitude }});

        marker.setPosition(source);
        markerSecond.setPosition(destination);

        var directionsService = new google.maps.DirectionsService;
        var directionsDisplay = new google.maps.DirectionsRenderer({suppressMarkers: true, preserveViewport: true});
        directionsDisplay.setMap(map);

        directionsService.route({
            origin: source,
            destination: destination,
            travelMode: google.maps.TravelMode.DRIVING
        }, function(result, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                console.log(result);
                directionsDisplay.setDirections(result);

                marker.setPosition(result.routes[0].legs[0].start_location);
                markerSecond.setPosition(result.routes[0].legs[0].end_location);
            }
        });

        @if($request->provider && $request->status != 'COMPLETED')
        var markerProvider = new google.maps.Marker({
            map: map,
            icon: "/asset/img/marker-car.png",
            anchorPoint: new google.maps.Point(0, -29)
        });

        provider = new google.maps.LatLng({{ $request->provider->latitude }}, {{ $request->provider->longitude }});
        markerProvider.setVisible(true);
        markerProvider.setPosition(provider);
        console.log('Provider Bounds', markerProvider.getPosition());
        bounds.extend(markerProvider.getPosition());
        @endif

        bounds.extend(marker.getPosition());
        bounds.extend(markerSecond.getPosition());
        map.fitBounds(bounds);
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ Setting::get('map_key', 'AIzaSyC7urojphmUg5qlseNH99Rojwn9Y-Amc0w') }}&libraries=places&callback=initMap" async defer></script>
@endsection