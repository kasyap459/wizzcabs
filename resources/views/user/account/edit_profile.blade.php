@extends('user.layout.base')

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
    <form action="{{url('profile')}}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="form-group">
            <label class="col-md-4">@lang('user.profile.profile_picture')</label>
            <div class="profile-img-blk col-md-6">
                <div class="img_outer">
                    <img class="profile_preview" id="profile_image_preview" src="{{img(Auth::user()->picture)}}" alt="your image" style="width: 99px;" />
                </div>
                <div class="fileUpload up-btn profile-up-btn" style="margin-top: 20px;">                   
                    <input type="file" id="profile_img_upload_btn" name="picture" class="upload" accept="image/x-png, image/jpeg"/>
                </div>                             
            </div> 
        </div>
        <div class="form-group">
            <label class="col-md-4">First @lang('user.profile.name')</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name="first_name" required placeholder="First @lang('user.profile.name')" value="{{Auth::user()->first_name}}">
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-4">Last @lang('user.profile.name')</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name="last_name" required placeholder="Last @lang('user.profile.name')" value="{{Auth::user()->last_name}}">
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4">@lang('user.profile.email')</label>
            <div class="col-md-6">
                <input type="email" class="form-control" placeholder="@lang('user.profile.email')" readonly value="{{Auth::user()->email}}">
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4">@lang('user.profile.mobile')</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name="mobile" required placeholder="@lang('user.profile.mobile')" value="{{Auth::user()->mobile}}">
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4">Gender</label>
            <div class="col-md-6">
                <select class="form-control" name="gender">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
        </div>
        <!-- <div class="form-group">
            <label class="col-md-4">Country</label>
            <div class="col-md-6">
                <select class="form-control" name="country_id">
                    @foreach($countries as $country)
                    <option value="{{ $country->countryid }}">{{ $country->name }}</option>
                    @endforeach
                </select>
            </div>
        </div> -->
        <div class="form-group">
            <label class="col-md-4"></label>
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary">@lang('user.profile.save')</button>
            </div>
        </div>

    </form>

@endsection