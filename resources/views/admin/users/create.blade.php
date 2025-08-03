@extends('admin.layout.base')

@section('title', 'Add User ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	
    	<div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">@lang('admin.member.users')</h4><a href="{{ route('admin.user.index') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">@lang('admin.list_users')</a>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">@lang('admin.add_new_user')</li>
                </ol>
            </div>
        </div>

    	<div class="box box-block bg-white">
			<h5 style="margin-bottom: 2em;">@lang('admin.member.add_user')</h5>
            <form class="form-horizontal" action="{{route('admin.user.store')}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
				<div class="form-group row">
					<label for="name" class="col-xs-2 col-form-label">@lang('admin.member.name')</label>
					<div class="col-xs-6">
						<input class="form-control" type="text" value="{{ old('first_name') }}" name="first_name" required id="first_name" placeholder="@lang('admin.member.name')">
					</div>
				</div>
				<div class="form-group row">
					<label for="name" class="col-xs-2 col-form-label">Last Name</label>
					<div class="col-xs-6">
						<input class="form-control" type="text" value="{{ old('last_name') }}" name="last_name" required id="last_name" placeholder="@lang('admin.member.name')">
					</div>
				</div>

				<div class="form-group row">
					<label for="email" class="col-xs-2 col-form-label">@lang('admin.member.email')</label>
					<div class="col-xs-6">
						<input class="form-control" type="email" required name="email" value="{{old('email')}}" id="email" placeholder="@lang('admin.member.email')">
					</div>
				</div>

				<div class="form-group row">
					<label for="password" class="col-xs-2 col-form-label">@lang('admin.member.password')</label>
					<div class="col-xs-6">
						<input class="form-control" type="password" name="password" id="password" placeholder="@lang('admin.member.password')">
					</div>
				</div>

				<div class="form-group row">
					<label for="password_confirmation" class="col-xs-2 col-form-label">@lang('admin.member.password_confirmation')</label>
					<div class="col-xs-6">
						<input class="form-control" type="password" name="password_confirmation" id="password_confirmation" placeholder="@lang('admin.member.re_type')">
					</div>
				</div>
				<div class="form-group row">
                    <label for="gender" class="col-xs-2 col-form-label">Gender</label>
                    <div class="col-xs-6">
                        <select name="gender" id="gender" required="required" class="form-control">
                        	<option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                </div>
				<div class="form-group row">
					<label for="picture" class="col-xs-2 col-form-label">@lang('admin.member.picture')</label>
					<div class="col-xs-6">
						<input type="file" accept="image/*" name="picture" class="dropify form-control-file" id="picture" aria-describedby="fileHelp">
					</div>
				</div>

				<div class="form-group row">
					<label for="mobile" class="col-xs-2 col-form-label">@lang('admin.member.mobile')</label>
					<div class="col-xs-6">
						<input class="form-control" type="text"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  value="{{ old('mobile') }}" name="mobile" required id="mobile" placeholder="@lang('admin.member.mobile')">
					</div>
				</div>
				<div class="form-group row">
					<label for="country_id" class="col-xs-2 col-form-label">Country</label>
					<div class="col-xs-6">
						<select name="country_id" id="country_id" class="form-control">
							<option value="">Select Country</option>
							@foreach($countries as $country)
								<option value="{{ $country->countryid }}">{{ $country->name }}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="form-group row">
                    <label for="custom_field1" class="col-xs-2 col-form-label">Custom field 1</label>
                    <div class="col-xs-6">
                        <input class="form-control" type="text" value="{{ old('custom_field1') }}" name="custom_field1" id="custom_field1" placeholder="Custom field 1">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="custom_field2" class="col-xs-2 col-form-label">Custom field 2</label>
                    <div class="col-xs-6">
                        <input class="form-control" type="text" value="{{ old('custom_field2') }}" name="custom_field2" id="custom_field2" placeholder="Custom field 2">
                    </div>
                </div>
				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('admin.member.add_user')</button>
						<a href="{{route('admin.user.index')}}" class="btn btn-inverse waves-effect waves-light">@lang('admin.member.cancel')</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection
