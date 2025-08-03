@extends('hotel.layout.base')

@section('title', 'Profile ')

@section('styles')

@endsection

@section('content')
    <div class="row no-margin">
        <div class="col-md-12">
            <h4 class="page-title">@lang('user.profile.general_information') <a class="btn btn-sm btn-success" href="{{url('/hotel/edit/profile')}}">@lang('user.profile.edit')</a></h4> 
        </div>
    </div>
    <hr>
    <div class="col-md-12">
        <h5 class="col-md-4"><strong>@lang('user.profile.name')</strong></h5>
        <h5 class="col-md-6">{{Auth::guard('hotel')->user()->name}}</h5>                       
    </div>
    <div class="col-md-12">
        <h5 class="col-md-4"><strong>@lang('user.profile.email')</strong></h5>
        <h5 class="col-md-6">{{Auth::guard('hotel')->user()->email}}</h5>
    </div>

    <div class="col-md-12">
        <h5 class="col-md-4"><strong>@lang('user.profile.mobile')</strong></h5>
        <h5 class="col-md-6">{{Auth::guard('hotel')->user()->dial_code}} {{Auth::guard('hotel')->user()->mobile}}</h5>
    </div>
    
    <div class="col-md-12">
        <h5 class="col-md-4"><strong>Address</strong></h5>
        <h5 class="col-md-6">{{Auth::guard('hotel')->user()->address}}</h5>
    </div>

@endsection