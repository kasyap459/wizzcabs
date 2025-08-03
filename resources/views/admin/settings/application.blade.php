@extends('admin.layout.base')

@section('title', 'Site Settings ')

@section('styles')
<style type="text/css">
	.display {
		display: none;
	}
	.input-group {
    		display: table !important;
    	}
</style>
@endsection

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">

    	<div class="row bg-title">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h4 class="page-title">@lang('admin.settings')</h4>
                <a href="{{ route('admin.settings') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light active">@lang('admin.member.site_settings')</a>
                <a href="{{ route('admin.settings.payment') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">@lang('admin.payment_settings')</a>
<!--                 <a href="#" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">@lang('admin.account_settings')</a>
                <a href="#" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">@lang('admin.change_password')</a>
 -->            </div>
           <!--  <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
               <ol class="breadcrumb">
                   <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                   <li class="active">@lang('admin.member.site_settings')</li>
               </ol>
           </div> -->
        </div>

    	<div class="box box-block bg-white">
			<h5>@lang('admin.member.site_settings')</h5>

            <form class="form-horizontal" action="{{ route('admin.settings.store') }}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}

				<div class="form-group row">
					<label for="site_title" class="col-xs-2 col-form-label">@lang('admin.member.site_name')</label>
					<div class="col-xs-8">
						<input class="form-control" type="text" value="{{ Setting::get('site_title', 'Unicotaxi')  }}" name="site_title" required id="site_title" placeholder="Site Name">
					</div>
				</div>

				<div class="form-group row">
					<label for="site_logo" class="col-xs-2 col-form-label">@lang('admin.member.site_logo')</label>
					<div class="col-xs-8">
						@if(Setting::get('site_logo')!='')
	                    <img style="height: 90px; margin-bottom: 15px;" src="{{ Setting::get('site_logo', asset('logo-black.png')) }}">
	                    @endif
						<input type="file" accept="image/*" name="site_logo" class="dropify form-control-file" id="site_logo" aria-describedby="fileHelp" >
					</div>
				</div>


				<div class="form-group row">
					<label for="site_icon" class="col-xs-2 col-form-label">@lang('admin.member.site_icon')</label>
					<div class="col-xs-8">
						@if(Setting::get('site_icon')!='')
	                    <img style="height: 90px; margin-bottom: 15px;" src="{{ Setting::get('site_icon') }}">
	                    @endif
						<input type="file" accept="image/*" name="site_icon" class="dropify form-control-file" id="site_icon" aria-describedby="fileHelp" >
					</div>
				</div>

                <div class="form-group row">
                    <label for="tax_percentage" class="col-xs-2 col-form-label">@lang('admin.member.copyright_content')</label>
                    <div class="col-xs-8">
                        <input class="form-control" type="text" value="{{ Setting::get('site_copyright', '&copy; '.date('Y').' Appoets') }}" name="site_copyright" id="site_copyright" placeholder="Site Copyright">
                    </div>
                </div>

				<div class="form-group row">
					<label for="store_link_android" class="col-xs-2 col-form-label">@lang('admin.member.playstore_link') User</label>
					<div class="col-xs-8">
						<input class="form-control" type="text" value="{{ Setting::get('store_link_android', '')  }}" name="store_link_android"  id="store_link_android" placeholder="Playstore link User">
					</div>
				</div>

				<div class="form-group row">
					<label for="store_link_ios" class="col-xs-2 col-form-label">@lang('admin.member.appstore_link') User</label>
					<div class="col-xs-8">
						<input class="form-control" type="text" value="{{ Setting::get('store_link_ios', '')  }}" name="store_link_ios"  id="store_link_ios" placeholder="Appstore link User">
					</div>
				</div>

				<div class="form-group row">
					<label for="store_link_android" class="col-xs-2 col-form-label">@lang('admin.member.playstore_link') Driver</label>
					<div class="col-xs-8">
						<input class="form-control" type="text" value="{{ Setting::get('store_link_android_driver', '')  }}" name="store_link_android_driver"  id="store_link_android_driver" placeholder="Playstore link Driver">
					</div>
				</div>

				<div class="form-group row">
					<label for="store_link_ios" class="col-xs-2 col-form-label">@lang('admin.member.appstore_link') Driver</label>
					<div class="col-xs-8">
						<input class="form-control" type="text" value="{{ Setting::get('store_link_ios_driver', '')  }}" name="store_link_ios_driver"  id="store_link_ios_driver" placeholder="Appstore link Driver">
					</div>
				</div>
