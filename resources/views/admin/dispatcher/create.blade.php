@extends('admin.layout.base')

@section('title', 'Add Dispatcher ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">

    	<div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">@lang('admin.member.dispatcher')</h4><a href="{{ route('admin.dispatch-manager.index') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">@lang('admin.list_dispatcher')</a>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">@lang('admin.member.add_dispatcher')</li>
                </ol>
            </div>
        </div>

    	<div class="box box-block bg-white">
    		<h5>@lang('admin.member.add_dispatcher')</h5>
            <form class="form-horizontal" action="{{route('admin.dispatch-manager.store')}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
            	<div class="form-group row">
					<label for="email" class="col-xs-12 col-form-label">Dispatcher to</label>
					<div class="col-xs-10">
					<select class="form-control" id="dispatcher_select" name="dispatcher_select">
				       <option value="admin">Admin Dispatcher</option>
				       <option value="company">Company Dispatcher</option>
				                
				      </select>
				  </div>
				</div>
            	
            	<div class="form-group row" id="shows" style="display: none;">
					<label for="email" class="col-xs-12 col-form-label">Company</label>
					<div class="col-xs-10">
					<select class="form-control" id="fleet_id" name="fleet_id">
				        <option value="">Select company..</option>
							@foreach($partners as $partner)
								<option value="{{ $partner->id }}">{{ $partner->name }}</option>
							@endforeach
				      </select>
				  </div>
				</div>				<div class="form-group row">
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
					<label for="mobile" class="col-xs-12 col-form-label">@lang('admin.member.mobile')</label>
					<div class="col-xs-8">
						<input class="form-control" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="{{ old('mobile') }}" name="mobile" required id="mobile" placeholder="@lang('admin.member.mobile')">
					</div>
				</div>
<!-- 				<div class="form-group row">
					<label for="partner_id" class="col-xs-12 col-form-label">Carrier Name</label>
					<div class="col-xs-8">
						<select name="partner_id" id="partner_id" class="form-control">
							<option value="0">Select Carrier</option>
							@foreach($partners as $partner)
								<option value="{{ $partner->id }}">{{ $partner->name }}</option>
							@endforeach
						</select>
					</div>
				</div>
 -->				<div class="form-group row">
					<label for="zipcode" class="col-xs-12 col-form-label"></label>
					<div class="col-xs-8">
						<button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('admin.member.add_dispatcher')</button>
						<a href="{{route('admin.dispatch-manager.index')}}" class="btn btn-inverse waves-effect waves-light">@lang('admin.member.cancel')</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript">
$('#dispatcher_select').on('change', function() {
        var val = this.value;
        if(val == "company"){
            $('#shows').show();
        }else{
            $('#shows').hide();
            $('#fleet_id').val('');
        }
    });

</script>
@endsection