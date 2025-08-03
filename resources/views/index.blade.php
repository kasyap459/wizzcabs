@extends('user.layout.app')

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/jquery.datetimepicker.min.css" />
<style type="text/css">
    .padding-bottom{
        margin-bottom: 14px;
    }
    .booking-option{
            background-color: #fff;
            padding: 13px 23px 1px;
            margin-top: 30px;
            width: 90%;
            text-shadow: 0px 0px 0px #fff;
            margin: 30px auto 0px;
            color: #565656;
    }
    .border-bottom{
        border-bottom: 2px solid #dedada;
    }
    .booking-option h4{
        text-align: left;
        color: #3a3939;
        text-shadow: none;
        font-size: 15px;
    }
    .booking-option .img-nav img{
        width: 39px;
    }
    .booking-option .nav>li>a {
        position: relative;
        display: block;
        padding: 10px 4px;
    }
    .booking-option .img-nav{
        display: inline;
        float: left;
    }
    .booking-option .content-nav{
        width: 137px;
        display: inline-block;
    }
    .booking-option .content-nav p{
        text-align: left;
        font-size: 12px;
        line-height: 7px;
        color: #565656;
        text-shadow: none;
        padding-left: 7px;
    }
    .booking-form{
        background-color: #ffe699;
        padding: 13px 23px 1px;
        margin-top: 30px;
        width: 90%;
        margin: 0px auto 90px;
    }
    .booking-form input{
        background-color: #fff !important;
        padding: 0 8px !important;
        border-radius: 5px !important;
        height: 40px !important;
        font-size: 14px !important;
        border-color: #b9babb !important;
    }
    .booking-form select{
        background-color: #fff !important;
        padding: 0 0px !important;
        border-radius: 5px !important;
        height: 40px !important;
        font-size: 14px !important;
        border-color: #b9babb !important;
    }
    .booking-form .col-md-4{
        padding: 0px 3px !important;
    }
    .booking-form .col-md-8{
        padding: 0px 3px !important;
    }
    .booking-form .col-md-12{
        padding: 0px 3px !important;
    }
    .booking-form .col-md-6{
        padding: 0px 3px !important;
    }
    .alert-text{
        text-shadow: none;
        display: inline;
        padding: 5px;
    }
</style>
@endsection

