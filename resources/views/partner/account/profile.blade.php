@extends('partner.layout.base')

@section('title', 'Update Profile ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="row bg-title">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h4 class="page-title">@lang('admin.member.update_profile')</h4>
            </div>
        </div>
    	<div class="box box-block bg-white">

			<h5>@lang('admin.member.update_profile')</h5>

            <form class="form-horizontal" action="{{route('partner.profile.update')}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}

				<div class="form-group row">
					<label for="name" class="col-xs-2 col-form-label">@lang('admin.member.name')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Auth::guard('partner')->user()->name }}" name="name" required id="name" placeholder=" @lang('admin.member.name')">
					</div>
				</div>
				<div class="form-group row">
					<label for="carrier_name" class="col-xs-2 col-form-label">@lang('admin.member.company_name')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" required name="carrier_name" value="{{ isset(Auth::guard('partner')->user()->carrier_name) ? Auth::guard('partner')->user()->carrier_name : '' }}" id="carrier_name" placeholder="@lang('admin.member.company_name')">
					</div>
				</div>

				<div class="form-group row">
					<label for="mobile" class="col-xs-2 col-form-label">@lang('admin.member.mobile')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" required name="mobile" value="{{ isset(Auth::guard('partner')->user()->mobile) ? Auth::guard('partner')->user()->mobile : '' }}" id="mobile" placeholder="@lang('admin.member.mobile')">
					</div>
				</div>
				<div class="form-group row">
					<label for="pan_no" class="col-xs-2 col-form-label">Pan Number</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" required name="pan_no" value="{{ isset(Auth::guard('partner')->user()->pan_no) ? Auth::guard('partner')->user()->pan_no : '' }}" id="pan_no" placeholder="Pan Number">
					</div>
				</div>
				<div class="form-group row">
					<label for="address" class="col-xs-2 col-form-label">Address</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" required name="address" value="{{ isset(Auth::guard('partner')->user()->address) ? Auth::guard('partner')->user()->address : '' }}" id="address" placeholder="Address">
					</div>
				</div>
				<div class="form-group row">
					<label for="logo" class="col-xs-2 col-form-label">@lang('admin.member.company_logo')</label>
					<div class="col-xs-10">
						@if(isset(Auth::guard('partner')->user()->logo))
	                    	<img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{img(Auth::guard('partner')->user()->logo)}}">
	                    @endif
						<input type="file" accept="image/*" name="logo" class=" dropify form-control-file" aria-describedby="fileHelp">
					</div>
				</div>

				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('admin.member.update_profile')</button>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection
