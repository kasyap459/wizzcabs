@extends('dispatcher.layout.base')

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

            <form class="form-horizontal" action="{{route('dispatcher.profile.update')}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}

				<div class="form-group row">
					<label for="name" class="col-xs-2 col-form-label">@lang('admin.member.name')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Auth::guard('dispatcher')->user()->name }}" name="name" required id="name" placeholder=" @lang('admin.member.name')">
					</div>
				</div>

				<div class="form-group row">
					<label for="email" class="col-xs-2 col-form-label">@lang('admin.member.email')</label>
					<div class="col-xs-10">
						<input class="form-control" type="email" required name="email" value="{{ isset(Auth::guard('dispatcher')->user()->email) ? Auth::guard('dispatcher')->user()->email : '' }}" id="email" placeholder="@lang('admin.member.email')">
					</div>
				</div>

				<div class="form-group row">
					<label for="mobile" class="col-xs-2 col-form-label">@lang('admin.member.mobile')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Auth::guard('dispatcher')->user()->mobile }}" name="mobile" required id="mobile" placeholder=" @lang('admin.member.mobile_number')">
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
