@extends('admin.layout.base')

@section('title', 'SMS Notification ')

@section('styles')

<link rel="stylesheet" href="{{asset('main/vendor/multi-select/css/multi-select.css')}}">
<style>
	input[type="checkbox"]{
	  width: 20px !important; 
	  height: 20px !important;
	}
	.searchboxs{
		width: 100%;
	    margin-bottom: 9px;
	    border: 1px solid #d2cece;
	    border-radius: 2px;
	    padding: 4px 10px;
	}
</style>
@endsection

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">

    	<div class="row bg-title">
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                <h4 class="page-title">SMS Notification to Driver</h4><a href="{{ route('admin.sms.index') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">Passenger Notification</a>
            </div>
        </div>

    	<div class="box box-block bg-white">
            <form class="form-horizontal" action="{{route('admin.sms.driverstore')}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
				<div class="form-group row">
					<label for="select_drivers" class="col-xs-12 col-form-label">@lang('admin.member.select_drivers')</label>
            		<div class="col-xs-10">
						<a href="#" class="btn btn-info btn-rounded" id='select-all'>@lang('admin.member.select_all')</a>
						<a href="#" class="btn btn-warning btn-rounded" id='deselect-all'>@lang('admin.member.clear_all')</a>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-xs-10">
					<select id='public-methods' name="Providers[]" multiple='multiple'>
					  @foreach($Providers as $index => $provider)
					  <option value='{{$provider->id}}'>{{$provider->name}} ({{$provider->dial_code}} {{$provider->mobile}})</option>
					  @endforeach
					</select>
				</div>
				</div>
				<div class="form-group row">
					<label for="push_content" class="col-xs-12 col-form-label">@lang('admin.member.message')</label>
					<div class="col-xs-9">
						<textarea name="push_content" id="push_content" cols="30" rows="10" class="form-control"></textarea>
					</div>
				</div>
				<div class="form-group row">
					<label for="zipcode" class="col-xs-12 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-success"><i class="fa fa-check"></i> @lang('admin.member.send_message')</button>
						<a href="{{route('admin.sms.driver.index')}}" class="btn btn-inverse waves-effect waves-light">@lang('admin.member.cancel')</a>
					</div>
				</div>
			</form>
		</div>

	<div class="box box-block bg-white">
		<form action="{{ route('admin.sms.destroy') }}" method="POST">
			{{ csrf_field() }}
		<table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                    	<th>@lang('admin.member.select')</th>
                        <th>@lang('admin.member.id')</th>
                        <th>@lang('admin.member.message')</th>
                        <th>@lang('admin.member.drivers')</th>
                        <th>@lang('admin.member.created_at')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pushdatas as $index => $pushdata)
                    <tr>
                    	<td><input type="checkbox" class="form-control" name="checkbox[]" value="{{ $pushdata->id }}"></td>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $pushdata->message }}</td>
                        <td>{{ $pushdata->mobile_numbers }}</td>
 						<td>{{ $pushdata->created_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>@lang('admin.member.select')</th>
                        <th>@lang('admin.member.id')</th>
                        <th>@lang('admin.member.message')</th>
                        <th>@lang('admin.member.drivers')</th>
                        <th>@lang('admin.member.created_at')</th>
                    </tr>
                </tfoot>
            </table>
            <button type="submit" class="btn btn-danger btn-rounded label-left b-a-0 waves-effect waves-light"><span class="btn-label"><i class="fa fa-trash"></i></span>@lang('admin.member.delete')</button>
            </form>
	</div>

    </div>
</div>

@endsection


@section('scripts')
<script type="text/javascript" src="{{asset('main/assets/js/jquery.quicksearch.js')}}"></script>
<script type="text/javascript" src="{{asset('main/vendor/multi-select/js/jquery.multi-select.js')}}"></script>
<script>
	$('#select-all').click(function(){
	  $('#public-methods').multiSelect('select_all');
	  return false;
	});
	$('#deselect-all').click(function(){
	  $('#public-methods').multiSelect('deselect_all');
	  return false;
	});
	$('#public-methods').multiSelect({
	selectableHeader: "<input type='text' class='search-input searchboxs' autocomplete='off' placeholder='Search here'>",
	selectionHeader: "<input type='text' class='search-input searchboxs' autocomplete='off' placeholder='Search here'>",
	afterInit: function(ms){
	var that = this,
	    $selectableSearch = that.$selectableUl.prev(),
	    $selectionSearch = that.$selectionUl.prev(),
	    selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
	    selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

	that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
	.on('keydown', function(e){
	  if (e.which === 40){
	    that.$selectableUl.focus();
	    return false;
	  }
	});

	that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
	.on('keydown', function(e){
	  if (e.which == 40){
	    that.$selectionUl.focus();
	    return false;
	  }
	});
	},
	afterSelect: function(){
	this.qs1.cache();
	this.qs2.cache();
	},
	afterDeselect: function(){
	this.qs1.cache();
	this.qs2.cache();
	}
	});
</script>
@endsection