@section('content')
<!-- Banner -->
    <div class="cps-banner style-5" id="banner">
        <div class="cps-banner-item cps-banner-item-10">
            <div class="overlay">
                <div class="cps-banner-content">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <h1 class="cps-banner-title">Comfort <span>Transportation </span> <br> For your Business and Personal Requirements</h1>
                                <p class="cps-banner-text">Get to your destination on time with speedy transportation from {{ Setting::get('site_title','Unicotaxi') }}, <br>we transport clients to any location, local or long distance</p>
                                <div class="cps-button-group">
                                    <a class="btn btn-primary" href="{{url('register')}}">Sign up - it's Free!</a>
                                    <p class="sign-in-text">Already using {{ Setting::get('site_title','Unicotaxi') }} ? <a href="{{url('/login')}}">Sign in</a></p>
                                </div>

                                
    <form action="{{ url('/booktaxi') }}" method="post">
        {{csrf_field()}}
        <div class="booking-option" style="display: none">
            <div class="row">
                <div class="col-md-12 padding-bottom border-bottom">
                    <div class="col-md-2">
                        <h4>Car Types</h4>
                    </div>
                    <div class="col-md-10">
                        <ul class="nav nav-pills">
                          @foreach($services as $service)  
                          <li class="nav-item">
                            <a class="nav-link active" href="#">
                                <div class="img-nav"><img src="{{$service->image}}"></div>
                                <div class="content-nav">
                                    <p>{{ $service->name }}</p>
                                    <p>4 personer & 3 bagage</p>
                                </div>
                            </a>
                          </li>
                          @endforeach
                        </ul>
                    </div> 
                </div>
                <div class="col-md-12 padding-bottom border-bottom">
                    <div class="col-md-2">
                        <h4>Options</h4>
                    </div>
                    <div class="col-md-10">
                        <div class="col-md-4">
                            <input type="checkbox" name="pet" id="pet" value="1"><label for="pet"><img src="{{asset('asset/img/pets.png')}}" style="width: 30px;">Animal</label></div>
                        <div class="col-md-4">
                            <input type="checkbox" name="booster" id="booster" value="1"><label for="booster"><img src="{{asset('asset/img/child_seat.png')}}" style="width: 30px;">High chair for 9 months</label></div>
                        <div class="col-md-4">
                            <input type="checkbox" name="wagon" id="wagon" value="1"><label for="wagon"><img src="{{asset('asset/img/child_booster.png')}}" style="width: 35px;">Station wagon</label></div>
                    </div> 
                </div>
                <div class="col-md-12 ">
                    <div class="col-md-2">
                        <h4>Payment</h4>
                    </div>
                    <div class="col-md-10">
                        <div class="col-md-4">
                            <input type="checkbox" name="payment_mode" id="payment_mode" value="CASH"><label for="payment_mode"> Pay in Taxi</label></div>
                        <div class="col-md-4">
                            <input type="checkbox" name="fixed_price" id="fixed_price" value="1"><label for="fixed_price"> Fixed Price</label></div>
                    </div> 
                </div>
            </div>
        </div>
      
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
<div class="cps-section cps-section-padding">
    <div>
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-xs-12 sm-text-center">
                    <h1 class="cps-banner-title">ENJOY <span>TRANSPORT SERVICE</span></h1>
                    <p class="cps-banner-text">Get around with a  <strong>Transportation </strong> service</p>
                    <div class="button-group">
                        <a href="{{url('/login')}}"class="btn btn-primary btn-square">Let's Get Started</a>
                    </div>
                </div>
                <div class="col-md-6">
                    <img class="img-responsive" src="{{asset('asset/theme/images/home1.jpg')}}" alt="Banner Mock">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="cps-section cps-section-padding">
    <div class="container">
        <div class="row">
            <div class="cps-service-boxs">
                <div class="col-sm-4">
                    <div class="cps-service-box style-8">
                        <div class="cps-service-icon">
                            <img class="img-responsive" src="{{asset('asset/theme/images/custom1.png')}}" alt="...">
                        </div>
                        <h4 class="cps-service-title">Schedule Your Ride</h4>
                        <p class="cps-service-text">Schedule your ride several months in advance.One less thing to worry about. Each category has its own price system.</p>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="cps-service-box style-8">
                        <div class="cps-service-icon">
                            <img class="img-responsive" src="{{asset('asset/theme/images/custom2.png')}}" alt="...">
                        </div>
                        <h4 class="cps-service-title">Easy to use</h4>
                        <p class="cps-service-text">Both riders and vehicles are geolocalized, an easy way to find each other. For a perfect transparency and service optimization, riders can rate the driver. </p>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="cps-service-box style-8">
                        <div class="cps-service-icon">
                            <img class="img-responsive" src="{{asset('asset/theme/images/custom3.png')}}" alt="...">
                        </div>
                        <h4 class="cps-service-title">24 Hours Supports</h4>
                        <p class="cps-service-text">Transportation service is available 24/7. Where you want. When you want.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="cps-section cps-section-padding cps-black-bg">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 text-center">
                <div class="text-center">
                    <h3 class="cps-section-title"><span class="blue-color" style="color: white;">Rides</span></h3>
                </div>
                <div class="custom-img-map">
                    <img class="img-responsive" src="{{asset('asset/theme/images/custom-map.png')}}" alt="...">
                    <span class="point-on-map" style="bottom: 60.5%; left: 10%;background-color: #00aff0;">United States</span>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- Banner End -->
