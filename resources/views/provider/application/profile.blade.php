@extends('provider.layout.base')

@section('title', 'Profile ')

@section('styles')

@endsection

@section('content')
    <div class="row no-margin">
        <div class="col-md-12">
            <h4 class="page-title">@lang('user.profile.general_information') <a class="btn btn-sm btn-success" href="{{url('/provider/edit/profile')}}">@lang('user.profile.edit')</a></h4> 
        </div>
    </div>
    <hr>
    <div class="col-md-12">
        <h5 class="col-md-3"><strong>@lang('user.profile.name')</strong></h5>
        <h5 class="col-md-6">{{ Auth::guard('provider')->user()->name }}</h5>                     
    </div>
    <div class="col-md-12">
        <h5 class="col-md-3"><strong>@lang('user.profile.email')</strong></h5>
        <h5 class="col-md-6">{{ Auth::guard('provider')->user()->email }}</h5>
    </div>

    <div class="col-md-12">
        <h5 class="col-md-3"><strong>@lang('user.profile.mobile')</strong></h5>
        <h5 class="col-md-6">{{ Auth::guard('provider')->user()->dial_code}} {{Auth::guard('provider')->user()->mobile}}</h5>
    </div>

    <div class="col-md-12">
        <h5 class="col-md-3"><strong>Bank account number</strong></h5>
        <h5 class="col-md-6">{{ Auth::guard('provider')->user()->acc_no}} {{Auth::guard('provider')->user()->acc_no}}</h5>
    </div>
    
    <div class="col-md-12">
        <h5 class="col-md-3"><strong>Gender</strong></h5>
        <h5 class="col-md-6">{{ Auth::guard('provider')->user()->gender }}</h5>
    </div>

    <div class="col-md-12">
        <h5 class="col-md-3"><strong>Account Status</strong></h5>
        <h5 class="col-md-6"><span class="label label-primary">{{ Auth::guard('provider')->user()->account_status }}</span></h5>
    </div>

    <div class="col-md-12">
        <h5 class="col-md-3"><strong>Carrier</strong></h5>
        <h5 class="col-md-6">
            @if(Auth::guard('provider')->user()->partner)
                {{ Auth::guard('provider')->user()->partner->carrier_name }}
            @else
                -
            @endif
        </h5>
    </div>

    <div class="col-md-12">
        <h5 class="col-md-3"><strong>Address</strong></h5>
        <h5 class="col-md-6">{{ Auth::guard('provider')->user()->address }}</h5>
    </div>

    <div class="col-md-12">
        <h5 class="col-md-3"><strong>Allowed Services</strong></h5>
        <h5 class="col-md-6">
            @if(Auth::guard('provider')->user()->allowed_service != "")
                @foreach(explode(',', Auth::guard('provider')->user()->allowed_service) as $service) 
                    {{ service_name($service) }},
                @endforeach
            @endif
        </h5>
    </div>

    <div class="col-md-12">
        <h5 class="col-md-3"><strong>Language</strong></h5>
        <h5 class="col-md-6">
            @if(Auth::guard('provider')->user()->language != "")
                @foreach(explode(',', Auth::guard('provider')->user()->language) as $lang) 
                    @switch($lang)
                        @case(1)
                            English,
                            @break
                        @case(2)
                            Spanish,
                            @break
                        @case(3)
                            French,   
                            @break
                        @case(4)
                            Korean,
                            @break
                        @case(5)
                            Russian,
                            @break
                        @case(6)
                            German,
                            @break
                        @case(7)
                            Portuguese,
                            @break
                        @case(8)
                            Italian,
                            @break
                        @case(9)
                            Urdu,
                            @break
                        @case(10)
                            Chinese,
                            @break
                        @case(11)
                            Tagalog,
                            @break
                        @case(12)
                            Vietnamese,
                            @break
                        @case(13)
                            Swahili,
                            @break
                        @default
                            -
                    @endswitch
                @endforeach
            @endif
        </h5>
    </div>

    <div class="col-md-12">
        <h5 class="col-md-3"><strong>License Number</strong></h5>
        <h5 class="col-md-6">{{ Auth::guard('provider')->user()->license_no }}</h5>
    </div>

    <div class="col-md-12">
        <h5 class="col-md-3"><strong>License Expire At</strong></h5>
        <h5 class="col-md-6">{{ Auth::guard('provider')->user()->license_expire }}</h5>
    </div>

@endsection