@extends('admin.layout.base')

@section('title', 'Add Demo ')
@section('styles')
  <link rel="stylesheet" href="{{asset('main/vendor/select2/dist/css/select2.min.css')}}">
@endsection

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">

    	<div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">Demos</h4><a href="{{ route('admin.demo.index') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">List Demo</a>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">Add Demo</li>
                </ol>
            </div>
        </div>

    	<div class="box box-block bg-white">
    		              <div class="card ">

                <div class="card-header card-header-rose card-header-text">
                  <div class="card-text">
                    <h4 class="card-title">Add Demo</h4>
                  </div>
                </div>
            <form class="form-horizontal" action="{{route('admin.demo.store')}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
				<div class="form-group row">
					<label for="name" class="col-xs-12 col-form-label">@lang('admin.member.full_name')</label>
					<div class="col-xs-8">
						<input class="form-control" type="text" required value="{{ old('name') }}" name="name" id="name" placeholder="@lang('admin.member.full_name')">
					</div>
				</div>

				<div class="form-group row">
					<label for="email" class="col-xs-12 col-form-label">@lang('admin.member.email')</label>
					<div class="col-xs-8">
						<input class="form-control" type="email" required name="email" value="{{old('email')}}" id="email" placeholder="@lang('admin.member.email')">
					</div>
				</div>

				<div class="form-group row">
					<label for="password" class="col-xs-12 col-form-label">@lang('admin.member.password')</label>
					<div class="col-xs-8">
						<input class="form-control" type="password" required name="password" id="password" value="{{old('password')}}" placeholder="@lang('admin.member.password')">
					</div>
				</div>
				<div class="form-group row">
					<label for="country_id" class="col-xs-12 col-form-label">Country</label>
					<div class="col-xs-8">
						<select name="country_id" id="country_id" class="form-control" data-plugin="select2">
							<option value="">Select Country</option>
							@foreach($countries as $country)
								<option value="{{ $country->countryid }}" @if (old('country_id') == $country->countryid)  
        selected  @endif>{{ $country->name }}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label for="phone" class="col-xs-12 col-form-label">@lang('admin.member.mobile')</label>
					<div class="col-xs-8">
						<input class="form-control" type="number" required value="{{ old('phone') }}" name="phone" id="phone" placeholder="@lang('admin.member.mobile')">
					</div>
				</div>
				<div class="form-group row">
					<label for="seller_email" class="col-xs-12 col-form-label">Seller Email</label>
					<div class="col-xs-8">
						<input class="form-control" type="email" name="seller_email" value="{{old('seller_email')}}" id="seller_email" placeholder="Seller Email">
					</div>
				</div>
				<div class="form-group row">
                    <label for="address" class="col-xs-12 col-form-label">Address</label>
                    <div class="col-xs-8">
                        <input class="form-control" type="text" required name="address" id="address" autocomplete="off" placeholder="Address" value="{{old('address')}}">
                        <input type="hidden" name="a_lat" id="a_lat">
                        <input type="hidden" name="a_long" id="a_long">
                    </div>
                </div>
                <div class="form-group row">
					<label for="zoom" class="col-xs-12 col-form-label">Map Zoom Level</label>
					<div class="col-xs-8">
						<select class="form-control" id="zoom" name="zoom">
<!-- 							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
 -->							<option value="8">10</option>
<!-- 							<option value="11">11</option>
							<option value="12">12</option>
 -->						</select>
					</div>
				</div> 
				<div class="form-group row">
					<label for="timezoner" class="col-xs-12 col-form-label">Time Zone</label>
					<div class="col-xs-8">
						<select class="form-control" id="timezoner" name="timezoner" data-plugin="select2">
							<option value="{{ env('APP_TIMEZONE') }}">{{ env('APP_TIMEZONE') }}</option>
							@foreach($tzlist as $timezone)
							<option value="{{ $timezone }}" @if (old('timezoner') == $timezone)  selected  @endif>{{ $timezone }}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label for="zipcode" class="col-xs-12 col-form-label"></label>
					<div class="col-xs-8">
						<button type="submit" class="btn btn-success"> <i class="fa fa-check"></i>Create demo</button>
						<a href="{{route('admin.demo.index')}}" class="btn btn-inverse waves-effect waves-light">@lang('admin.member.cancel')</a>
					</div>
				</div>
			</form>
		</div>
		</div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ Setting::get('map_key', 'AIzaSyA30-YQUNKSLkw69WCOzJBMHDDwH_X_QXY') }}&libraries=places" async defer></script>
<script type="text/javascript" src="{{asset('main/vendor/select2/dist/js/select2.min.js')}}"></script>

<script type="text/javascript">

var input = document.querySelector('#address');

// var messages = document.querySelector('#messages');

input.addEventListener('input', function()
{
  if(input.value.length>4){
    var originInput = document.getElementById('address');
    var originLatitude = document.getElementById('a_lat');
    var originLongitude = document.getElementById('a_long');
    var originAutocomplete = new google.maps.places.Autocomplete(
            originInput);

    originAutocomplete.addListener('place_changed', function(event) {
        var place = originAutocomplete.getPlace();
        if (place.hasOwnProperty('place_id')) {
            if (!place.geometry) {
                    // window.alert("Autocomplete's returned place contains no geometry");
                    return;
            }
            originLatitude.value = place.geometry.location.lat();
            originLongitude.value = place.geometry.location.lng();
        }
    });
  }

});
       $('[data-plugin="select2"]').select2($(this).attr('data-options'));   

// document.getElementById('txtbox1').addEventListener('input',function(e){
//  console.log('typing');
// },false);

 // $("#address").keyup(function() {
 // if($("#address").val().length >4) {
   // }
   // else{
   //  alert('3');
//    }
//  // });

// }
</script>
@endsection
