@extends('admin.layout.base')

@section('title', 'Update Account Manager ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">

    	<div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">@lang('admin.member.account_manager')</h4><a href="{{ route('admin.account-manager.index') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">@lang('admin.list_account_managers')</a>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">@lang('admin.member.update_account_manager')</li>
                </ol>
            </div>
        </div>

    	<div class="box box-block bg-white">
			<h5>@lang('admin.member.update_account_manager')</h5>
            <form class="form-horizontal" action="{{route('admin.account-manager.update', $account->id )}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
            	<input type="hidden" name="_method" value="PATCH">
				
				<div class="form-group row">
					<label for="name" class="col-xs-12 col-form-label">@lang('admin.member.full_name')</label>
					<div class="col-xs-8">
						<input class="form-control" type="text" value="{{ $account->name }}" name="name" required id="name" placeholder="@lang('admin.member.full_name')">
					</div>
				</div>

				<div class="form-group row">
					<label for="email" class="col-xs-12 col-form-label">@lang('admin.member.email')</label>
					<div class="col-xs-8">
						<input class="form-control" type="text" value="{{ $account->email }}" name="email" required id="email" placeholder="@lang('admin.member.email')">
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
					<label for="picture" class="col-xs-12 col-form-label">@lang('admin.member.picture')</label>
					<div class="col-xs-8">
					@if(isset($account->picture))
                    	<img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{ asset('storage/'.$account->picture) }}">
                    @endif
						<input type="file" accept="image/*" name="picture" class="dropify form-control-file" id="picture" aria-describedby="fileHelp">
					</div>
				</div>
				<div class="form-group row">
					<label for="country_id" class="col-xs-12 col-form-label">Country</label>
					<div class="col-xs-8">
						<select name="country_id" id="country_id" class="form-control">
							<option value="">Select Country</option>
							@foreach($countries as $country)
								<option value="{{ $country->countryid }}" @if($country->countryid == $account->country_id) selected @endif>{{ $country->name }}</option>
							@endforeach
						</select>
					</div>
				</div>

				<div class="form-group row">
					<label for="mobile" class="col-xs-12 col-form-label">@lang('admin.member.mobile')</label>
					<div class="col-xs-8">
						<input class="form-control" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="{{ $account->mobile }}" name="mobile" required id="mobile" placeholder="@lang('admin.member.mobile')">
					</div>
				</div>

				<div class="form-group row">
					<label for="zipcode" class="col-xs-12 col-form-label"></label>
					<div class="col-xs-8">
						<button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('admin.member.update_account_manager')</button>
						<a href="{{route('admin.account-manager.index')}}" class="btn btn-inverse waves-effect waves-light">@lang('admin.member.cancel')</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection
