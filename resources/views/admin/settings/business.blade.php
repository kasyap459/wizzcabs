@extends('admin.layout.base')

@section('title', 'Business Settings ')

@section('styles')
<style type="text/css">
	.display {
		display: none;
	}
</style>
@endsection

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">

    	<div class="row bg-title">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h4 class="page-title">Business Settings</h4>
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

            <form class="form-horizontal" action="{{ route('admin.settings.store_business') }}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
            	
				<div class="form-group row">
					<label for="mail_enable" class="col-xs-2 col-form-label">@lang('admin.member.mail_enable')</label>
					<div class="col-xs-8">
						<select class="form-control" id="mail_enable" name="mail_enable" >
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
						<input class="form-control" type="text" value="{{ $country_code  }}" name="country_code"  id="country_code" placeholder="+123">
					</div>
				</div>
				<div class="form-group row">
					<label for="provider_select_timeout" class="col-xs-2 col-form-label">@lang('admin.member.provider_accept_timeout')</label>
					<div class="col-xs-8">
						<input class="form-control" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="{{ Setting::get('provider_select_timeout', '60')  }}" name="provider_select_timeout" required id="provider_select_timeout" placeholder="Provider Timout" >
					</div>
				</div>

				<div class="form-group row">
					<label for="offline_time" class="col-xs-2 col-form-label">Driver Auto offline (min)</label>
					<div class="col-xs-8">
						<input class="form-control" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="{{ Setting::get('offline_time', '60')  }}" name="offline_time" required id="offline_time" placeholder="Driver Auto offline time (min)" >
					</div>
				</div>

				<!-- <div class="form-group row">
					<label for="min_wallet" class="col-xs-2 col-form-label">User Wallet Minimum Balance</label>
					<div class="col-xs-8">
						<input class="form-control" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="{{ Setting::get('min_wallet', '60')  }}" name="min_wallet" required id="min_wallet" placeholder="User Wallet Minimum Balance" >
					</div>
				</div> -->

				<div class="form-group row">
					<label for="provider_search_radius" class="col-xs-2 col-form-label">@lang('admin.member.provider_search_radius')</label>
					<div class="col-xs-8">
						<input class="form-control" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="{{ Setting::get('provider_search_radius', '100')  }}" name="provider_search_radius" required id="provider_search_radius" placeholder="Provider Search Radius" >
					</div>
				</div>	
				<div class="form-group row">
					<label for="distance_unit" class="col-xs-2 col-form-label">Distance unit</label>
					<div class="col-xs-8">
						<select class="form-control" id="distance_unit" name="distance_unit">
							<option value="km" @if(Setting::get('distance_unit') == 'km') selected @endif>Km</option>
							<option value="miles" @if(Setting::get('distance_unit') == 'miles') selected @endif>Miles</option>
						</select>
					</div>
				</div>	
				<div class="form-group row">
					<label for="auto_assign" class="col-xs-2 col-form-label">Auto assign</label>
					<div class="col-xs-8">
					<select class="form-control" id="sms_enable" name="sms_enable">
						<option value="1" @if(Setting::get('auto_assign', 0) == 1) selected @endif>Enable</option>
						<option value="0" @if(Setting::get('auto_assign', 0) == 0) selected @endif>Disable</option>
					</select>
					</div>
				</div>	
				
				<div class="form-group row">
                    <label for="tax_percentage" class="col-xs-2 col-form-label">@lang('admin.member.country')</label>
                    <div class="col-xs-8">
                        <input class="form-control" type="text" value="{{ Setting::get('country', '')  }}" name="country" id="country" placeholder="Site Country" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="tax_percentage" class="col-xs-2 col-form-label">@lang('admin.member.state')</label>
                    <div class="col-xs-8">
                        <input class="form-control" type="text" value="{{ Setting::get('state', '')  }}" name="state" id="state" placeholder="Site State" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="tax_percentage" class="col-xs-2 col-form-label">@lang('admin.member.city')</label>
                    <div class="col-xs-8">
                        <input class="form-control" type="text" value="{{ Setting::get('city', '')  }}" name="city" id="city" placeholder="Site City" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="tax_percentage" class="col-xs-2 col-form-label">Address</label>
                    <div class="col-xs-8">
                        <input class="form-control" type="text" onfocus="initMap()" value="{{ Auth::guard('admin')->user()->admin_address }}" name="address" id="address" placeholder="Address">
                        <input type="hidden" name="address_lat" id="a_lat" value="{{ Auth::guard('admin')->user()->admin_lat }}">
                        <input type="hidden" name="address_long" id="a_long" value="{{ Auth::guard('admin')->user()->admin_long }}">
                    </div>
                </div>
				<div class="form-group row">
					<label for="country_code" class="col-xs-2 col-form-label">Available Tips</label>
					<div class="col-xs-2">
						<input class="form-control" type="text" value="{{ Setting::get('tip_1',1)  }}" name="tip_1"  id="tip_1" placeholder="Tips" >
					</div>
					<div class="col-xs-2">
						<input class="form-control" type="text" value="{{ Setting::get('tip_2',2)  }}" name="tip_2"  id="tip_2" placeholder="Tips" >
					</div>
					<div class="col-xs-2">
						<input class="form-control" type="text" value="{{ Setting::get('tip_3',3)  }}" name="tip_3"  id="tip_0" placeholder="Tips" >
					</div>
					<div class="col-xs-2">
						<input class="form-control" type="text" value="{{ Setting::get('tip_4',4)  }}" name="tip_4"  id="tip_4" placeholder="Tips" >
					</div>
				</div>
                {{-- <div class="form-group row">
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
                </div> --}}
                <div class="form-group row">
					<label for="zoom" class="col-xs-2 col-form-label">Map Zoom Level</label>
					<div class="col-xs-8">
						<select class="form-control" id="zoom" name="zoom">
<!-- 							<option value="1" @if(Auth::guard('admin')->user()->admin_zoom == 1) selected @endif>1</option>
							<option value="2" @if(Auth::guard('admin')->user()->admin_zoom == 2) selected @endif>2</option>
							<option value="3" @if(Auth::guard('admin')->user()->admin_zoom == 3) selected @endif>3</option>
							<option value="4" @if(Auth::guard('admin')->user()->admin_zoom == 4) selected @endif>4</option>
							<option value="5" @if(Auth::guard('admin')->user()->admin_zoom == 5) selected @endif>5</option>
							<option value="6" @if(Auth::guard('admin')->user()->admin_zoom == 6) selected @endif>6</option>
							<option value="7" @if(Auth::guard('admin')->user()->admin_zoom == 7) selected @endif>7</option>
							<option value="8" @if(Auth::guard('admin')->user()->admin_zoom == 8) selected @endif>8</option>
							<option value="9" @if(Auth::guard('admin')->user()->admin_zoom == 9) selected @endif>9</option>
 -->							<option value="10" @if(Auth::guard('admin')->user()->admin_zoom == 8) selected @endif>10</option>
<!-- 							<option value="11" @if(Auth::guard('admin')->user()->admin_zoom == 11) selected @endif>11</option>
							<option value="12" @if(Auth::guard('admin')->user()->admin_zoom == 12) selected @endif>12</option>
 -->						</select>
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
				<div class="form-group row">
				<label for="zipcode" class="col-xs-2 col-form-label"></label>
				<div class="col-xs-8">
						<button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Update Business Settings</button>
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
@endsection