@extends('hotel.layout.base')

@section('title', 'Profile ')

@section('styles')
<style type="text/css">
    .form-control {
        margin-bottom: 10px;
    }
    .profile-img-blk{
        margin-bottom: 10px;
    }
    label{
        padding-top: 10px;
    }
</style>
@endsection

@section('content')
    
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    @if(Session::has('flash_error'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">×</button>
            {{ Session::get('flash_error') }}
        </div>
    @endif


    @if(Session::has('flash_success'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            {{ Session::get('flash_success') }}
        </div>
    @endif

    <div class="row no-margin">
        <div class="col-md-12">
            <h4 class="page-title">@lang('user.profile.edit_information')</h4> 
        </div>
    </div>
    <hr>
    <form action="{{url('/hotel/profile')}}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="form-group">
            <label class="col-md-4">@lang('user.profile.profile_picture')</label>
            <div class="profile-img-blk col-md-6">
                <div class="img_outer">
                    <img class="profile_preview" id="profile_image_preview" src="{{img(Auth::guard('hotel')->user()->picture)}}" alt="your image" style="width: 120px;" />
                </div>
                <div class="fileUpload up-btn profile-up-btn">                   
                    <input type="file" id="profile_img_upload_btn" name="picture" class="upload" accept="image/x-png, image/jpeg"/>
                </div>                             
            </div> 
        </div>
        <div class="form-group">
            <label class="col-md-4">@lang('user.profile.name')</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name="name" required placeholder="@lang('user.profile.name')" value="{{Auth::guard('hotel')->user()->name}}">
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4">@lang('user.profile.email')</label>
            <div class="col-md-6">
                <input type="email" class="form-control" placeholder="@lang('user.profile.email')" readonly value="{{Auth::guard('hotel')->user()->email}}">
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4">@lang('user.profile.mobile')</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name="mobile" required placeholder="@lang('user.profile.mobile')" value="{{Auth::guard('hotel')->user()->mobile}}">
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4">Country</label>
            <div class="col-md-6">
                <select class="form-control" name="country_id">
                    @foreach($countries as $country)
                    <option value="{{ $country->countryid }}">{{ $country->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4">Address</label>
            <div class="col-md-6">
                <input type="text" class="form-control" id="address" name="address" required placeholder="Address" value="{{Auth::guard('hotel')->user()->address}}" onfocus="initMap()">
                <input type="hidden" name="latitude" id="latitude" value="{{ Auth::guard('hotel')->user()->latitude }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ Auth::guard('hotel')->user()->longitude }}">
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4"></label>
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary">@lang('user.profile.save')</button>
            </div>
        </div>

    </form>

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