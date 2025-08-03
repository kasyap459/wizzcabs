@extends('provider.layout.base')

@section('title', 'Profile ')

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/jquery.datetimepicker.min.css" />
<link rel="stylesheet" href="{{asset('main/vendor/select2/dist/css/select2.min.css')}}">
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
    <form action="{{url('/provider/profile')}}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="form-group row">
            <label class="col-md-4">@lang('user.profile.profile_picture')</label>
            <div class="profile-img-blk col-md-6">
                <div class="img_outer">
                    <img class="profile_preview" id="profile_image_preview" src="{{img(Auth::guard('provider')->user()->avatar)}}" alt="your image" style="width: 99px;" />
                </div>
                <div class="fileUpload up-btn profile-up-btn" style="margin-top: 20px;">                   
                    <input type="file" id="profile_img_upload_btn" name="avatar" class="upload" accept="image/x-png, image/jpeg"/>
                </div>                             
            </div> 
        </div>
        <div class="form-group row">
            <label class="col-md-4">@lang('user.profile.name')</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name="name" required placeholder="@lang('user.profile.name')" value="{{Auth::guard('provider')->user()->name}}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-4">@lang('user.profile.email')</label>
            <div class="col-md-6">
                <input type="email" class="form-control" placeholder="@lang('user.profile.email')" readonly value="{{Auth::guard('provider')->user()->email}}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-4">@lang('user.profile.mobile')</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name="mobile" required placeholder="@lang('user.profile.mobile')" value="{{Auth::guard('provider')->user()->mobile}}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-4">Gender</label>
            <div class="col-md-6">
                <select class="form-control" name="gender">
                    <option value="Male" @if(Auth::guard('provider')->user()->gender =='Male' ) selected @endif>Male</option>
                    <option value="Female" @if(Auth::guard('provider')->user()->gender =='Female' ) selected @endif>Female</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-4">Country</label>
            <div class="col-md-6">
                <select class="form-control" name="country_id">
                    <option value="{{ $countries[1]['countryid'] }}">{{ $countries[1]['name'] }}</option>
                    <option value="{{ $countries[2]['countryid'] }}">{{ $countries[2]['name'] }}</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-4">Language</label>
            <div class="col-md-6">
                <select name="language[]" id="language" required="required" class="form-control" data-plugin="select2" multiple="multiple">
                    <option value="1">English</option>
                    <option value="2">Spanish</option>
                    <option value="3">French</option>
                    <option value="4">Korean</option>
                    <option value="5">Russian</option>
                    <option value="6">German</option>
                    <option value="7">Portuguese</option>
                    <option value="8">Italian</option>
                    <option value="9">Urdu</option>
                    <option value="10">Chinese</option>
                    <option value="11">Tagalog</option>
                    <option value="12">Vietnamese</option>
                    <option value="13">Swahili</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-4">Bank account number</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name="acc_no" required placeholder="Bank account number" value="{{Auth::guard('provider')->user()->acc_no}}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-4">License Number</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name="license_no" required placeholder="Bank account number" value="{{Auth::guard('provider')->user()->license_no}}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-4">License Expire At</label>
            <div class="col-md-6">
                <input type="text" class="form-control" id="license_expire" name="license_expire" required placeholder="Bank account number" value="{{Auth::guard('provider')->user()->license_expire}}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-4"></label>
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary">@lang('user.profile.save')</button>
            </div>
        </div>

    </form>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.full.min.js"></script>
<script type="text/javascript" src="{{asset('main/vendor/select2/dist/js/select2.min.js')}}"></script>
<script type="text/javascript">
    var mindate = {!! json_encode( \Carbon\Carbon::today()->format('Y-m-d\TH:i') ) !!}
    $('#license_expire').datetimepicker({
        format:'Y-m-d',
        timepicker: false,
        minDate: mindate
    });

    $('[data-plugin="select2"]').select2($(this).attr('data-options'));
    @if(Auth::guard('provider')->user()->language !='')
        $('#language').val([{{ Auth::guard('provider')->user()->language }}]).trigger('change');
    @endif
</script>
@endsection