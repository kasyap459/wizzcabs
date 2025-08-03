@extends('provider.layout.base')

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
                @if($fully->count() > 0)
                <div class="earning-element">
                    <p class="earning-txt">Total Earnings</p>
                    <p class="earning-price" id="set_fully_sum">{{ currency_amt($total_sum) }}</p>
                </div>
                <table class="table table-condensed" style="border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th>@lang('driver.pickup_time')</th>
                            <th>@lang('driver.booking_id')</th>
                            <th>@lang('driver.vehicle')</th>
                            <th>@lang('driver.duration')</th>
                            <th>@lang('driver.status')</th>
                            <th>@lang('driver.distance')(KM)</th>
                            <th>@lang('driver.total_earnings')</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $fully_sum = 0; ?>
                        @foreach($fully as $each)
                            <tr>
                                <td>{{date('Y D, M d - H:i A',strtotime($each->created_at))}}</td>
                                <td>{{ $each->booking_id }}</td>
                                <td>{{ $each->service_name }}
                                </td>
                                <td>
                                    @if($each->finished_at != null && $each->started_at != null) 
                                        <?php 
                                        $StartTime = \Carbon\Carbon::parse($each->started_at);
                                        $EndTime = \Carbon\Carbon::parse($each->finished_at);
                                        echo $StartTime->diffInHours($EndTime). " Hours";
                                        echo " ".$StartTime->diffInMinutes($EndTime). " Minutes";
                                        ?>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{$each->status}}</td>
                                <td>{{$each->distance}}</td>
                                <td>{{$each->total}}</td>
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
