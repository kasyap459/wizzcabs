@extends('hotel.layout.base')

@section('title', 'Dashboard ')

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/jquery.datetimepicker.min.css" />
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" 
integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" 
  crossorigin="anonymous">
<link rel="stylesheet" href="{{asset('main/ZebraDatetimePicker/css/default/zebra_datepicker.min.css')}}" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<style type="text/css">
.checkbox {
    opacity: 0;
    position: absolute;
}

.label {
    /*background-color: #f0f0f0;*/
    border-radius: 50px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 5px;
    color: #333333;
    padding-bottom: 0px;
    margin-bottom: 0px;
    position: relative;
    border: 1px solid #e4dede;
    height: 23px;
    width: 47px;
    transform: scale(1.5);
}

.label .ball {
    background-color: #27AB18;
    border-radius: 50%;
    position: absolute;
    top: -1px;
    left: 0px;
    height: 23px;
    width: 22px;
    z-index: -1;
    transform: translateX(0px);
    transition: transform 0.2s linear;
}

.checkbox:checked + .label .ball {
    transform: translateX(24px);
}    

/* Steps */
.step {
  position: relative;
  min-height: 4rem;
  color: gray;
  top: 0px;
}
/*.step + .step {
  margin-top: 1.5em
}*/
.step > div:first-child {
  position: static;
  height: 0;
}
.step > div:not(:first-child) {
  margin-left: 1.5em;
}
.step.step-active .circle {
  background-color: #CC0000 !important;
}

/* Circle */
.circle {
  background: #27AB18 !important;
  position: relative;
  width: 10px;
  height: 10px;
  top:18px;
  border-radius: 100%;
  color: #fff;
  text-align: center;
  box-shadow: 0 0 0 3px #fff;
}

/* Vertical Line */
.circle:after {
  content: ' ';
  position: absolute;
  display: block;
  top: -17px;
  right: 50%;
  bottom: 1px;
  left: 50%;
  height: 30px;
  width: 1px;
  transform: scale(1, 2);
  transform-origin: 50% -100%;
  background-color: #27AB18;
  z-index: 0;
}
.step:nth-child(2) .circle:after {
  display: none !important;
}
.step:nth-child(2) .circle {
  top: 15px !important;
}

/* Stepper Titles */
.title {
  line-height: 1.5em;
  font-weight: bold;
}
.caption {
  font-size: 0.8em;
}
body {
    background: none !important;
}
#container {
    position: relative; 
    padding: 20px 40px;
    text-align: justify;
    height: 100%;
    width: 100%;
    background-color: white;
    overflow: auto;
}
.car-detail {
    margin-top: 0px;
    margin-bottom: 0px;
}
header {
    padding-bottom: 0px;
}
hr {
    margin-top: 0px;
    margin-bottom: 20px;
    border: 0;
    border-top: 1px solid #eee;
}
.logo {
    text-align: center;
    padding: 0px !important;
}
.fa-user:before {
    content: "\f007";
    margin-right: 4px;
}
.input-group-addon {
    padding: 0px 12px;
}
.input-group-addon:hover {
    cursor: pointer;
}
/* circle1 css */
.step.step-active .circle1 {
  background-color: #CC0000 !important;
}
.circle1 {
  background: #27AB18 !important;
  position: relative;
  width: 10px;
  height: 10px;
  top:18px;
  border-radius: 100%;
  color: #fff;
  text-align: center;
  box-shadow: 0 0 0 3px #fff;
}

/* Vertical Line */
.circle1:after {
  content: ' ';
  position: absolute;
  display: block;
  top: -60px;
  right: 50%;
  bottom: 1px;
  left: 50%;
  height: 75px;
  width: 1px;
  transform: scale(1, 2);
  transform-origin: 50% -100%;
  background-color: #27AB18;
  z-index: 0;
}
.step:nth-child(2) .circle1:after {
  display: none !important;
}
.step:nth-child(2) .circle1 {
  top: 15px !important;
}
.circle1 {
    background: #00c292;
    position: relative;
    width: 10px;
    height: 10px;
    top: 18px;
    /* line-height: 1.5em; */
    border-radius: 100%;
    color: #fff;
    text-align: center;
    box-shadow: 0 0 0 3px #fff;
}