<!-- 				<div class="form-group row">
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
					<label for="auto_assign" class="col-xs-2 col-form-label">Auto Assign Driver</label>
					<div class="col-xs-8">
						<select class="form-control" id="auto_assign" name="auto_assign">
							<option value="1" @if(Setting::get('auto_assign', 0) == 1) selected @endif>Enable</option>
							<option value="0" @if(Setting::get('auto_assign', 0) == 0) selected @endif>Disable</option>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label for="provider_select_timeout" class="col-xs-2 col-form-label">@lang('admin.member.provider_accept_timeout')</label>
					<div class="col-xs-8">
						<input class="form-control" type="number" value="{{ Setting::get('provider_select_timeout', '60')  }}" name="provider_select_timeout" required id="provider_select_timeout" placeholder="Provider Timout">
					</div>
				</div>
 -->
				<!-- <div class="form-group row">
					<label for="provider_search_radius" class="col-xs-2 col-form-label">@lang('admin.member.provider_search_radius')</label>
					<div class="col-xs-8">
						<input class="form-control" type="number" value="{{ Setting::get('provider_search_radius', '100')  }}" name="provider_search_radius" required id="provider_search_radius" placeholder="Provider Search Radius">
					</div>
				</div> -->
<!-- 				<div class="form-group row">
					<label for="distance_unit" class="col-xs-2 col-form-label">Distance unit</label>
					<div class="col-xs-8">
						<select class="form-control" id="distance_unit" name="distance_unit">
							<option value="km" @if(Setting::get('distance_unit') == 'km') selected @endif>Km</option>
							<option value="miles" @if(Setting::get('distance_unit') == 'miles') selected @endif>Miles</option>
						</select>
					</div>
				</div>	
 -->				
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

				<!-- <div class="form-group row">
					<label for="social_login" class="col-xs-2 col-form-label">@lang('admin.member.social_login')</label>
					<div class="col-xs-8">
						<select class="form-control" id="social_login" name="social_login">
							<option value="1" @if(Setting::get('social_login', 0) == 1) selected @endif>Enable</option>
							<option value="0" @if(Setting::get('social_login', 0) == 0) selected @endif>Disable</option>
						</select>
					</div>
				</div> -->
				<div style="display: none;">
				<div class="form-group row">
					<label for="country_code" class="col-xs-2 col-form-label">Available Tips</label>
					<div class="col-xs-2">
						<input class="form-control" type="text" value="{{ Setting::get('tip_1',1)  }}" name="tip_1"  id="tip_1" placeholder="Tips" disabled>
					</div>
					<div class="col-xs-2">
						<input class="form-control" type="text" value="{{ Setting::get('tip_2',2)  }}" name="tip_2"  id="tip_2" placeholder="Tips" disabled>
					</div>
					<div class="col-xs-2">
						<input class="form-control" type="text" value="{{ Setting::get('tip_3',3)  }}" name="tip_3"  id="tip_0" placeholder="Tips" disabled>
					</div>
					<div class="col-xs-2">
						<input class="form-control" type="text" value="{{ Setting::get('tip_4',4)  }}" name="tip_4"  id="tip_4" placeholder="Tips" disabled>
					</div>
                <div class="form-group row">
                    <label for="tax_percentage" class="col-xs-2 col-form-label">Stop title</label>
                    <div class="col-xs-8">
                        <input class="form-control" type="text" value="{{ Setting::get('stop_title','Please keep stops to 3 minutes or less')  }}" name="stop_title" id="stop_title" placeholder="stop title">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="tax_percentage" class="col-xs-2 col-form-label">Stop Description</label>
                    <div class="col-xs-8">
                        <input class="form-control" type="text" value="{{ Setting::get('stop_description','As a courtesy for your driver\'s time,Please limit each stop to 3 minutes or less,otherwise your fare may change')  }}" name="stop_description" id="stop_description" placeholder="stop description">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="tax_percentage" class="col-xs-2 col-form-label">Payment Description</label>
                    <div class="col-xs-8">
                        <input class="form-control" type="text" value="{{ Setting::get('payment_description','Total fare may change due in case of any route or destination changes of if your ride takes longer due to traffic or other factors')  }}" name="payment_description" id="payment_description" placeholder="payment description">
                    </div>
                </div>
                </div>		
                				</div>

                <div class="form-group row">
                    <label for="tax_percentage" class="col-xs-2 col-form-label">Web Map Key</label>
                    <div class="col-xs-8">
			<div class="input-group" id="show_hide_password1">
      				<input class="form-control" type="password" value="{{ Setting::get('map_key','AIzaSyBV1fZoyzTnxHOP5fbSiLvpe3oH7LZXc')  }}" name="map_key" id="map_key" placeholder="map_key" disabled>
      				<div class="input-group-addon">
        				<a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
      				</div>
    			</div>
                    </div>
                </div>
		<div class="form-group row">
                    <label for="tax_percentage" class="col-xs-2 col-form-label">Android User Map Key</label>
                    <div class="col-xs-8">
			<div class="input-group" id="show_hide_password2">
      				<input class="form-control" type="password" value="{{ Setting::get('android_user_map','AIzaSyBV1fZoyzTnxH_P5fbSiLvpe3oH7LZXc')  }}" name="android_user_map" id="android_user_map" placeholder="android_user_map" disabled>
      				<div class="input-group-addon">
        				<a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
      				</div>
    			</div>                        
		    </div>
                </div>
                <div class="form-group row">
                    <label for="tax_percentage" class="col-xs-2 col-form-label">Android Driver Map Key</label>
                    <div class="col-xs-8">
			<div class="input-group" id="show_hide_password3">
      				<input class="form-control" type="password" value="{{ Setting::get('android_driver_map','AIzaSyBV1fZoyzTnxH_P5fbSiLvpe3oH7LZXc')  }}" name="android_driver_map" id="android_driver_map" placeholder="android_driver_map" disabled>
      				<div class="input-group-addon">
        				<a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
      				</div>
    			</div>
                     </div>
                </div>
                <div class="form-group row">
                    <label for="tax_percentage" class="col-xs-2 col-form-label">Ios User Map Key</label>
                    <div class="col-xs-8">
			<div class="input-group" id="show_hide_password4">
      				<input class="form-control" type="password" value="{{ Setting::get('ios_user_map','AIzaSyBV1fZoyzTnxH_P5fbLvpe3oH7LZXc')  }}" name="ios_user_map" id="ios_user_map" placeholder="ios_user_map" disabled>      				
				<div class="input-group-addon">
        				<a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
      				</div>
    			</div>    
                    </div>
                </div>
               <input class="form-control" type="hidden" value="{{ Setting::get('address')  }}" name="address" id="address" >
                <input class="form-control" type="hidden" value="{{ Setting::get('address_lat')  }}" name="address_lat" id="address_lat" >
                 <input class="form-control" type="hidden" value="{{ Setting::get('address_long')  }}" name="address_long" id="address_long" >

               
                <div class="form-group row">
                    <label for="tax_percentage" class="col-xs-2 col-form-label">Ios Driver Map Key</label>
                    <div class="col-xs-8">
			<div class="input-group" id="show_hide_password5">
      				<input class="form-control" type="password" value="{{ Setting::get('ios_driver_map','AIzaSyBV1fyzTnxH_P5fbSiLvpe3oH7LZXc')  }}" name="ios_driver_map" id="ios_driver_map" placeholder="ios_driver_map" disabled>
				<div class="input-group-addon">
        				<a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
      				</div>
    			</div>
                    </div>
                </div>
