@extends('admin.layout.base')

@section('title', 'Refferal Settings ')

@section('styles')
<style type="text/css">
	.display {
		display: none;
	}
	button, input, select[multiple], textarea {
    background-image: ;
    border: 1px solid #e4e7ea;
    
}
.test{
	width: 80px;
}
</style>
@endsection

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">

    	<div class="row bg-title">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h4 class="page-title">Referral Settings</h4>
            </div>
           <!--  <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
               <ol class="breadcrumb">
                   <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                   <li class="active">@lang('admin.member.site_settings')</li>
               </ol>
           </div> -->
        </div>

    	<div class="box box-block bg-white">
			<!-- <h5>@lang('admin.member.site_settings')</h5> -->

            <form class="form-horizontal" action="{{ route('admin.settings.store') }}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
            	<div style="display: none;">
				<div class="form-group row">
					<label for="site_title" class="col-xs-2 col-form-label">@lang('admin.member.site_name')</label>
					<div class="col-xs-8">
						<input class="form-control" type="text" value="{{ Setting::get('site_title', 'vx')  }}" name="site_title" required id="site_title" placeholder="Site Name">
					</div>
				</div>

				<div class="form-group row">
					<label for="site_logo" class="col-xs-2 col-form-label">@lang('admin.member.site_logo')</label>
					<div class="col-xs-8">
						@if(Setting::get('site_logo')!='')
	                    <img style="height: 90px; margin-bottom: 15px;" src="{{ Setting::get('site_logo', asset('logo-black.png')) }}">
	                    @endif
						<input type="file" accept="image/*" name="site_logo" class="dropify form-control-file" id="site_logo" aria-describedby="fileHelp">
					</div>
				</div>


				<div class="form-group row">
					<label for="site_icon" class="col-xs-2 col-form-label">@lang('admin.member.site_icon')</label>
					<div class="col-xs-8">
						@if(Setting::get('site_icon')!='')
	                    <img style="height: 90px; margin-bottom: 15px;" src="{{ Setting::get('site_icon') }}">
	                    @endif
						<input type="file" accept="image/*" name="site_icon" class="dropify form-control-file" id="site_icon" aria-describedby="fileHelp">
					</div>
				</div>

                <div class="form-group row">
                    <label for="tax_percentage" class="col-xs-2 col-form-label">@lang('admin.member.copyright_content')</label>
                    <div class="col-xs-8">
                        <input class="form-control" type="text" value="{{ Setting::get('site_copyright', '&copy; '.date('Y').' Appoets') }}" name="site_copyright" id="site_copyright" placeholder="Site Copyright">
                    </div>
                </div>

				<div class="form-group row">
					<label for="store_link_android" class="col-xs-2 col-form-label">@lang('admin.member.playstore_link')</label>
					<div class="col-xs-8">
						<input class="form-control" type="text" value="{{ Setting::get('store_link_android', '')  }}" name="store_link_android"  id="store_link_android" placeholder="Playstore link">
					</div>
				</div>

				<div class="form-group row">
					<label for="store_link_ios" class="col-xs-2 col-form-label">@lang('admin.member.appstore_link')</label>
					<div class="col-xs-8">
						<input class="form-control" type="text" value="{{ Setting::get('store_link_ios', '')  }}" name="store_link_ios"  id="store_link_ios" placeholder="Appstore link">
					</div>
				</div>
				<div class="form-group row">
					<label for="mail_enable" class="col-xs-2 col-form-label">@lang('admin.member.mail_enable')</label>
					<div class="col-xs-8">
						<select class="form-control" id="mail_enable" name="mail_enable">
							<option value="1" @if(Setting::get('mail_enable', 0) == 1) selected @endif>Enable</option>
							<option value="0" @if(Setting::get('mail_enable', 0) == 0) selected @endif>Disable</option>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label for="sms_enable" class="col-xs-2 col-form-label">@lang('admin.member.sms_enable')</label>
					<div class="col-xs-8">
						<select class="form-control" id="sms_enable" name="sms_enable">
							<option value="1" @if(Setting::get('sms_enable', 0) == 1) selected @endif>Enable</option>
							<option value="0" @if(Setting::get('sms_enable', 0) == 0) selected @endif>Disable</option>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label for="country_code" class="col-xs-2 col-form-label">@lang('admin.member.country_code')</label>
					<div class="col-xs-8">
						<input class="form-control" type="text" value="{{ Setting::get('country_code', '')  }}" name="country_code"  id="country_code" placeholder="+123">
					</div>
				</div>
				<div class="form-group row">
					<label for="provider_select_timeout" class="col-xs-2 col-form-label">@lang('admin.member.provider_accept_timeout')</label>
					<div class="col-xs-8">
						<input class="form-control" type="number" value="{{ Setting::get('provider_select_timeout', '90')  }}" name="provider_select_timeout" required id="provider_select_timeout" placeholder="Provider Timout">
					</div>
				</div>

				<div class="form-group row">
					<label for="provider_search_radius" class="col-xs-2 col-form-label">@lang('admin.member.provider_search_radius')</label>
					<div class="col-xs-8">
						<input class="form-control" type="number" value="{{ Setting::get('provider_search_radius', '200')  }}" name="provider_search_radius" required id="provider_search_radius" placeholder="Provider Search Radius">
					</div>
				</div>
				<div class="form-group row">
					<label for="sms_enable" class="col-xs-2 col-form-label">@lang('admin.member.distance_unit')</label>
					<div class="col-xs-8">
						<select class="form-control" id="distance_unit" name="distance_unit">
							<option value="km" @if(Setting::get('distance_unit', 'km') == 'km') selected @endif>Kilometers</option>
							<option value="mile" @if(Setting::get('distance_unit', 'mile') == 'mile') selected @endif>Miles</option>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label for="sos_number" class="col-xs-2 col-form-label">@lang('admin.member.sos_number')</label>
					<div class="col-xs-8">
						<input class="form-control" type="number" value="{{ Setting::get('sos_number', '911')  }}" name="sos_number" required id="sos_number" placeholder="SOS Number">
					</div>
				</div>

				<div class="form-group row">
					<label for="contact_number" class="col-xs-2 col-form-label">@lang('admin.member.contact_number')</label>
					<div class="col-xs-8">
						<input class="form-control" type="number" value="{{ Setting::get('contact_number', '911')  }}" name="contact_number" required id="contact_number" placeholder="Contact Number">
					</div>
				</div>

				<div class="form-group row">
					<label for="contact_email" class="col-xs-2 col-form-label">@lang('admin.member.contact_email')</label>
					<div class="col-xs-8">
						<input class="form-control" type="email" value="{{ Setting::get('contact_email', '')  }}" name="contact_email" required id="contact_email" placeholder="Contact Email">
					</div>
				</div>

				<div class="form-group row">
					<label for="social_login" class="col-xs-2 col-form-label">@lang('admin.member.social_login')</label>
					<div class="col-xs-8">
						<select class="form-control" id="social_login" name="social_login">
							<option value="1" @if(Setting::get('social_login', 0) == 1) selected @endif>Enable</option>
							<option value="0" @if(Setting::get('social_login', 0) == 0) selected @endif>Disable</option>
						</select>
					</div>
				</div>
				</div>
				<div class="form-group row">
					<label for="refferal" class="col-xs-2 col-form-label">Refferal</label>
					<div class="col-xs-3">
						<select class="form-control" id="refferal" name="refferal">
							<option value="1" @if(Setting::get('refferal', 0) == 1) selected @endif>Enable</option>
							<option value="0" @if(Setting::get('refferal', 0) == 0) selected @endif>Disable</option>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label for="refferal_type" class="col-xs-2 col-form-label">Refferal Type</label>
					<div class="col-xs-8">
						 <input type="radio" id="refferal_type" name="refferal_type" value="first ride"  @if(Setting::get('refferal_type', '') == 'first ride') checked @endif> 
  						<label for="male"> First Ride Free</label> 
  						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="refferal_type" name="refferal_type" value="percentage"  @if(Setting::get('refferal_type', '') == 'percentage') checked @endif>
  						<label for="female"> Percentage</label>     <input class="test" type="text" value="{{ Setting::get('refferal_value', '0') }}" name="refferal_value" id="refferal_value"> %<br>
					</div>
				</div>
				<div style="display: none;">
				<div class="form-group row">
                    <label for="tax_percentage" class="col-xs-2 col-form-label">@lang('admin.member.country')</label>
                    <div class="col-xs-8">
                        <input class="form-control" type="text" value="{{ Setting::get('country', '') }}" name="country" id="country" placeholder="Site Country">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="tax_percentage" class="col-xs-2 col-form-label">@lang('admin.member.state')</label>
                    <div class="col-xs-8">
                        <input class="form-control" type="text" value="{{ Setting::get('state', '') }}" name="state" id="state" placeholder="Site State">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="tax_percentage" class="col-xs-2 col-form-label">@lang('admin.member.city')</label>
                    <div class="col-xs-8">
                        <input class="form-control" type="text" value="{{ Setting::get('city', '') }}" name="city" id="city" placeholder="Site City">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="tax_percentage" class="col-xs-2 col-form-label">Address</label>
                    <div class="col-xs-8">
                        <input class="form-control" type="text" onfocus="initMap()" value="{{ Setting::get('address', '') }}" name="address" id="address" placeholder="Address">
                        <input type="hidden" name="address_lat" id="a_lat" value="{{ Setting::get('address_lat', '') }}">
                        <input type="hidden" name="address_long" id="a_long" value="{{ Setting::get('address_long', '') }}">
                    </div>
                </div>
                <div class="form-group row">
					<label for="zoom" class="col-xs-2 col-form-label">Map Zoom Level</label>
					<div class="col-xs-8">
						<select class="form-control" id="zoom" name="zoom">
							<option value="1" @if(Setting::get('zoom', 0) == 1) selected @endif>1</option>
							<option value="2" @if(Setting::get('zoom', 0) == 2) selected @endif>2</option>
							<option value="3" @if(Setting::get('zoom', 0) == 3) selected @endif>3</option>
							<option value="4" @if(Setting::get('zoom', 0) == 4) selected @endif>4</option>
							<option value="5" @if(Setting::get('zoom', 0) == 5) selected @endif>5</option>
							<option value="6" @if(Setting::get('zoom', 0) == 6) selected @endif>6</option>
							<option value="7" @if(Setting::get('zoom', 0) == 7) selected @endif>7</option>
							<option value="8" @if(Setting::get('zoom', 0) == 8) selected @endif>8</option>
							<option value="9" @if(Setting::get('zoom', 0) == 9) selected @endif>9</option>
							<option value="10" @if(Setting::get('zoom', 0) == 10) selected @endif>10</option>
							<option value="11" @if(Setting::get('zoom', 0) == 11) selected @endif>11</option>
							<option value="12" @if(Setting::get('zoom', 0) == 12) selected @endif>12</option>
						</select>
					</div>
				</div>
				@if(Auth::guard('admin')->user()->admin_type ==0)
				<div class="form-group row">
					<label for="timezoner" class="col-xs-2 col-form-label">Time Zone</label>
					<div class="col-xs-8">
						<select class="form-control" id="timezoner" name="timezoner">
							<option value="{{ env('APP_TIMEZONE') }}">{{ env('APP_TIMEZONE') }}</option>
							@foreach($tzlist as $timezone)
							<option value="{{ $timezone }}">{{ $timezone }}</option>
							@endforeach
						</select>
					</div>
				</div>
				@endif
				</div>
				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-8">
						<button type="" class="btn btn-success"> <i class="fa fa-check"></i> @lang('admin.member.update_site_settings')</button>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&libraries=places" async defer></script>
<script type="text/javascript">
  function initMap() {

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
</script>
@endsection