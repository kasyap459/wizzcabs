@extends('web_sp.layouts.app')

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
    .book-ride-one:after {
        background: none !important;
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
<section class="inner-banner">
            <div class="container">
                <ul class="thm-breadcrumb">
                    <li><a href="{{url('/es')}}">Hogar</a></li>
                    <li><span class="sep">.</span></li>
                    <li><span class="page-title">Reservar un viaje</span></li>
                </ul><!-- /.thm-breadcrumb -->
                <h2>Reservar un viaje</h2>
            </div><!-- /.container -->
        </section><!-- /.inner-banner -->
        <section class="about-style-three clearfix">
            <div class="left-block">
                <div class="content-block">
                    <div class="image-block">
                        <img src="{{asset('web/images/resources/history.png')}}" alt="Awesome Image"/>
                    </div><!-- /.image-block -->
                    <div class="block-title">
                     <!-- <div><img src="{{asset('web/images/black_logo.png')}}" alt="" width="10%"></div> -->
                        <p>Somos los mejores en tu ciudad.</p>
                        <h2>Bienvenido a la<br> empresa <br> más confiable</h2>
                    </div><!-- /.block-title -->
                    <p>Llegue a su destino a tiempo con el transporte rápido de Pronto Taxi, transportamos clientes a cualquier ubicación, local o de larga distancia.</p>
                    <hr class="style-one" />
                    <div class="tag-line">
                        <span>Seguro .</span>
                        <span>A tiempo .</span>
                        <span>Rápido .</span>
                    </div><!-- /.tag-line -->
                </div><!-- /.content-block -->
            </div><!-- /.left-block -->
            <div class="right-block">
                <div class="right-upper-block">
                    <div class="content-block">
                        <div class="block-title">
                            <!-- <div><img src="{{asset('web/images/white.png')}}" alt="" width="30%"></div> -->
                            <p class="light-2">¿Buscas taxi?</p>
                            <h2 class="light">Haz tu <br> reserva</h2>
                        </div><!-- /.block-title -->
                        <form action="{{ url('/booktaxi') }}" method="post"  class="booking-form-one">
                        {{csrf_field()}}
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="input-holder">
                                        <input type="text" name="name" id="name" placeholder="Su nombre" required>
                                    </div><!-- /.input-holder -->
                                </div><!-- /.col-lg-6 -->
                                <div class="col-lg-12">
                                    <div class="input-holder">
                                        <input type="email" name="email" id="email" placeholder="Dirección de correo electrónico">
                                    </div><!-- /.input-holder -->
                                </div><!-- /.col-lg-6 -->
                                <!-- <div class="col-lg-12">
                                    <div class="input-holder">
                                        <input type="text" name="passanger" id="passanger" placeholder="">
                                    </div>
                                </div> -->
                                <div class="col-lg-12">
                                    <div class="input-holder">
                                        <input type="text" name="s_address" id="source"  placeholder="(De)" onfocus="initMap1()" required="required" >
                                        <input type="hidden" name="s_latitude" id="s_latitude">
                                        <input type="hidden" name="s_longitude" id="s_longitude">
                                    </div><!-- /.input-holder -->
                                </div><!-- /.col-lg-12 -->
                                <div class="col-lg-12">
                                    <div class="input-holder" style="color: black;">
                                        <input type="text" name="d_address" id="destination" class="form-control" placeholder="(A)" onfocus="initMap2()" required="required">
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
                        <option value="">Por favor seleccione</option>
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
                                        <option value="Ride Now">Montar ahora</option>
                                        <option value="Shedule Ride">Horario de viaje</option>
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
                                        <button type="submit">Reservar ahora</button>
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
                            <p>Llama y reserva tu taxi</p>
                            <a href="callto:1234567890">1234567890</a>
                        </div><!-- /.text-block -->
                    </div><!-- /.content-block -->
                </div><!-- /.right-bottom-block -->
            </div><!-- /.right-block -->
        </section><!-- /.about-style-three -->
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
@endsection
