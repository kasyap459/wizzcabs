@extends('provider.layout.base')

@section('title', 'UpComing Trips ')

@section('content')
    <div class="dash-content">
        <div class="row no-margin">
            <div class="col-md-12">
                <h4 class="page-title">@lang('driver.upcoming_trips')</h4>
            </div>
        </div>
        <hr>
        <div class="row no-margin ride-detail">
            <div class="col-md-12">
                @if($fully->count() > 0)
                <table class="table table-condensed" style="border-collapse:collapse;">
                    <thead>
                        <tr>
                            <tr>
                                <th>Booking ID</th>
                                <th>@lang('driver.pickup_time')</th>
                                <th>@lang('driver.pickup_address')</th>
                                <th>Drop Address</th>
                                <th>@lang('driver.status')</th>
                            </tr>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $fully_sum = 0; ?>
                            @foreach($fully as $each)
                                <tr>
                                    <td>{{$each->booking_id}}</td>
                                    <td>@if($each->schedule_at !=null)
                                            {{ $each->schedule_at }}
                                        @else
                                            {{ $each->assigned_at }}
                                        @endif
                                    </td>
                                    <td>{{$each->s_address}}</td>
                                    <td>{{$each->d_address}}</td>
                                    <td><span class="label label-primary label-sm">{{$each->status}}</span></td>
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
