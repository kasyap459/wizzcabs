@extends('user.layout.base')

@section('title', 'My Trips ')

@section('content')

    <div class="dash-content">
        <div class="row no-margin">
            <div class="col-md-12">
                <h4 class="page-title">@lang('user.my_trips')</h4>
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
                            <th>@lang('user.date')</th>
                            <th>@lang('user.profile.name')</th>
                            <th>@lang('user.amount')</th>
                            <th>@lang('user.type')</th>
                            <th>@lang('user.payment')</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                    @foreach($trips as $trip)

                        <tr data-toggle="collapse" data-target="#trip_{{$trip->id}}" class="accordion-toggle collapsed">
                            <td><span class="arrow-icon fa fa-chevron-right"></span></td>
                            <td>{{ $trip->booking_id }}</td>
                            <td>{{date('d-m-Y',strtotime($trip->created_at))}}</td>
                            @if($trip->provider_name !=null)
                                <td>{{$trip->provider_name}}</td>
                            @else
                                <td>-</td>
                            @endif
                            @if($trip->status =='COMPLETED')
                                <td>{{ $trip->total }}&nbsp;{{$trip->new}}</td>
                            @else
                                <td>-</td>
                            @endif
                            
                            <td>{{$trip->service_name}}</td>
                            
                            <td>@if($trip->corporate_id !=0)
                                    CORPORATE
                                @else
                                    {{$trip->payment_mode}}
                                @endif</td>
                            <td>
                                @if($trip->status =='COMPLETED')
                                    <span class="label label-success">{{ $trip->status }}</span>
                                @else
                                    <span class="label label-danger">{{ $trip->status }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr class="hiddenRow">
                            <td colspan="12">
                                <div class="accordian-body collapse row" id="trip_{{$trip->id}}">
                                    <div class="col-md-8">
                                        <div class="my-trip-left">
                                            <div class="from-to">
                                                <div class="from">
                                                    <h5>@lang('user.from')</h5>
                                                    
                                                    <h6>@if($trip->status =='COMPLETED'){{date('Y D, M d - H:i A',strtotime($trip->started_at))}}@else{{date('Y D, M d - H:i A',strtotime($trip->created_at))}}@endif</h6>
                                                    <p>{{$trip->s_address}}</p>
                                                </div>
                                                <div class="to">
                                                    <h5>@lang('user.to')</h5>
                                                    <h6>@if($trip->status =='COMPLETED'){{date('Y D, M d - H:i A',strtotime($trip->started_at))}}@else{{date('Y D, M d - H:i A',strtotime($trip->created_at))}}@endif</h6>
                                                    <p>{{$trip->d_address}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">

                                        <div class="mytrip-right">
                                            <div class="from-to">
                                                <div class="to">
                                                    <h5>@lang('user.payment')</h5>
                                                    <h6>@if($trip->corporate_id !=0)
                                                            CORPORATE
                                                        @else
                                                            {{$trip->payment_mode}}
                                                        @endif
                                                    </h6>
                                                </div>
                                                <div class="to">
                                                    <h5>@lang('user.amount')</h5>
                                                    <h6>@if($trip->status =='COMPLETED')
                                                            {{ $trip->total }}&nbsp;{{$trip->new}}
                                                        @else
                                                            -
                                                        @endif
                                                    </h6>
                                                </div>
                                                <div class="to">
                                                    <h5>@lang('user.profile.name')</h5>
                                                    <h6>@if($trip->provider_name !=null)
                                                            {{$trip->provider_name}}
                                                        @else
                                                            -
                                                        @endif
                                                    </h6>
                                                </div>
                                                @if($trip->rating)
                                                <div class="to">
                                                    <h5>Rating</h5>
                                                    <div class="rating-outer">
                                                        <input type="hidden" class="rating" value="{{$trip->rating->user_rating}}" />
                                                    </div>
                                                    <h6>{{$trip->rating->user_comment}}</h6>
                                                </div>
                                                @else
                                                    -
                                                @endif
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

@section('scripts')
    <script type="text/javascript" src="{{asset('asset/userpanel/js/rating.js')}}"></script>    
    <script type="text/javascript">
        $('.rating').rating();
    </script>
@endsection