@extends('user.layout.app')

@section('content')
<!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h2 class="page-title"></h2>
        </div>
    </div>
    <!-- Page Header End -->
<div class="cps-main-wrap">
<!-- About us -->
        <div class="cps-section cps-section-padding" id="about">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="cps-section-header text-center">
                            <h3 class="cps-section-title">Welcome To {{ Setting::get('site_title','Unicotaxi') }}</h3>
                            <p class="cps-section-text">Depend on our company for reliable local and long-distance transportation. We offer low-cost travel to any location, as well as airport shuttle service to many nearby airports. Advanced reservations are welcomed and guaranteed, no matter the size of your group, so request a quote today for our quality transportation service.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="cps-about-img text-center">
                            <img class="img-responsive" src="{{asset('asset/theme/images/banner/mock-bg.png')}}" alt="...">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- About us end -->
		<div class="cps-cta cps-gray-bg style-4">
            <div class="container text-center">
                <h3 class="cps-cta-title">Driver &amp; Passenger App on Android and IOS</h3>
                <p class="cps-cta-text">Your App for Taxis, Cars and Beyond...</p>
                <div class="cps-cta-download">
                    <a href="{{Setting::get('store_link_android','#')}}" target="_blank"><img src="{{asset('asset/theme/images/googleplay.png')}}" alt="Download from Google Play"></a>
                    <a href="{{Setting::get('store_link_ios','#')}}" target="_blank"><img src="{{asset('asset/theme/images/appstore.png')}}" alt="Download from Play Store"></a>
                </div>
            </div>
        </div>
</div>
@endsection