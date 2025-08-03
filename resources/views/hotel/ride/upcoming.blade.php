@extends('hotel.layout.base')

@section('title', 'Upcoming Trips ')

@section('content')

    @if(Session::has('flash_error'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">×</button>
            {{ Session::get('flash_error') }}
        </div>
    @endif


    @if(Session::has('flash_success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            {{ Session::get('flash_success') }}
        </div>
    @endif
    
    <div class="dash-content">
        <div class="row no-margin">
            <div class="col-md-12">
                <h4 class="page-title">@lang('user.upcoming_trips')</h4>
            </div>
        </div>
        <hr>
        <div class="row no-margin ride-detail">
            <div class="col-md-12">
                @if($trips->count() > 0)

                <table class="table table-condensed" style="border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>@lang('user.booking_id')</th>
                            <th>@lang('user.schedule_date')</th>
                            <th>Customer Name</th>
                            <th>@lang('user.type')</th>
                            <th>@lang('user.payment')</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                    @foreach($trips as $trip)

                        <tr data-toggle="collapse" data-target="#trip_{{$trip->id}}" class="accordion-toggle collapsed">
                            <td><span class="arrow-icon fa fa-chevron-right"></span></td>
                            <td>{{$trip->booking_id}}</td>
                            <td>{{date('d-m-Y H:i:s',strtotime($trip->schedule_at))}}</td>
                            <td>{{ $trip->user_name }}</td>
                            <td>{{$trip->service_name}}</td>
                            <td>{{$trip->payment_mode}}</td>
                            <td>@if($trip->provider_id !=0)
                                    <span class="label label-success">{{ $trip->status }}</span>
                                @else
                                    Not yet Accepted
                                @endif
                            </td>
                        </tr>
                        <tr class="hiddenRow">
                            <td colspan="6">
                                <div class="accordian-body collapse row" id="trip_{{$trip->id}}">
                                    <div class="col-md-8">
                                        <div class="my-trip-left">
                                            <div class="from-to">
                                                <div class="from">
                                                    <h5>@lang('user.from')</h5>
                                                    <p>{{$trip->s_address}}</p>
                                                </div>
                                                <div class="to">
                                                    <h5>@lang('user.to')</h5>
                                                    <p>{{$trip->d_address}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mytrip-right">
                                            <h5>Provider Details</h5>
                                            <div class="trip-user">
                                                @if($trip->provider_id !=0)
                                                <div class="user-img" style="background-image: url({{img($trip->provider_avatar)}});">
                                                </div>
                                                @endif
                                                <div class="user-right">
                                                @if($trip->provider_id !=0)
                                                    <h5>{{$trip->provider_name}}</h5>
                                                @endif
                                                @if($trip->provider_id !=0)
                                                    <span class="label label-success">{{ $trip->status }}</span>
                                                @else
                                                    Driver Not yet Assigned.
                                                @endif
                                                </div>
                                            </div>
                                            <div class="fare-break">
                                               <form method="POST" action="{{url('/hotel/cancel/ride')}}">
                                                  {{ csrf_field() }}
                                                     <input type="hidden" name="request_id" value="{{$trip->id}}" />
                                                   <button class="full-primary-btn fare-btn" type="submit">Cancel Ride</button>
                                               </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <p style="text-align: center;">No trips Available</p>
                @endif
            </div>
        </div>
    </div>
@endsection
