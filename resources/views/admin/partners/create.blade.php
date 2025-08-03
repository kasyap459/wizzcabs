@extends('admin.layout.base')

@section('title', 'Add Sub-company ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">

    	<div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">Sub-company</h4><a href="{{ route('admin.partner.index') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">List Sub-company</a>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">Add Sub-company</li>
                </ol>
            </div>
        </div>

    	<div class="box box-block bg-white">
    		<h5>Add Sub-company</h5>
            <form class="form-horizontal" action="{{route('admin.partner.store')}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
				<div class="form-group row">
					<label for="name" class="col-xs-12 col-form-label">@lang('admin.member.full_name')</label>
					<div class="col-xs-8">
						<input class="form-control" type="text" value="{{ old('name') }}" name="name" required id="name" placeholder="@lang('admin.member.full_name')">
					</div>
				</div>
		
				<div class="form-group row">
					<label for="email" class="col-xs-12 col-form-label">@lang('admin.member.email')</label>
					<div class="col-xs-8">
						<input class="form-control" type="email" required name="email" value="{{old('email')}}" id="email" placeholder="@lang('admin.member.email')">
					</div>
				</div>

				<div class="form-group row">
					<label for="password" class="col-xs-12 col-form-label">@lang('admin.member.password')</label>
					<div class="col-xs-8">
						<input class="form-control" type="password" name="password" id="password" placeholder="@lang('admin.member.password')">
					</div>
				</div>

				<div class="form-group row">
					<label for="password_confirmation" class="col-xs-12 col-form-label">@lang('admin.member.password_confirmation')</label>
					<div class="col-xs-8">
						<input class="form-control" type="password" name="password_confirmation" id="password_confirmation" placeholder="@lang('admin.member.re_type')">
					</div>
				</div>
				<div class="form-group row">
					<label for="carrier_name" class="col-xs-12 col-form-label">Sub-company name</label>
					<div class="col-xs-8">
						<input class="form-control" type="text" value="{{ old('carrier_name') }}" name="carrier_name" id="carrier_name" placeholder="Sub-company name">
					</div>
				</div>
				<div class="form-group row">
					<label for="carrier_percentage" class="col-xs-12 col-form-label">Sub-company percentage</label>
					<div class="col-xs-8">
						<input class="form-control" type="number" value="{{ old('carrier_percentage') }}" name="carrier_percentage" id="carrier_percentage" placeholder="Sub-company percentage">
					</div>
				</div>
				<div class="form-group row">
					<label for="logo" class="col-xs-12 col-form-label">Sub-company logo</label>
					<div class="col-xs-8">
						<input type="file" accept="image/*" name="logo" class="dropify form-control-file" id="logo" aria-describedby="fileHelp">
					</div>
				</div>
				<div class="form-group row">
					<label for="pan_no" class="col-xs-12 col-form-label">PAN number</label>
					<div class="col-xs-8">
						<input class="form-control" type="text" value="{{ old('pan_no') }}" name="pan_no" id="pan_no" placeholder="PAN number">
					</div>
				</div>
				<div class="form-group row">
					<label for="country_id" class="col-xs-12 col-form-label">Country</label>
					<div class="col-xs-8">
						<select name="country_id" id="country_id" class="form-control">
							<option value="">Select Country</option>
							@foreach($countries as $country)
								<option value="{{ $country->countryid }}">{{ $country->name }}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label for="address" class="col-xs-12 col-form-label">Address</label>
					<div class="col-xs-8">
						<input class="form-control" type="text" value="{{ old('address') }}" name="address" required id="address" placeholder="Address">
					</div>
				</div>
				<div class="form-group row">
					<label for="mobile" class="col-xs-12 col-form-label">@lang('admin.member.mobile')</label>
					<div class="col-xs-8">
						<input class="form-control" type="number" value="{{ old('mobile') }}" name="mobile" required id="mobile" placeholder="@lang('admin.member.mobile')">
					</div>
				</div>
				
				<div class="form-group row">
					<label for="zipcode" class="col-xs-12 col-form-label"></label>
					<div class="col-xs-8">
						<button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Add Sub-company</button>
						<a href="{{route('admin.partner.index')}}" class="btn btn-inverse waves-effect waves-light">@lang('admin.member.cancel')</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection
