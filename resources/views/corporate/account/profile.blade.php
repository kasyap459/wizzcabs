@extends('corporate.layout.base')

@section('title', 'Update Profile ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">

    	<div class="row bg-title">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h4 class="page-title">@lang('admin.settings')</h4>
                <a href="{{ route('corporate.profile') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light active">@lang('admin.account_settings')</a>
                <a href="{{ route('corporate.password') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">@lang('admin.change_password')</a>
            </div>
        </div>

    	<div class="box box-block bg-white">

			<h5>@lang('admin.member.update_profile')</h5>

            <form class="form-horizontal" action="{{route('corporate.profile.update')}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}

				<div class="form-group row">
					<label for="legal_name" class="col-xs-2 col-form-label">Legal Name</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Auth::guard('corporate')->user()->legal_name }}" name="legal_name" required id="legal_name" placeholder="@lang('admin.member.name')">
					</div>
				</div>
				<div class="form-group row">
					<label for="display_name" class="col-xs-2 col-form-label">Display Name</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Auth::guard('corporate')->user()->display_name }}" name="display_name" required id="display_name" placeholder="@lang('admin.member.name')">
					</div>
				</div>
				<div class="form-group row">
					<label for="email" class="col-xs-2 col-form-label">@lang('admin.member.email')</label>
					<div class="col-xs-10">
						<input class="form-control" type="email" required name="email" value="{{ isset(Auth::guard('corporate')->user()->email) ? Auth::guard('corporate')->user()->email : '' }}" id="email" placeholder="@lang('admin.member.email')">
					</div>
				</div>
				<div class="form-group row">
					<label for="secondary_email" class="col-xs-2 col-form-label">Secondary Email</label>
					<div class="col-xs-10">
						<input class="form-control" type="email" required name="secondary_email" value="{{ isset(Auth::guard('corporate')->user()->secondary_email) ? Auth::guard('corporate')->user()->secondary_email : '' }}" id="secondary_email" placeholder="Secondary Email">
					</div>
				</div>
				<div class="form-group row">
					<label for="pan_no" class="col-xs-2 col-form-label">Pan Number</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" required name="pan_no" value="{{ isset(Auth::guard('corporate')->user()->pan_no) ? Auth::guard('corporate')->user()->pan_no : '' }}" id="pan_no" placeholder="Pan Number">
					</div>
				</div>
				<div class="form-group row">
					<label for="address" class="col-xs-2 col-form-label">Address</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" required name="address" value="{{ isset(Auth::guard('corporate')->user()->address) ? Auth::guard('corporate')->user()->address : '' }}" id="address" placeholder="Address">
					</div>
				</div>
				<div class="form-group row">
					<label for="picture" class="col-xs-2 col-form-label">@lang('admin.member.picture')</label>
					<div class="col-xs-10">
						@if(isset(Auth::guard('admin')->user()->picture))
	                    	<img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{img(Auth::guard('corporate')->user()->picture)}}">
	                    @endif
						<input type="file" accept="image/*" name="picture" class=" dropify form-control-file" aria-describedby="fileHelp">
					</div>
				</div>

				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('admin.member.update_profile')</button>
						<a href="{{route('corporate.index')}}" class="btn btn-inverse waves-effect waves-light">@lang('admin.member.cancel')</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection
