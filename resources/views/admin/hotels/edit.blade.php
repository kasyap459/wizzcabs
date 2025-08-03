@extends('admin.layout.base')

@section('title', 'Update Hotel/Restaurant ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">Hotel/Restaurant</h4><a href="{{ route('admin.hotel.index') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">List Hotel/Restaurant</a>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">Update Hotel/Restaurant</li>
                </ol>
            </div>
        </div>

    	<div class="box box-block bg-white">
			<h5>Update Hotel</h5>

            <form class="form-horizontal" action="{{route('admin.hotel.update', $hotel->id )}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
            	<input type="hidden" name="_method" value="PATCH">
				
				<div class="form-group row">
					<label for="name" class="col-xs-12 col-form-label">@lang('admin.member.full_name')</label>
					<div class="col-xs-8">
						<input class="form-control" type="text" value="{{ $hotel->name }}" name="name" required id="name" placeholder="@lang('admin.member.full_name')">
					</div>
				</div>
		
				<div class="form-group row">
					<label for="email" class="col-xs-12 col-form-label">@lang('admin.member.email')</label>
					<div class="col-xs-8">
						<input class="form-control" type="email" required name="email" value="{{ $hotel->email }}" id="email" placeholder="@lang('admin.member.email')">
					</div>
				</div>
				<div class="form-group row">
					<label for="password" class="col-xs-12 col-form-label">@lang('admin.member.password')</label>
					<div class="col-xs-8">
						<input class="form-control" type="password" name="password" id="password" placeholder="@lang('admin.member.password')">
					</div>
				</div>

				<div class="form-group row">
					<label for="password_confirmation" class="col-xs-12 col-form-label">@lang('admin.member.password_confirmation')</label>
					<div class="col-xs-8">
						<input class="form-control" type="password" name="password_confirmation" id="password_confirmation" placeholder="@lang('admin.member.re_type')">
					</div>
				</div>
				<div class="form-group row">
					<label for="picture" class="col-xs-12 col-form-label">@lang('admin.member.picture')</label>
					<div class="col-xs-8">
					@if(isset($hotel->picture))
                    	<img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{img($hotel->picture)}}">
                    @endif
						<input type="file" accept="image/*" name="picture" class="dropify form-control-file" id="picture" aria-describedby="fileHelp">
					</div>
				</div>
				<div class="form-group row">
					<label for="country_id" class="col-xs-12 col-form-label">Country</label>
					<div class="col-xs-8">
						<select name="country_id" id="country_id" class="form-control">
							<option value="">Select Country</option>
							@foreach($countries as $country)
								<option value="{{ $country->countryid }}" @if($country->countryid == $hotel->country_id) selected @endif>{{ $country->name }}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="form-group row">
                    <label for="address" class="col-xs-12 col-form-label">Address</label>
                    <div class="col-xs-8">
                        <input class="form-control" type="text" value="{{ $hotel->address }}" onclick="initMap()" name="address" required id="address" placeholder="Address">
                        <input type="hidden" name="latitude" id="latitude" value="{{ $hotel->latitude }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ $hotel->longitude }}">
                    </div>
                </div>
				<div class="form-group row">
					<label for="mobile" class="col-xs-12 col-form-label">@lang('admin.member.mobile')</label>
					<div class="col-xs-8">
						<input class="form-control" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="{{ $hotel->mobile }}" name="mobile" required id="mobile" placeholder="@lang('admin.member.mobile')">
					</div>
				</div>

				<div class="form-group row">
					<label for="zipcode" class="col-xs-12 col-form-label"></label>
					<div class="col-xs-8">
						<button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Update Hotel/Restaurant</button>
						<a href="{{route('admin.hotel.index')}}" class="btn btn-inverse waves-effect waves-light">@lang('admin.member.cancel')</a>

					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection


@section('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ Setting::get('map_key', 'AIzaSyC7urojphmUg5qlseNH99Rojwn9Y-Amc0w') }}&libraries=places" async defer></script>
<script type="text/javascript">
  function initMap() {

    var originInput = document.getElementById('address');
    var originLatitude = document.getElementById('latitude');
    var originLongitude = document.getElementById('longitude');
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
</script>
@endsection