/* circle2 css */
.step.step-active .circle2 {
  background-color: #CC0000 !important;
}
.circle2 {
  background: #27AB18 !important;
  position: relative;
  width: 10px;
  height: 10px;
  top:18px;
  border-radius: 100%;
  color: #fff;
  text-align: center;
  box-shadow: 0 0 0 3px #fff;
}

/* Vertical Line */
.circle2:after {
  content: ' ';
  position: absolute;
  display: block;
  top: -35px;
  right: 50%;
  bottom: 1px;
  left: 50%;
  height: 50px;
  width: 1px;
  transform: scale(1, 2);
  transform-origin: 50% -100%;
  background-color: #27AB18;
  z-index: 0;
}
.step:nth-child(2) .circle2:after {
  display: none !important;
}
.step:nth-child(2) .circle2 {
  top: 15px !important;
}
.circle2 {
    background: #00c292;
    position: relative;
    width: 10px;
    height: 10px;
    top: 18px;
    /* line-height: 1.5em; */
    border-radius: 100%;
    color: #fff;
    text-align: center;
    box-shadow: 0 0 0 3px #fff;
}
.Zebra_DatePicker_Icon_Wrapper {
    width: auto !important;
}
li > a {
    text-decoration: none;
    color: #fff;
}
nav {
    color: #fff;
}
.offside {
    background-color: #296738;
}
</style>
@endsection

