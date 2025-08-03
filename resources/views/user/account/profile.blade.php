@extends('user.layout.base')

@section('title', 'Profile ')

@section('styles')

@endsection

@section('content')
    <div class="row no-margin">
        <div class="col-md-12">
            <h4 class="page-title">@lang('user.profile.general_information') <a class="btn btn-sm btn-success" href="{{url('edit/profile')}}">@lang('user.profile.edit')</a></h4> 
        </div>
    </div>
    <hr>
    <div class="col-md-12">
        <h5 class="col-md-4"><strong>First @lang('user.profile.name')</strong></h5>
        <h5 class="col-md-6">{{Auth::user()->first_name}}</h5>                       
    </div>
    <div class="col-md-12">
        <h5 class="col-md-4"><strong>Last @lang('user.profile.name')</strong></h5>
        <h5 class="col-md-6">{{Auth::user()->last_name}}</h5>                       
    </div>
    <div class="col-md-12">
        <h5 class="col-md-4"><strong>@lang('user.profile.email')</strong></h5>
        <h5 class="col-md-6">{{Auth::user()->email}}</h5>
    </div>

    <div class="col-md-12">
        <h5 class="col-md-4"><strong>@lang('user.profile.mobile')</strong></h5>
        <h5 class="col-md-6">{{Auth::user()->dial_code}} {{Auth::user()->mobile}}</h5>
    </div>

    <div class="col-md-12">
        <h5 class="col-md-4"><strong>Gender</strong></h5>
        <h5 class="col-md-6">{{Auth::user()->gender}}</h5>
    </div>

    <!-- <div class="col-md-12">
        <h5 class="col-md-4"><strong>@lang('user.profile.wallet_balance')</strong></h5>
        <h5 class="col-md-6"> {{ Setting::get('currency','$') }} {{Auth::user()->wallet_balance}}</h5>
    </div> -->

@endsection