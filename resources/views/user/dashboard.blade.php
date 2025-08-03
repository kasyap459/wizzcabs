@extends('user.layout.base')

@section('title', 'Dashboard ')

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/jquery.datetimepicker.min.css" />
<link rel="stylesheet" href="{{asset('main/vendor/toastr/toastr.min.css')}}">

<style type="text/css">
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
.input-group .form-control {
    border-radius: 4px 0px 0px 4px !important;
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
    <div class="row no-margin">
        <div class="col-md-12">
            <h4 class="page-title">Create New Trip</h4>
        </div>
    </div>
    <hr>
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <span id="message-error"></span>
    </div>
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <span id="message-success"></span>
    </div>
    <form action="{{url('create/ride')}}" id="create_trip" method="GET" onkeypress="return disableEnterKey(event);">
        <div class="form-group">
            <label>Pickup Location</label><span class="error-field s_address">required</span>
            <input type="text" class="form-control" id="origin-input" name="s_address"  placeholder="Enter pickup location">
        </div>
        <div class="form-group stop1">
            <label>Stop1 Location</label><span class="error-field d_address">required</span>
	    <div class="input-group">
            	<input type="text" class="form-control" name="stop1_address" id="stop1_address" placeholder="Enter stop1 location">
	    	<span class="input-group-addon" id="morefield1"><img style="width:27px" src="{{asset('asset/img/minusicon.png')}}"></span>
            </div>
	</div>
	<div class="form-group stop2">
	    <label>Stop2 Location</label><span class="error-field d_address">required</span>
	    <div class="input-group">
            	<input type="text" class="form-control" name="stop2_address" id="stop2_address" placeholder="Enter stop2 location">
	    	<span class="input-group-addon" id="morefield2"><img style="width:27px" src="{{asset('asset/img/minusicon.png')}}"></span>
            </div>
	</div>
	<div class="form-group">
            <label>Drop Location</label><span class="error-field d_address">required</span>
            <div class="input-group">
	   	<input type="text" class="form-control" id="destination-input" name="d_address"  placeholder="Enter drop location">
	    	<span class="input-group-addon" id="morefield"><img style="width:27px" src="{{asset('asset/img/plusicon.png')}}"></span>
            </div>
	</div>
        <div class="form-group">
            <label>When</label>
            <select class="form-control" id="trip_types" name="trip_types">
                <option value="1">Ride Now</option>
                <option value="2">Scheduled Ride</option>
            </select>
        </div>
        <div class="form-group" id="schedule_time_block">
            <label>Date & Time</label>
            <input type="text" class="form-control" id="schedule_time" name="schedule_time" placeholder="Schedule Time">
        </div>
        
        <input type="hidden" name="s_latitude" id="origin_latitude">
        <input type="hidden" name="s_longitude" id="origin_longitude">
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
                       <strong><span class="currency_symbol">{{ Setting::get('currency','$') }}</span>
                       <span id="estimate_fare{{$loop->index}}" class="estimate_fare"></span></strong>
                    </div>
			<div><span class="drop_off"></span> Dropoff</div>
                    </div>
                </label>
            </div>
            @endforeach
        </div>
        <div class="form-group">
            <label>Your Message to Driver</label>
            <input type="text" class="form-control" id="message" name="message"  placeholder="You can add your message to driver">
        </div>
        <div class="extrabtn" style="display:none;">
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
                <input type="checkbox" class="form-check-input" id="handicap" name="handicap" value="1">
                <label class="form-check-label" for="handicap">Handicap Access</label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="booster" name="booster" value="1">
                <label class="form-check-label" for="booster">Booster Seat</label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="fixed_rate" name="fixed_rate" value="1">
                <label class="form-check-label" for="fixed_rate">Enable Fixed Rate</label>
            </div>
        </div>
        <button class="btn btn-danger btn-block" type="submit">@lang('user.ride.ride_now')<i class="fa fa-long-arrow-right" aria-hidden="true"></i></button>

    </form>
</div>
<div class="col-md-6">
    <div class="map-container">
        <div class="map-responsive">
            <div id="map" style="width: 100%; height: 500px;"></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.full.min.js"></script> -->
<script type="text/javascript" src="{{ asset('asset/userpanel/js/map.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ Setting::get('map_key', 'AIzaSyC7urojphmUg5qlseNH99Rojwn9Y-Amc0w') }}&libraries=places&callback=initMap" async defer></script>
<!-- datetime picker -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.4/css/bootstrap-datetimepicker.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.4/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="{{asset('main/vendor/toastr/toastr.min.js')}}"></script>

<script type="text/javascript">
    /*window.vx = {!! json_encode([
        "minDate" => \Carbon\Carbon::today()->format('Y-m-d\TH:i'),
        "maxDate" => \Carbon\Carbon::today()->addDays(30)->format('Y-m-d\TH:i'),
    ]) !!}
    $('#schedule_time').datetimepicker({
            minDate: window.vx.minDate,
            maxDate: window.vx.maxDate,
            format:'d-m-Y H:i:s',
    });*/
    window.vx = {!! json_encode([
        "minDate" => \Carbon\Carbon::today()->format('d-m-Y\TH:i'),
        "maxDate" => \Carbon\Carbon::today()->addDays(30)->format('d-m-Y\TH:i'),
    ]) !!}
    $("#schedule_time").datetimepicker({
	minDate: window.vx.minDate,
        maxDate: window.vx.maxDate,
        format: 'DD-MM-YYYY HH:mm',
    });


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
    var current_latitude = {{ $countrylatlng['lat'] }};
    var current_longitude = {{ $countrylatlng['lng'] }};
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
    slidesToShow: 4,
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
                url: "{{ url('create/ride') }}",
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
                type: 'POST',
                data: $("#create_trip").serialize(),
                success: function(data) {
                    console.log(data);
                    if(data.success ==1){
                        $('input[type=text]').val('').removeAttr('selected');
                        /*$('.alert-success').show();
                        $("#message-success").html(data.message);*/
			toastr.options = {
     				positionClass: 'toast-top-right',
     				timeOut: 10000
			};			
			toastr.success(data.message);
			setTimeout(function() {
				location.reload();
  			}, 400);

                    }else{
			toastr.options = {
     				positionClass: 'toast-top-right',
     				timeOut: 10000
			};
			toastr.error(data.message);
			
                       /* $('.alert-danger').show();
                        $("#message-error").html(data.message);*/
                    }
                }
            });
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
                url: '/fare',
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

			console.log(data);
                    // $('#fdrop_off').html('<b>Estimated Fare</b>');
                          $('.currency_symbol').text(data[4]);
                          //$('.estimate_fare1').text(data[2]);
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
</script>
@endsection