@section('content')
<div class="col-md-6">
    <div class="row">
        <h4 class="page-title">New Booking</h4>
    </div>
    <hr>
    <div class="alert alert-danger">
        <span id="message-error"></span>
    </div>
    <div class="alert alert-success">
        <span id="message-success"></span>
    </div>
    <form action="{{url('/hotel/create/ride')}}" id="create_trip" method="GET" onkeypress="return disableEnterKey(event);">
        <div class="step">
            <div>
                <label class="col-3"></label>
                <div class="circle d-flex justify-content-center"></div>
            </div>
            <div>
                <div class="form-group">
                    <label>Pickup Location</label><span class="error-field s_address">required</span>
                    <input type="text" class="form-control" id="origin-input" name="s_address"  placeholder="Enter pickup location" >
                </div>
		<div class="input-group stop1" style="width:auto !important;padding-bottom: 15px;">
			<input type="text" class="form-control" name="stop1_address" id="stop1_address" placeholder="Stop1 Location">
                        <span class="input-group-addon" id="morefield1"><img style="width:27px" src="{{asset('asset/img/minusicon.png')}}"></span>
                </div>
                <div class="input-group stop2" style="width:auto !important;padding-bottom: 15px;padding-top: 0px;">
			<input type="text" class="form-control" onfocus="initrip()" name="stop2_address" id="stop2_address"
                           placeholder="Stop2 Location">
                     	<span class="input-group-addon" id="morefield2"><img style="width:27px" src="{{asset('asset/img/minusicon.png')}}"></span>
                </div>
            </div>
        </div>
        <div class="step step-active">
            <div>
                <label class="col-3"></label>
                <div class="circle d-flex justify-content-center"></div>
            </div>
            <div>                                   
                <div class="form-group">
                    <label>Drop Location</label><span class="error-field d_address">required</span>
		    <div class="input-group">
                    	<input type="text" class="form-control" id="destination-input" name="d_address"  placeholder="Enter drop location">
		    	<span class="input-group-addon" id="morefield"><img style="width:27px" src="{{asset('asset/img/plusicon.png')}}"></span>
		    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center align-items-center h-100">
            <div class="form-group col-md-6">
                <label>Guest Name</label>
                <input type="text" class="form-control" id="guest" name="guest"  placeholder="Enter guest name">
            </div>
            <div class="form-group col-md-6 mx-auto">
                <div class="one-quarter" id="switch">
                        <label>Select Ride</label>
                        <input type="checkbox" class="checkbox" id="chk" />
                        <label class="label" for="chk">
                            <i class=""><img style="width:15px;margin-top:-10px;" id="pic1" src="{{asset('asset/img/caricon-trip.png')}}"></i>
                            <i class=""><img style="width:15px;margin-top:-7px;margin-left: 7px;" id="pic2" src="{{asset('asset/img/sheduleicon-trip.png')}}"></i>
                            <div class="ball"></div>
                        </label>
                </div>
            </div>
        </div>
        <div class="form-group" id="schedule_time_block">
            <label>Date & Time</label>
            <input type="text" class="form-control" id="schedule_time" name="schedule_time" placeholder="Schedule Time">
        </div>
        <div class="form-group">
                <label>Add Note to Driver</label>
                <input type="text" class="form-control" id="message" name="message"  placeholder="You can add your message to driver">
        </div>
        <input type="hidden" name="s_latitude" id="origin_latitude" value="{{Auth::guard('hotel')->user()->latitude}}">
        <input type="hidden" name="s_longitude" id="origin_longitude" value="{{Auth::guard('hotel')->user()->longitude}}">
        <input type="hidden" name="d_latitude" id="destination_latitude">
        <input type="hidden" name="d_longitude" id="destination_longitude">
        <input type="hidden" name="current_longitude" id="long">
        <input type="hidden" name="current_latitude" id="lat">
  	<input type="hidden" name="stop1_latitude" id="stop1_latitude">
          <input type="hidden" name="stop1_longitude" id="stop1_longitude">
  	<input type="hidden" name="stop2_latitude" id="stop2_latitude">
          <input type="hidden" name="stop2_longitude" id="stop2_longitude">
                        
        <div class="car-detail">
            @foreach($services as $service)
            <div class="car-radio">
                <input type="radio" 
                    name="service_type"
                    value="{{$service->id}}"
                    id="service_{{$service->id}}"
                    @if ($loop->first) checked="checked" @endif class="service_type">
                <label for="service_{{$service->id}}">
                    <div class="car-radio-inner">
                        <div class="img"><img src="{{image($service->image)}}" style="margin-bottom:0px;"></div>
			<div class="" style="margin-bottom:13px;"><span class="fa fa-user seats_available{{$loop->index}}" id="seats_available" aria-hidden="true" style="font-size: 14px;"> </span></div>
                        <div class="name"><span>{{$service->name}}</span></div>
                    <div>
                       <strong><span class="currency_symbol">$</span>
                       <span id="estimate_fare{{$loop->index}}" class="estimate_fare"></span></strong>
                    </div>
			<div><span class="drop_off"></span>  Dropoff</div>
                    </div>
                </label>
            </div>
            @endforeach
        </div>
        <!--<div class="form-group">
            <label>Your Message to Driver</label>
            <input type="text" class="form-control" id="message" name="message"  placeholder="You can add your message to driver">
        </div>
        <div class="extrabtn">
            <a href="#Extra" class="" data-toggle="collapse"><i class="fa fa-arrow-right"></i> Extra</a>
        </div>
        <div id="Extra" class="collapse">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="pet" name="pet" value="1">
                <label class="form-check-label" for="pet">Travelling with Pet</label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="wagon" name="wagon" value="1">
                <label class="form-check-label" for="wagon">Station Wagon</label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="booster" name="booster" value="1">
                <label class="form-check-label" for="booster">Booster Seat</label>
            </div>
        </div>-->
        <div class="text-center mx-auto" style="text-align: center;margin-top: 4%;">
            <button type="submit" id="submit" style="margin-right: 1%;" class="btn btn-sm mx-auto btn-success waves-effect waves-light submitter" onclick=>Book Now</button>
            <button type="button" id="cancel" style="margin-left: 1%;" class=" btn btn-sm mx-auto btn-warning waves-effect waves-light" onclick=>Cancel</button>
        </div>
    </form>
</div>
<div class="col-md-6">
    <div class="map-container">
        <div class="map-responsive">
            <div id="map" style="width: 100%; height: 450px;"></div>
        </div>
    </div>
</div> 
@endsection

@section('scripts')
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.full.min.js"></script>-->
<script type="text/javascript" src="{{ asset('asset/userpanel/js/map.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ Setting::get('map_key', 'AIzaSyC7urojphmUg5qlseNH99Rojwn9Y-Amc0w') }}&libraries=places&callback=initMap" async defer></script>
<!-- datetime picker -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.4/css/bootstrap-datetimepicker.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.4/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/zebra_pin@2.0.0/dist/zebra_pin.min.js"></script>
<script src="{{asset('main/ZebraDatetimePicker/zebra_datepicker.min.js')}}"></script>

