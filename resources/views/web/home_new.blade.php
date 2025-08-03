@extends('web.layouts.app')

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
        background-color: #f1ca0d;
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
    #more {display: none;}
    #more2 {display: none;}
    #more3 {display: none;}

    .pac-container, .pac-item {
    border-radius:10px !important;
    border: 0px !important;
}
.xdsoft_datetimepicker {
    border-radius:20px !important;
}
    

</style>
@endsection

@section('content')
<!-- Banner -->
<div class="main-banner-wrapper">
            <section class="banner-style-two owl-theme owl-carousel no-dots">
                <div class="slide slide-one" style="background-image: url(web/images/slider/slider-2-1.jpg);">
                    <div class="container">
                        <div class="row">
                             <div class="col-lg-12 text-center">
                                <div class="banner-circle mt-5" style="background-image: url(web/images/slider/slider-2-1.jpg);">
                                    <div class="inner-block mt-5">
                                        <h3>Now enjoy <br> comfortable <br> trip with <br>  {{ Setting::get('site_title','Unicotaxi') }}</h3>
                                         <!-- <a href="#" class="banner-btn">Learn More</a>  -->
                                    </div>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="slide slide-two" style="background-image: url(web/images/slider/slider-2-1.jpg);">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <div class="banner-circle mt-5" style="background-image: url(web/images/slider/slider-2-1.jpg);">
                                    <div class="inner-block mt-5">
                                        <h3>Now enjoy <br> comfortable <br> trip with <br> {{ Setting::get('site_title','Unicotaxi') }}</h3>
                                        <!-- <a href="#" class="banner-btn">Learn More</a> -->
                                    </div><!-- /.inner-block -->
                                </div><!-- /.banner-circle -->
                            </div><!-- /.col-lg-12 -->
                        </div><!-- /.row -->
                    </div><!-- /.container -->
                </div><!-- /.slide -->
            </section><!-- /.banner-style-one -->
            <!-- <div class="carousel-btn-block banner-carousel-btn">
                <span class="carousel-btn left-btn"><i class="conexi-icon-left"></i></span>
                <span class="carousel-btn right-btn"><i class="conexi-icon-right"></i></span>
            </div> -->
        </div><!-- /.main-banner-wrapper -->
        <section class="about-style-three clearfix">
            <div class="left-block">
                <div class="content-block">
                    <div class="image-block">
                        <img src="web/images/resources/book-1-1.jpg" alt="Awesome Image"/>
                    </div><!-- /.image-block -->
                    <div class="block-title">
                     <div><img src="{{asset('asset/img/unico.png')}}" alt="" width="20%"></div>
                        <p>We’re the best in your town</p>
                        <h2>Welcome to the <br> most trusted <br> company</h2>
                    </div><!-- /.block-title -->
                    <p>Get to your destination on time with speedy transportation from {{ Setting::get('site_title','Unicotaxi') }},
                    we transport clients to any location, local or long distance</p>
                    <hr class="style-one" />
                    <div class="tag-line">
                        <span>Safe .</span>
                        <span>Ontime .</span>
                        <span>Quick .</span>
                    </div><!-- /.tag-line -->
                </div><!-- /.content-block -->
            </div><!-- /.left-block -->
            <div class="right-block">
                <div class="right-upper-block">
                    <div class="content-block">
                        <div class="block-title">
                            <!-- <div><img src="{{asset('asset/img/unico.png')}}" alt="" width="30%"></div> -->
                            <p class="light-2">Looking for taxi?</p>
                            <h2 class="light">Make your <br> booking</h2>
                        </div><!-- /.block-title -->
                        <form action="{{ url('/booktaxi') }}" method="post"  class="booking-form-one">
                        {{csrf_field()}}
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="input-holder">
                                        <input type="text" name="name" id="name" placeholder="Your name" required>
                                    </div><!-- /.input-holder -->
                                </div><!-- /.col-lg-6 -->
                                <div class="col-lg-12">
                                    <div class="input-holder">
                                        <input type="email" name="email" id="email" placeholder="Email address">
                                    </div><!-- /.input-holder -->
                                </div><!-- /.col-lg-6 -->
                                <!-- <div class="col-lg-12">
                                    <div class="input-holder">
                                        <input type="text" name="passanger" id="passanger" placeholder="">
                                    </div>
                                </div> -->
                                <div class="col-lg-12">
                                    <div class="input-holder">
                                        <input type="text" name="s_address" id="source"  placeholder="(From)" onfocus="initMap1()" required="required" >
                                        <input type="hidden" name="s_latitude" id="s_latitude">
                                        <input type="hidden" name="s_longitude" id="s_longitude">
                                    </div><!-- /.input-holder -->
                                </div><!-- /.col-lg-12 -->
                                <div class="col-lg-12">
                                    <div class="input-holder" style="color: black;">
                                        <input type="text" name="d_address" id="destination" class="form-control" placeholder="(To)" onfocus="initMap2()" required="required">
                                        <input type="hidden" name="d_latitude" id="d_latitude">
                                        <input type="hidden" name="d_longitude" id="d_longitude">
                                    </div><!-- /.input-holder -->
                                </div><!-- /.col-lg-12 -->

                                <div class="col-lg-12">
                                    <div class="input-holder">
                        <select name="service_type" id="service_type" class="form-control" style="border: none;
                        outline: none;
                        width: 100%;
                        background-color: white;
                        height: 67px;
                        border-radius: 33.5px;
                        padding-left: 40px;
                        color: black;
                        font-size: 14px;
                        font-weight: 600;
                        -webkit-appearance: none;
                        -moz-appearance: none;
                        text-indent: 1px;
                        text-overflow: '';">
                        <option value="">Please select</option>
                            @foreach($services as $service) 
                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div> 
                </div>

                                <div class="col-lg-12">
                                    <div class="input-holder" >
                                   <select name="ride" id="ride" class="form-control" style="border: none;
                                   outline: none;
                                   width: 100%;
                                   background-color: white;
                                   height: 67px;
                                   border-radius: 33.5px;
                                   padding-left: 40px;
                                   color: black;
                                   font-size: 14px;
                                   font-weight: 600;
                                   -webkit-appearance: none;
                                    -moz-appearance: none;
                                    text-indent: 1px;
                                    text-overflow: '';">
                                        <option value="Ride Now">Ride Now</option>
                                        <option value="Shedule Ride">Shedule Ride</option>
                                  </select>
                               </div>   
                             </div>

                                <div class="col-lg-12">
                                    <div class="input-holder scheduleride" >
                                        <input type="text" name="schedule_time" id="schedule_time" placeholder="Select date">
                                        <i class="conexi-icon-small-calendar"></i>
                                    </div><!-- /.input-holder -->
                                </div><!-- /.col-lg-6 -->
                                <!-- <div class="col-lg-6">
                                    <div class="input-holder">
                                        <select class="selectpicker">
                                            <option>Select Time</option>
                                            <option>10AM-10.59AM</option>
                                            <option>12PM-12.59PM</option>
                                            <option>1PM-1.59PM</option>
                                            <option>2PM-2.59PM</option>
                                        </select>
                                        <i class="conexi-icon-clock"></i>
                                    </div>
                                </div> -->
                                <div class="col-lg-12">
                                    <div class="input-holder">
                                        <button type="submit">Book Now</button>
                                    </div><!-- /.input-holder -->
                                </div><!-- /.col-lg-12 -->

                                <div class="row padding-bottom">
                <div class="col-md-12">
                    @if(Session::has('flash_error'))
                        <p class="alert-text" style="color: #fff;background-color: #af271b;">{{ Session::get('flash_error') }}</p>
                    @endif

                    @if(Session::has('flash_success'))
                        <p class="alert-text" style="color: #fff;background-color: #07bb07;">{{ Session::get('flash_success') }}</p>
                    @endif
                </div>
            </div>
                            </div><!-- /.row -->
                        </form><!-- /.booking-form-one -->
                    </div><!-- /.content-block -->
                </div><!-- /.right-upper-block -->
                <div class="right-bottom-block">
                    <div class="content-block cta-block">
                        <div class="icon-block">
                            <i class="conexi-icon-phone-call"></i>
                        </div><!-- /.icon-block -->
                        <div class="text-block">
                            <p>Call and book your taxi</p>
                            <a href="callto:1234567890">1234567890</a>
                        </div><!-- /.text-block -->
                    </div><!-- /.content-block -->
                </div><!-- /.right-bottom-block -->
            </div><!-- /.right-block -->
        </section><!-- /.about-style-three -->
        <section class="offer-style-one" style="background:#FFF;">
            <div class="container">
                <div class="block-title text-center">
                    <div><img src="{{asset('asset/img/unico.png')}}" alt="" width="10%"></div> 
                    <p>Check out our benefits</p>
                    <h2>What we’re offering</h2>
                </div><!-- /.block-title -->
                <div class="row">
                    <div class="col-lg-4">
                        <div class="single-offer-one hvr-float-shadow">
                            <div class="image-block">
                                <a href="#"><i class="fa fa-link"></i></a> 
                                <img src="web/images/resources/offer-1-1.jpg" alt="Awesome Image" />
                            </div><!-- /.image-block -->
                            <div class="text-block">
                                <h3><a href="#">Tap the app, get a ride</a></h3>
                                <p>{{ Setting::get('site_title','Unicotaxi') }} is the smartest way to get around. One tap and a car comes directly to you<span id="dots">...</span><span id="more">. Your driver knows exactly where to go. And you can pay with either cash or card. Advanced reservations are welcomed and guaranteed, no matter the size of your group, so request a quote today for our quality transportation service.</span></p>
                                <a onclick="myFunction()" id="myBtn" class="more-link">Read More</a>
                            </div><!-- /.text-block -->
                        </div><!-- /.single-offer-one -->
                    </div><!-- /.col-lg-4 -->
                    <div class="col-lg-4">
                        <div class="single-offer-one hvr-float-shadow">
                            <div class="image-block">
                                <a href="#"><i class="fa fa-link"></i></a>
                                <img src="web/images/resources/offer-1-2.jpg" alt="Awesome Image" />
                            </div><!-- /.image-block -->
                            <div class="text-block">
                                <h3><a href="#">Ready anywhere, anytime</a></h3>
                                <p>Daily commute. Errand across town. Early morning flight. Lat<span id="dots2">...</span><span id="more2">e night drinks. Wherever you’re headed, count on {{ Setting::get('site_title','Unicotaxi') }} for a ride—no reservations needed. We charge low rates and drive clean and well-maintained vehicles, all to ensure a pleasant and affordable ride. When you're in need of prompt and dependable taxi service, count on us!</span></p>
                                <a onclick="myFunction2()" id="myBtn2" class="more-link">Read More</a>
                            </div><!-- /.text-block -->
                        </div><!-- /.single-offer-one -->
                    </div><!-- /.col-lg-4 -->
                    <div class="col-lg-4">
                        <div class="single-offer-one hvr-float-shadow">
                            <div class="image-block">
                                <a href="#"><i class="fa fa-link"></i></a>
                                <img src="web/images/resources/offer-1-3.jpg" alt="Awesome Image" />
                            </div><!-- /.image-block -->
                            <div class="text-block">
                                <h3><a href="#">Easy to use</a></h3>
                                <p>Both riders and vehicles are geolocalized, an easy way to find each othe<span id="dots3">...</span><span id="more3">r. For a perfect transparency and service optimization, riders can rate the driver.</span></p>
                                <a onclick="myFunction3()" id="myBtn3" class="more-link">Read More</a>
                            </div><!-- /.text-block -->
                        </div><!-- /.single-offer-one -->
                    </div><!-- /.col-lg-4 -->
                </div><!-- /.row -->
            </div><!-- /.container -->
        </section><!-- /.offer-style-one -->
      <!-- <hr class="style-one"> -->
        <section class="cta-style-three" style="background:#FFF;">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 d-flex">
                        <div class="my-auto image-block-wrapper text-center">
                            <div class="image-block">
                                <img src="web/images/resources/mock-new5_Pas.png" style="height: 500px;width: 400px;" alt="Awesome Image" />
                            </div><!-- /.image-block -->
                        </div><!-- /.my-auto -->
                    </div><!-- /.col-lg-6 -->
                    <div class="col-lg-6">
                        <div class="content-block">
                            <div class="block-title">
                                <div><img src="{{asset('asset/img/unico.png')}}" alt="" width="20%"></div> 
                                <p>Book now from application</p>
                                <h2>Get a Free Mobile Application</h2>
                            </div><!-- /.block-title -->
                            <p>We give our customers the best possible experience by allowing them to book a taxi seamlessly anytime with our feature-rich Passenger App. With immense experience in offering the best transportation services, we allow users to hail a cab with a single click. Download and install the Taxi Booking Application from the App Store/Google Play Store now and enjoy a hassle-free ride experience!</p>

                            <div class="button-block">
                                <a href="#" class="app-btn apple-btn mb-3">
                                    <i class="fa fa-apple icon"></i>
                                    <span class="text-block">
                                        <span class="tag-line">Download From</span>
                                        <span class="store-name">Apple Store</span>
                                    </span>
                                </a>
                                <a href="#" class="app-btn google-btn mb-3">
                                    <i class="fa fa-android icon"></i>
                                    <span class="text-block">
                                        <span class="tag-line">Download From</span>
                                        <span class="store-name">Google Play</span>
                                    </span>
                                </a>
                            </div><!-- /.button-block -->
                        </div><!-- /.content-block -->
                    </div><!-- /.col-lg-6 -->
                </div><!-- /.row -->
            </div><!-- /.container -->
        </section><!-- /.cta-style-three -->
      
       
        <section class="cta-style-two no-zigzag">
            <div class="container">
                <div class="content-block">
                    <p>Make a call or fill form</p>
                    <h3>Call our agent to get a quote.</h3>
                </div><!-- /.content-block -->
                <div class="button-block">
                    <a href="{{url('/book-taxi')}}" class="cta-btn">Book Taxi Now</a>
                </div><!-- /.button-block -->
            </div><!-- /.container -->
        </section><!-- /.cta-style-two -->
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.full.min.js"></script>
<script type="text/javascript">
    
    $(document).ready(function(){
        $('#schedule_time').val('{{ \Carbon\Carbon::now() }}');
    });
    var mindate = {!! json_encode( \Carbon\Carbon::now()->format('Y-m-d\TH:i') ) !!}
    $('#schedule_time').datetimepicker({
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
<script>
function myFunction() {
  var dots = document.getElementById("dots");
  var moreText = document.getElementById("more");
  var btnText = document.getElementById("myBtn");

  if (dots.style.display === "none") {
    dots.style.display = "inline";
    btnText.innerHTML = "Read more"; 
    moreText.style.display = "none";
  } else {
    dots.style.display = "none";
    btnText.innerHTML = "Read less"; 
    moreText.style.display = "inline";
  }
}
</script>
<script>
function myFunction2() {
  var dots = document.getElementById("dots2");
  var moreText = document.getElementById("more2");
  var btnText = document.getElementById("myBtn2");

  if (dots.style.display === "none") {
    dots.style.display = "inline";
    btnText.innerHTML = "Read more"; 
    moreText.style.display = "none";
  } else {
    dots.style.display = "none";
    btnText.innerHTML = "Read less"; 
    moreText.style.display = "inline";
  }
}
</script>
<script>
function myFunction3() {
  var dots = document.getElementById("dots3");
  var moreText = document.getElementById("more3");
  var btnText = document.getElementById("myBtn3");

  if (dots.style.display === "none") {
    dots.style.display = "inline";
    btnText.innerHTML = "Read more"; 
    moreText.style.display = "none";
  } else {
    dots.style.display = "none";
    btnText.innerHTML = "Read less"; 
    moreText.style.display = "inline";
  }
}
</script>
@endsection