<!--                 <div class="form-group row">
                    <label for="tax_percentage" class="col-xs-2 col-form-label">Address</label>
                    <div class="col-xs-8">
                        <input class="form-control" type="text" onfocus="initMap()" value="{{ Auth::guard('admin')->user()->admin_address }}" name="address" id="address" placeholder="Address">
                        <input type="hidden" name="address_lat" id="a_lat" value="{{ Auth::guard('admin')->user()->admin_lat }}">
                        <input type="hidden" name="address_long" id="a_long" value="{{ Auth::guard('admin')->user()->admin_long }}">
                    </div>
                </div>
                <div class="form-group row">
					<label for="zoom" class="col-xs-2 col-form-label">Map Zoom Level</label>
					<div class="col-xs-8">
						<select class="form-control" id="zoom" name="zoom">
							<option value="1" @if(Auth::guard('admin')->user()->admin_zoom == 1) selected @endif>1</option>
							<option value="2" @if(Auth::guard('admin')->user()->admin_zoom == 2) selected @endif>2</option>
							<option value="3" @if(Auth::guard('admin')->user()->admin_zoom == 3) selected @endif>3</option>
							<option value="4" @if(Auth::guard('admin')->user()->admin_zoom == 4) selected @endif>4</option>
							<option value="5" @if(Auth::guard('admin')->user()->admin_zoom == 5) selected @endif>5</option>
							<option value="6" @if(Auth::guard('admin')->user()->admin_zoom == 6) selected @endif>6</option>
							<option value="7" @if(Auth::guard('admin')->user()->admin_zoom == 7) selected @endif>7</option>
							<option value="8" @if(Auth::guard('admin')->user()->admin_zoom == 8) selected @endif>8</option>
							<option value="9" @if(Auth::guard('admin')->user()->admin_zoom == 9) selected @endif>9</option>
							<option value="10" @if(Auth::guard('admin')->user()->admin_zoom == 10) selected @endif>10</option>
							<option value="11" @if(Auth::guard('admin')->user()->admin_zoom == 11) selected @endif>11</option>
							<option value="12" @if(Auth::guard('admin')->user()->admin_zoom == 12) selected @endif>12</option>
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
				
 -->				
 				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-8">
						<button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('admin.member.update_site_settings')</button>
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
<script>
$(document).ready(function() {
    $("#show_hide_password1 a").on('click', function(event) {
        event.preventDefault();
        // if($('#show_hide_password1 input').attr("type") == "text"){
        //     $('#show_hide_password1 input').attr('type', 'password');
        //     $('#show_hide_password1 i').addClass( "fa-eye-slash" );
        //     $('#show_hide_password1 i').removeClass( "fa-eye" );
        // }else if($('#show_hide_password1 input').attr("type") == "password"){
        //     $('#show_hide_password1 input').attr('type', 'text');
        //     $('#show_hide_password1 i').removeClass( "fa-eye-slash" );
        //     $('#show_hide_password1 i').addClass( "fa-eye" );
        // }
    });
    $("#show_hide_password2 a").on('click', function(event) {
        event.preventDefault();
        // if($('#show_hide_password2 input').attr("type") == "text"){
        //     $('#show_hide_password2 input').attr('type', 'password');
        //     $('#show_hide_password2 i').addClass( "fa-eye-slash" );
        //     $('#show_hide_password2 i').removeClass( "fa-eye" );
        // }else if($('#show_hide_password2 input').attr("type") == "password"){
        //     $('#show_hide_password2 input').attr('type', 'text');
        //     $('#show_hide_password2 i').removeClass( "fa-eye-slash" );
        //     $('#show_hide_password2 i').addClass( "fa-eye" );
        // }
    });
    $("#show_hide_password3 a").on('click', function(event) {
        event.preventDefault();
        // if($('#show_hide_password3 input').attr("type") == "text"){
        //     $('#show_hide_password3 input').attr('type', 'password');
        //     $('#show_hide_password3 i').addClass( "fa-eye-slash" );
        //     $('#show_hide_password3 i').removeClass( "fa-eye" );
        // }else if($('#show_hide_password3 input').attr("type") == "password"){
        //     $('#show_hide_password3 input').attr('type', 'text');
        //     $('#show_hide_password3 i').removeClass( "fa-eye-slash" );
        //     $('#show_hide_password3 i').addClass( "fa-eye" );
        // }
    });
    $("#show_hide_password4 a").on('click', function(event) {
        event.preventDefault();
        // if($('#show_hide_password4 input').attr("type") == "text"){
        //     $('#show_hide_password4 input').attr('type', 'password');
        //     $('#show_hide_password4 i').addClass( "fa-eye-slash" );
        //     $('#show_hide_password4 i').removeClass( "fa-eye" );
        // }else if($('#show_hide_password4 input').attr("type") == "password"){
        //     $('#show_hide_password4 input').attr('type', 'text');
        //     $('#show_hide_password4 i').removeClass( "fa-eye-slash" );
        //     $('#show_hide_password4 i').addClass( "fa-eye" );
        // }
    });
    $("#show_hide_password5 a").on('click', function(event) {
        event.preventDefault();
        // if($('#show_hide_password5 input').attr("type") == "text"){
        //     $('#show_hide_password5 input').attr('type', 'password');
        //     $('#show_hide_password5 i').addClass( "fa-eye-slash" );
        //     $('#show_hide_password5 i').removeClass( "fa-eye" );
        // }else if($('#show_hide_password5 input').attr("type") == "password"){
        //     $('#show_hide_password5 input').attr('type', 'text');
        //     $('#show_hide_password5 i').removeClass( "fa-eye-slash" );
        //     $('#show_hide_password5 i').addClass( "fa-eye" );
        // }
    });
});
</script>
@endsection