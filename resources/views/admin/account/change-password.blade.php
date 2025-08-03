@extends('admin.layout.base')

@section('title', 'Change Password ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">

    	<div class="row bg-title">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h4 class="page-title">@lang('admin.settings')</h4>
                <a href="{{ route('admin.settings') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">@lang('admin.member.site_settings')</a>
                <a href="{{ route('admin.settings.payment') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">@lang('admin.payment_settings')</a>
                <a href="{{ route('admin.profile') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">@lang('admin.account_settings')</a>
                <a href="{{ route('admin.password') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light active">@lang('admin.change_password')</a>
            </div>
        </div>

    	<div class="box box-block bg-white">

			<h5>@lang('admin.member.change_password')</h5>

            <form class="form-horizontal" action="{{route('admin.password.update')}}" method="POST" role="form">
            	{{csrf_field()}}

            	<div class="form-group row">
					<label for="old_password" class="col-xs-12 col-form-label">@lang('admin.member.old_password')</label>
					<div class="col-xs-10">
						<input class="form-control" type="password" name="old_password" id="old_password" placeholder="@lang('admin.member.old_password')">
					</div>
				</div>

				<div class="form-group row">
					<label for="password" class="col-xs-12 col-form-label">@lang('admin.member.password')</label>
					<div class="col-xs-10">
						<input class="form-control" type="password" name="password" id="password" placeholder="@lang('admin.member.new_password')">
					</div>
				</div>

				<div class="form-group row">
					<label for="password_confirmation" class="col-xs-12 col-form-label">@lang('admin.member.password_confirmation')</label>
					<div class="col-xs-10">
						<input class="form-control" type="password" name="password_confirmation" id="password_confirmation" placeholder="@lang('admin.member.re_type')">
					</div>
				</div>

				<div class="form-group row">
					<label for="zipcode" class="col-xs-12 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('admin.member.change_password')</button>
						<a href="{{route('admin.index')}}" class="btn btn-inverse waves-effect waves-light">@lang('admin.member.cancel')</a>
					</div>
				</div>

			</form>
		</div>
    </div>
</div>

@endsection