<script type="text/javascript">
    window.vx = {!! json_encode([
        "minDate" => \Carbon\Carbon::today()->format('d-m-Y g:i A'),
        "maxDate" => \Carbon\Carbon::today()->addDays(6)->format('d-m-Y g:i A'),
    ]) !!}
    $('#schedule_time').Zebra_DatePicker({
        datepicker : false,
        ampm: true, // FOR AM/PM FORMAT
        format : 'd-m-Y g:i A',
	direction: [window.vx.minDate, window.vx.maxDate],
    });
   // $('.Zebra_DatePicker_Icon_Wrapper').removeAttr('style');

    $('#trip_types').on('change', function() {
        var val = this.value;
        if(val ==2){
            $('#schedule_time_block').show();
        }else{
            $('#schedule_time_block').hide();
        }
    });
</script> 

<script type="text/javascript">
    var current_latitude = parseFloat("{{ Auth::guard('hotel')->user()->latitude }}");
    var current_longitude = parseFloat("{{ Auth::guard('hotel')->user()->longitude }}");
    var zoom_level = 8;
</script>

<script type="text/javascript">
    if( navigator.geolocation ) {
       navigator.geolocation.getCurrentPosition( success, fail );
    } else {
        console.log('Sorry, your browser does not support geolocation services');
        initMap();
    }

    function success(position)
    {
        document.getElementById('long').value = position.coords.longitude;
        document.getElementById('lat').value = position.coords.latitude

        if(position.coords.longitude != "" && position.coords.latitude != ""){
            origin_longitude = position.coords.longitude;
            origin_latitude = position.coords.latitude;
        }
        initMap();
    }

    function fail()
    {
        // Could not obtain location
        console.log('unable to get your location');        
    }
</script> 

<script type="text/javascript">
    function disableEnterKey(e)
    {
        var key;
        if(window.e)
            key = window.e.keyCode; // IE
        else
            key = e.which; // Firefox

        if(key == 13)
            return e.preventDefault();
    }
</script>

<script type="text/javascript">
    $('.car-detail').slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    autoplay: false,
    swipeToSlide: true,
    infinite: false
})

.on("mousewheel", function (event) {
    event.preventDefault();
    if (event.deltaX > 0 || event.deltaY < 0) {
        $('.slick-next').click();
    } else if (event.deltaX < 0 || event.deltaY > 0) {
        $('.slick-prev').click();
    }
});

// 
$('.view-icon.list-btn').click( function(){
    $('.grid-view').hide();
    $('.list-view').fadeIn(300);
    $('.view-icon.list-btn').addClass('active');
    $('.view-icon.grid-btn').removeClass('active');
});

$('.view-icon.grid-btn').click( function(){
    $('.list-view').hide();
    $('.grid-view').fadeIn(300);
    $('.view-icon.list-btn').removeClass('active');
    $('.view-icon.grid-btn').addClass('active');

});
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $('.alert-danger').hide();
        $('.alert-success').hide();
        $("#create_trip").submit(function(event){
            event.preventDefault();
            event.stopPropagation();
            var require = 0;
            if($('#origin-input').val() == ''){
                $('.s_address').show();
                require = 1;
            }else{
                $('.s_address').hide();
            }
            if($('#destination-input').val() == ''){
                $('.d_address').show();
                require = 1;
            }else{
                $('.d_address').hide();
            }
            if(require == 1){
                return false;
            }

            $.ajax({
                url: "{{ url('/hotel/create/ride') }}",
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
                type: 'POST',
                data: $("#create_trip").serialize(),
                success: function(data) {
                    console.log(data);
                    if(data.success ==1){
                        $('input[type=text]').val('').removeAttr('selected');
                        $('#origin-input').val(parseFloat("{{Auth::guard('hotel')->user()->address}}"));
                        $('#origin_latitude').val(parseFloat("{{Auth::guard('hotel')->user()->latitude}}"));
                        $('#origin_longitude').val(parseFloat("{{Auth::guard('hotel')->user()->longitude}}"));
                        $('.alert-success').show().delay(5000).fadeOut();;
                        $("#message-success").html(data.message);
			$("#seats_available").text();
			setTimeout(function() {
				location.reload();
  			}, 500);
                    }else{
                        $('.alert-danger').show().delay(5000).fadeOut();;
                        $("#message-error").html(data.message);
                    }
                }
            });
        });
    });
