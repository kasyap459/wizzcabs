@extends('provider.layout.base')

@section('title', 'My Trips ')

@section('content')
    <div class="dash-content">
        <div class="row no-margin">
            <div class="col-md-12">
                <h4 class="page-title">Drive Now</h4>
            </div>
        </div>
        <hr>
        <div class="earning-element row no-margin">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
                <div class="earning-box driver-box first">
                    <p>{{$today}}</p>
                    <p>Completed today</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
                <div class="earning-box driver-box second">
                    <p class="dashboard-count">{{$provider->accepted->count()}}</p>
                    <p class="dashboard-txt">Fully completed trips</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
                <div class="earning-box driver-box third">
                    <p class="dashboard-count">
                    @if($provider->accepted->count() != 0)
                        {{$provider->accepted->count()/$provider->accepted->count()*100}}%
                    @else
                        0%
                    @endif
                    </p>
                    <p class="dashboard-txt">Acceptance rate</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
                <div class="earning-box driver-box fourth">
                    <p class="dashboard-count">
                        {{$provider->cancelled->count()}}
                    </p>
                    <p class="dashboard-txt">Driver cancellations</p>
                </div>
            </div>
        </div>
    </div>
@endsection