<div class="cps-section cps-section-padding cps-bottom-0">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2 col-xs-12">
                        <div class="cps-section-header text-center">
                            <h3 class="cps-section-title">{{ Setting::get('site_title','Unicotaxi') }} Mobile App</h3>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="cps-section-header text-left">
                            <h3 class="cps-section-title">Tap the app, get a ride</h3>
                            <p class="cps-section-text">{{ Setting::get('site_title','Unicotaxi') }} is the smartest way to get around. One tap and a car comes directly to you. Your driver knows exactly where to go. And you can pay with either cash or card. Advanced reservations are welcomed and guaranteed, no matter the size of your group, so request a quote today for our quality transportation service.</p>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12 text-center">
                        <img class="img-responsive features-side-img" src="{{asset('asset/img/tap.png')}}" alt="Features Image" style="height: 218px;">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="cps-section cps-section-padding cps-bottom-0">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 col-xs-12 col-sm-push-6">
                        <div class="cps-section-header text-left">
                            <h3 class="cps-section-title">Ready anywhere, anytime</h3>
                            <p class="cps-section-text">Daily commute. Errand across town. Early morning flight. Late night drinks. Wherever you’re headed, count on {{ Setting::get('site_title','Unicotaxi') }} for a ride—no reservations needed. We charge low rates and drive clean and well-maintained vehicles, all to ensure a pleasant and affordable ride. When you're in need of prompt and dependable Transportation service, count on us!</p>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12 col-sm-pull-6">
                        <img class="img-responsive features-side-img" src="{{asset('asset/img/anywhere.png')}}" alt="Features Image">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
        <div class="col-sm-6"> 
        <div class="cps-cta cps-gray-bg style-4" style="margin-left: 20px;">
            <!-- <div class="container text-center"> -->
                <h3 class="cps-cta-title">Download  Passenger App </h3>
                <p class="cps-cta-text"></p>
                <div class="cps-cta-download">
                    <a href="{{Setting::get('store_link_android','#')}}" target="_blank"><img src="{{asset('asset/theme/images/googleplay.png')}}" alt="Download from Google Play"></a>
                    <a href="{{Setting::get('store_link_ios','#')}}" target="_blank"><img src="{{asset('asset/theme/images/appstore.png')}}" alt="Download from Play Store"></a>
                </div>
            <!-- </div> -->
            <!-- <p>2023</p> -->
        </div>
        </div>
      
        <div class="col-sm-6"> 
        <div class="cps-cta cps-gray-bg style-4" style="margin-right: 20px; padding-bottom: 86px">
            <!-- <div class="container text-center"> -->
                <h3 class="cps-cta-title">Download Driver  App </h3>
                <p class="cps-cta-text"></p>
                <div class="cps-cta-download">
                    <a href="{{Setting::get('store_link_android_driver','#')}}" target="_blank"><img src="{{asset('asset/theme/images/googleplay.png')}}" alt="Download from Google Play"></a>
                    <a href="{{Setting::get('store_link_ios_driver','#')}}" target="_blank"><img src="{{asset('asset/theme/images/appstore.png')}}" alt="Download from Play Store"></a>
                </div>
            <!-- </div> -->
            <!-- <p>2023</p> -->
        </div>
        </div>
        </div>
        <div class="cps-footer-lower">
            <div class="container">
                <div class="row">
                    <div class="xs-text-center" style="text-align: center; padding: 25px;">
                        <p class="copyright">{{ Setting::get('site_copyright') }}</p>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.full.min.js"></script>
<script type="text/javascript">
    
    $(document).ready(function(){
        $('#schedule_at').val('{{ \Carbon\Carbon::now() }}');
    });
    var mindate = {!! json_encode( \Carbon\Carbon::now()->format('Y-m-d\TH:i') ) !!}
    $('#schedule_at').datetimepicker({
        format:'Y-m-d H:i:s',
        minDate: mindate
    });

    $(document).ready(function(){
        $('#addition').on('click', function(event) {        
             $('.booking-option').toggle();
        });
	$('.scheduleride').hide();
	$('#ride').on('change', function() {
		var a = $('#ride').val();
		if(a == "Shedule Ride"){
			$('.scheduleride').show();
		}else{
			$('.scheduleride').hide();
		}       
    	});
    });
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ Setting::get('map_key', 'AIzaSyC7urojphmUg5qlseNH99Rojwn9Y-Amc0w') }}&libraries=places" async defer></script>
<script type="text/javascript">
  function initMap1() {
    var sourceInput = document.getElementById('source');
    var sourceLatitude = document.getElementById('s_latitude');
    var sourceLongitude = document.getElementById('s_longitude');
    var sourceAutocomplete = new google.maps.places.Autocomplete(
            sourceInput);

    sourceAutocomplete.addListener('place_changed', function(event) {
        var place = sourceAutocomplete.getPlace();
        if (place.hasOwnProperty('place_id')) {
            if (!place.geometry) {
                    // window.alert("Autocomplete's returned place contains no geometry");
                    return;
            }
            sourceLatitude.value = place.geometry.location.lat();
            sourceLongitude.value = place.geometry.location.lng();
        }
    });
  }
  function initMap2() {
    var destinationInput = document.getElementById('destination');
    var destinationLatitude = document.getElementById('d_latitude');
    var destinationLongitude = document.getElementById('d_longitude');
    var destinationAutocomplete = new google.maps.places.Autocomplete(
            destinationInput);

    destinationAutocomplete.addListener('place_changed', function(event) {
        var place = destinationAutocomplete.getPlace();
        if (place.hasOwnProperty('place_id')) {
            if (!place.geometry) {
                    // window.alert("Autocomplete's returned place contains no geometry");
                    return;
            }
            destinationLatitude.value = place.geometry.location.lat();
            destinationLongitude.value = place.geometry.location.lng();
        }
    });
  }
</script>
@endsection