</script>

<script type="text/javascript">
const chk = document.getElementById('chk');

var switchStatus = true;
$('#pic1').attr('src', '/asset/img/whitecar.png');
chk.addEventListener('change', () => {
    var chkPassport = document.getElementById("chk");

        if (chkPassport.checked) {
            $('#schedule_time_block').show();
		$('#pic1').attr('src', '/asset/img/caricon-trip.png');
		$('#pic2').attr('src', '/asset/img/calendaricon-white.png');
		
        } else {
            $('#schedule_time_block').hide();
		$('#pic1').attr('src', '/asset/img/whitecar.png');
		$('#pic2').attr('src', '/asset/img/sheduleicon-trip.png');

        }

    document.body.classList.toggle('dark');
});

// SOCIAL PANEL JS
const floating_btn = document.querySelector('.floating-btn');
const close_btn = document.querySelector('.close-btn');
const social_panel_container = document.querySelector('.social-panel-container');


floating_btn.addEventListener('click', () => {
    social_panel_container.classList.toggle('visible')
});

close_btn.addEventListener('click', () => {
    social_panel_container.classList.remove('visible')
});
</script>
<script>
$(document).ready(function () {
  $('.stop1').hide();
  $('.stop2').hide();
$("#morefield1").click(function(){
      $(".stop1").hide();
      $('.circle').removeClass("circle1");
      $('.circle').addClass("circle2");
      if($(".stop2").is(":visible") || $(".stop1").is(":visible")){
          //alert("The paragraph  is visible.");
      }else{
          $('.circle').removeClass("circle2");
      }
});
$("#morefield2").click(function(){
      $(".stop2").hide();
      $('.circle').removeClass("circle1");
      $('.circle').addClass("circle2");
      if($(".stop2").is(":visible") || $(".stop1").is(":visible")){
           //alert("The paragraph  is visible.");
      }else{
	 $('.circle').removeClass("circle2");
      }
});

  $("#morefield").click(function(){
          $(".stop1").show();
          $(".stop2").show();
	  $('.circle').addClass("circle1");
	  if($(".stop2").is(":visible") || $(".stop1").is(":visible")){
           $('.circle').addClass("circle1");
	   $('.circle').removeClass("circle2");
          }
});
});

/* Fare Calculation */

/*$('.service_type').on('change', function() {
        fare_calculation();
    });
*/    function fare_calculation(){
        var val = $('input[type="radio"][name="service_type"]:checked').val();
        //alert(val); 
        var s_latitude1 = $('#origin_latitude').val();
        var s_longitude1 = $('#origin_longitude').val();
        var d_latitude1 = $('#destination_latitude').val();
        var d_longitude1 = $('#destination_longitude').val();
	//alert("s_latitude1 "+s_latitude1+"s_longitude1"+s_longitude1+"d_latitude1"+d_latitude1+"d_longitude1"+d_longitude1);
        if(s_latitude1 !='' && s_latitude1 !='' && val !=''){
            $.ajax({
                url: '/hotel/fare',
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
                type: 'POST',
                data: {
                        s_latitude: s_latitude1,
                        s_longitude: s_longitude1,
                        d_latitude: d_latitude1,
                        d_longitude: d_longitude1,
                        service_type: val
                        },
                success: function(data) {
			$.each(data[2], function(item) {
            			$('#estimate_fare'+(item)).text(data[2][item]);
            		});
			$.each(data[8], function(item) {
            			$('.seats_available'+(item)).text(data[8][item]);
            		});
			//console.log(data);
                    	$('.currency_symbol').text(data[4]);
                        $('.drop_off').text(data[6]);
                }
            });
        }
    }
$('#destination-input').on('focusout',function(){
	setTimeout(function() {
		fare_calculation();
  	}, 700);
});
$('#origin-input').on('focusout',function(){
	setTimeout(function() {
		fare_calculation();
  	}, 700);
});

</script>
@endsection