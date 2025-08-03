@extends('admin.layout.base')

@section('title', 'Add Promocode ')

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/jquery.datetimepicker.min.css" />
@endsection

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">

    	<div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">@lang('admin.member.promocodes')</h4><a href="{{ route('admin.promocode.index') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">@lang('admin.list_promocodes')</a>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">@lang('admin.member.add_promocode')</li>
                </ol>
            </div>
        </div>

    	<div class="box box-block bg-white">
			<h5>@lang('admin.member.add_promocode')</h5>
            <form class="form-horizontal" action="{{route('admin.promocode.store')}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
				<div class="form-group row">
					<label for="promo_code" class="col-xs-2 col-form-label">@lang('admin.member.promocode')</label>
					<div class="col-xs-6">
						<input class="form-control" autocomplete="off"  type="text" value="{{ old('promo_code') }}" name="promo_code" required id="promo_code" placeholder="@lang('admin.member.promocode')">
					</div>
				</div>
				<div class="form-group row">
					<label for="discount" class="col-xs-2 col-form-label">@lang('admin.member.discount')</label>
					<div class="col-xs-6">
						<input class="form-control" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="{{ old('discount') }}" name="discount" required id="discount" placeholder="@lang('admin.member.discount')">
					</div>
				</div>
				<div class="form-group row">
					<label for="discount_type" class="col-xs-2 col-form-label">Discount Type</label>
					<div class="col-xs-6">
						<select name="discount_type" class="form-control" id="discount_type">
							<option value="flat">Flat</option>
							<option value="percent">Percentage</option>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label for="user_type" class="col-xs-2 col-form-label">User Type</label>
					<div class="col-xs-6">
						<select name="user_type" class="form-control" id="user_type">
							<option value="all">All Users</option>
							<option value="new">New Users</option>
						</select>
					</div>
				</div>

				<!-- <div class="form-group row">
					<label for="use_count" class="col-xs-2 col-form-label">Use Count</label>
					<div class="col-xs-6">
						<input class="form-control" type="tel" value="{{ old('use_count') }}" name="use_count" required id="use_count" placeholder="Use Count">
					</div>
				</div>	 -->
						<!-- <input class="form-control" type="tel" value="1" name="use_count"> -->

<!-- 				<div class="form-group row">
					<label for="use_count" class="col-xs-2 col-form-label">Usage Count</label>
					<div class="col-xs-6">
						<select name="use_count" class="form-control" id="use_count">
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
						</select>
					</div>
				</div>

 -->				<div class="form-group row">
					<label for="starting_at" class="col-xs-2 col-form-label">Starting At</label>
					<div class="col-xs-6">
						<input class="form-control" type="text" value="{{ old('starting_at') }}" name="starting_at" required id="starting_at" placeholder="Starting at">
					</div>
				</div>

				<div class="form-group row">
					<label for="expiration" class="col-xs-2 col-form-label">@lang('admin.member.expiration')</label>
					<div class="col-xs-6">
						<input class="form-control" type="text" value="{{ old('expiration') }}" name="expiration" required id="expiration" placeholder="@lang('admin.member.expiration')">
					</div>
				</div>
				<div class="form-group row">
					<label for="description" class="col-xs-2 col-form-label">Description</label>
					<div class="col-xs-6">
						<textarea name="description" id="description" class="form-control" required cols="30" rows="10">{{ old('desc') }}</textarea>
					</div>
				</div>

				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-6">
						<button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('admin.member.add_promocode')</button>
                        <a href="{{ route('admin.promocode.index') }}" class="btn btn-inverse waves-effect waves-light">@lang('admin.member.cancel')</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.full.min.js"></script>
<script type="text/javascript">
    var mindate = {!! json_encode( \Carbon\Carbon::today()->format('Y-m-d\TH:i') ) !!}
    $('#expiration').datetimepicker({
         timepicker:false,
         format:'Y-m-d',
         minDate: mindate
    });
    $('#starting_at').datetimepicker({
         timepicker:false,
         format:'Y-m-d',
         minDate: mindate
    });
</script>
@endsection